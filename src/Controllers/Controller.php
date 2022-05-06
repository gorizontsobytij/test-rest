<?php

namespace Src\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller
{
    /**
     * @var Request
     */
    protected Request $request;
    /**
     * @var array
     */
    protected array $attributes;

    /**
     * @var Response
     */
    protected Response $response;


    /**
     * Controller constructor.
     * @param Request $request
     * @param array $attributes
     */
    public function __construct(Request $request, array $attributes)
    {
        $this->request = $request;
        $this->attributes = $attributes;
        $this->response = new Response();
    }

}