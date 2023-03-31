<?php

declare(strict_types=1);

namespace Core\View\Php;

use Core\View\ViewTrait;
use Core\Interfaces\WebPage;
use Core\Interfaces\View;
use Psr\SimpleCache\CacheInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class PhpView implements View
{

    use ViewTrait;

    /**
     * @var PhpRenderEngine
     */
    private PhpRenderEngine $view;

    /**
     * @var WebPage
     */
    private WebPage $webPage;

    public function __construct(
            PhpRenderEngine $view,
            WebPage $webPage,
            ServerRequestInterface $request,
            ResponseInterface $response,
            CacheInterface $cache
    )
    {
        $this->view = $view;
        $this->webPage = $webPage;
        $this->request = $request;
        $this->response = $response;
        $this->cache = $cache;
    }

    public function fetch(string $layout, array $vars = []): string
    {

        $this->assign($this->webPage->getWebpage());
        $this->assign($vars);

        $this->view->vars = $this->vars;

        $this->clear();

        return $this->view->include($layout);
    }

}
