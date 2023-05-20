<?php

declare(strict_types=1);

namespace Core\View\Php;

use Core\Interfaces\WebPageInterface;
use Core\Interfaces\ViewInterface;
use Core\Interfaces\ViewAdapterInterface;
use Core\Interfaces\ViewTopologyInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\SimpleCache\CacheInterface;

class PhpViewAdapter implements ViewAdapterInterface
{

    private CacheInterface $cache;
    private ServerRequestInterface $request;
    private ResponseFactoryInterface $responseFactory;
    private WebPageInterface $webPage;
    private ViewTopologyInterface $viewTopology;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
            ViewTopologyInterface $viewTopology,
            WebPageInterface $webPage,
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

    public function getView(array|string|null $templatePath = null, ?ResponseInterface $response = null): ViewInterface
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
