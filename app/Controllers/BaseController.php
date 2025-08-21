<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Config\Services;
use Config\App;

class BaseController extends Controller
{
    protected $helpers = [];
    protected $session;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->session = Services::session();

        $language = Services::language();

        $appConfig = new App();

        $locale = $this->session->get('lang') ?? $appConfig->defaultLocale;

        $language->setLocale($locale);
    }
}
