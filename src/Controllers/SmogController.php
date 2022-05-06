<?php

namespace Src\Controllers;

use Symfony\Component\HttpFoundation\Response;

class SmogController extends Controller
{
    public function index(): Response
    {
        return $this->response->setContent('test is done')
            ->setStatusCode(200)
            ->send();
    }
}