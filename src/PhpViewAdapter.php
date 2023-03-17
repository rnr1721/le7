<?php

declare(strict_types=1);

namespace Core\View\Php;

use Core\View\Interfaces\WebPage;
use Core\View\Interfaces\View;
use Core\View\Interfaces\ViewAdapter;
use Core\View\Interfaces\ViewTopology;

class PhpViewAdapter implements ViewAdapter
{

    private WebPage $webPage;
    private ViewTopology $viewTopology;

    public function __construct(ViewTopology $viewTopology, WebPage $webPage)
    {
        $this->viewTopology = $viewTopology;
        $this->webPage = $webPage;
    }

    public function getView(): View
    {
        $template = new Template();
        $template->setPath($this->viewTopology->getTemplatePath());
        $template->setPath($this->viewTopology->getTemplateSystemPath());
        $phpView = new PhpRenderEngine($template, $this->viewTopology);
        return new PhpView($phpView, $this->webPage);
    }

}
