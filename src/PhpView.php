<?php

declare(strict_types=1);

namespace App\View\Php;

use App\View\Interfaces\WebPage;
use App\View\Interfaces\View;
use function array_merge;

class PhpView implements View
{

    private array $vars = array();

    /**
     * @var PhpRenderEngine
     */
    private PhpRenderEngine $view;

    /**
     * @var WebPage
     */
    private WebPage $webPage;

    public function __construct(PhpRenderEngine $view, WebPage $webPage)
    {
        $this->view = $view;
        $this->webPage = $webPage;
    }

    public function render(string $layout, array $vars = []): string
    {

        $webpage = $this->webPage->getWebpage();

        $finalVars = array_merge($vars, $webpage);

        $this->view->vars = $finalVars;

        return $this->view->include($layout);
    }

}
