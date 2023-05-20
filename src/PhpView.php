<?php

declare(strict_types=1);

namespace Core\View\Php;

use Core\Interfaces\WebPageInterface;
use Core\Interfaces\ViewInterface;
use Core\View\ViewTrait;
use Core\View\ViewException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use \Throwable;

class PhpView implements ViewInterface
{

    use ViewTrait;

    /**
     * @var PhpRenderEngine
     */
    private PhpRenderEngine $view;

    /**
     * @var WebPageInterface
     */
    private WebPageInterface $webPage;

    public function __construct(
            PhpRenderEngine $view,
            WebPageInterface $webPage,
            ServerRequestInterface $request,
            ResponseInterface $response,
            CacheInterface $cache,
            EventDispatcherInterface $eventDispatcher
    )
    {
        $this->view = $view;
        $this->webPage = $webPage;
        $this->request = $request;
        $this->response = $response;
        $this->cache = $cache;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function fetch(string $layout, array $vars = []): string
    {

        try {
            $this->assign($this->webPage->getWebpage());
            $this->assign($vars);

            $this->view->vars = $this->vars;

            $this->clear();

            return $this->view->include($layout);
        } catch (Throwable $ex) {
            throw new ViewException($ex->getMessage());
        }
    }

}
