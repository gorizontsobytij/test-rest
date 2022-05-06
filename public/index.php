<?php

use Src\DotEnv;
use Symfony\Component\HttpFoundation\Request;

require_once '../vendor/autoload.php';
$config = require '../config.php';
$request = Request::createFromGlobals();

try {
    (new DotEnv($config['root_dir'] . '/.env'))->load();
} catch (Exception $exception) {
    echo $exception->getMessage();
    exit;
}
(new \Src\AppRouter($request, $config));


