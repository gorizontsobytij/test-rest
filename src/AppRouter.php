<?php


namespace Src;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;

class AppRouter
{

    /**
     * AppRouter constructor.
     * @param Request $request
     * @param array $config
     */
    public function __construct(Request $request, array $config)
    {
        $fileLocator = new FileLocator([$config['root_dir']]);
        $loader = new Routing\Loader\YamlFileLoader($fileLocator);
        $routes = $loader->load($config['root_dir'] . '/src/routes.yaml');
        $context = (new Routing\RequestContext())->fromRequest($request);
        $matcher = new Routing\Matcher\UrlMatcher($routes, $context);
        try{
            $attributes = $matcher->match($request->getPathInfo());
            $this->callController($request, $attributes);
        } catch (Routing\Exception\ResourceNotFoundException $exception) {
            (new Response('Not Found', 404))->send();
        } catch (\Exception $exception) {
            echo($exception->getMessage());
            (new Response('Error', 500))->send();
        }
    }

    /**
     * @param Request $request
     * @param array $attributes
     */
    protected function callController(Request $request, array $attributes): void
    {
        if(!empty($attributes['_controller']) && substr_count($attributes['_controller'], '::') === 1){
            $code = explode('::', $attributes['_controller']);
            $controller = (string)$code[0];
            $action = (string)$code[1];
            (new $controller($request, $attributes))->$action();
        }
    }

}
