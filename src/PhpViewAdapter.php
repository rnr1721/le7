<?php

declare(strict_types=1);

namespace Core\View\Php;

use Core\Interfaces\WebPage;
use Core\Interfaces\View;
use Core\Interfaces\ViewAdapter;
use Core\Interfaces\ViewTopology;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\Log\LoggerInterface;

class PhpViewAdapter implements ViewAdapter
{

    private CacheInterface $cache;
    private ServerRequestInterface $request;
    private ResponseInterface $response;
    private WebPage $webPage;
    private ViewTopology $viewTopology;
    private LoggerInterface $logger;

    public function __construct(
            ViewTopology $viewTopology,
            WebPage $webPage,
            ServerRequestInterface $request,
            ResponseInterface $response,
            CacheInterface $cache,
            LoggerInterface $logger
    )
    {
        $this->viewTopology = $viewTopology;
        $this->webPage = $webPage;
        $this->request = $request;
        $this->response = $response;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    public function getView(): View
    {
        $template = new Template($this->logger);
        $template->setPath($this->viewTopology->getTemplatePath());
        $phpView = new PhpRenderEngine($template, $this->viewTopology);
        return new PhpView($phpView, $this->webPage, $this->request, $this->response, $this->cache);
    }

}
