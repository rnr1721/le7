<?php

declare(strict_types=1);

namespace Core\View\Php;

use Core\Interfaces\WebPage;
use Core\Interfaces\View;
use Core\Interfaces\ViewAdapter;
use Core\Interfaces\ViewTopology;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\Log\LoggerInterface;

class PhpViewAdapter implements ViewAdapter
{

    private CacheInterface $cache;
    private ServerRequestInterface $request;
    private ResponseFactoryInterface $responseFactory;
    private WebPage $webPage;
    private ViewTopology $viewTopology;
    private LoggerInterface $logger;

    public function __construct(
            ViewTopology $viewTopology,
            WebPage $webPage,
            ServerRequestInterface $request,
            ResponseFactoryInterface $responseFactory,
            CacheInterface $cache,
            LoggerInterface $logger
    )
    {
        $this->viewTopology = $viewTopology;
        $this->webPage = $webPage;
        $this->request = $request;
        $this->responseFactory = $responseFactory;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    public function getView(array|string|null $templatePath = null, ?ResponseInterface $response = null): View
    {

        if ($response === null) {
            $response = $this->responseFactory->createResponse(404);
        }

        if ($templatePath === null) {
            $templatePath = $this->viewTopology->getTemplatePath();
        }

        $template = new Template($this->logger);
        $template->setPath($templatePath);
        $phpView = new PhpRenderEngine($template, $this->viewTopology);

        return new PhpView($phpView, $this->webPage, $this->request, $response, $this->cache);
    }

}
