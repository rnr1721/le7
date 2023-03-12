<?php

declare(strict_types=1);

namespace App\View\Php;

use App\View\Interfaces\WebPage;
use App\View\Interfaces\View;
use App\View\Interfaces\ViewAdapter;
use App\View\Interfaces\ViewTopology;

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
        $phpView = new PhpRenderEngine($template, $this->viewTopology, $this->webPage);
        return new PhpView($phpView, $this->webPage);
    }

}
