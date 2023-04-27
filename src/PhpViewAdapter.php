<?php

declare(strict_types=1);

namespace Core\View\Php;

use Core\Interfaces\WebPage;
use Core\Interfaces\View;
use Core\Interfaces\ViewAdapter;
use Core\Interfaces\ViewTopology;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\SimpleCache\CacheInterface;

class PhpViewAdapter implements ViewAdapter
{

    private CacheInterface $cache;
    private ServerRequestInterface $request;
    private ResponseFactoryInterface $responseFactory;
    private WebPage $webPage;
    private ViewTopology $viewTopology;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
            ViewTopology $viewTopology,
            WebPage $webPage,
            ServerRequestInterface $request,
            ResponseFactoryInterface $responseFactory,
            CacheInterface $cache,
            EventDispatcherInterface $eventDispatcher
    )
    {
        $this->viewTopology = $viewTopology;
        $this->webPage = $webPage;
        $this->request = $request;
        $this->responseFactory = $responseFactory;
        $this->cache = $cache;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getView(array|string|null $templatePath = null, ?ResponseInterface $response = null): View
    {

        if ($response === null) {
            $response = $this->responseFactory->createResponse(404);
        }

        if ($templatePath === null) {
            $templatePath = $this->viewTopology->getTemplatePath();
        }

        $template = new Template();
        $template->setPath($templatePath);
        $phpView = new PhpRenderEngine($template, $this->viewTopology);

        return new PhpView(
                $phpView,
                $this->webPage,
                $this->request,
                $response,
                $this->cache,
                $this->eventDispatcher
        );
    }

}
