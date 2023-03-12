<?php

declare(strict_types=1);

namespace App\View\Php;

use App\View\Interfaces\WebPage;
use App\View\Interfaces\ViewTopology;
use function extract;
use function ob_start;
use function ob_get_clean;
use function htmlspecialchars;

class PhpRenderEngine
{

    /**
     * @var WebPage
     */
    private WebPage $webPage;
    private ViewTopology $viewTopology;
    private Template $template;
    public array $vars = array();

    public function __construct(Template $template, ViewTopology $viewTopology, WebPage $webPage)
    {
        $this->template = $template;
        $this->viewTopology = $viewTopology;
        $this->webPage = $webPage;
    }

    /**
     * Include template
     * @param string $templateFile
     * @return string
     */
    public function include(string $templateFile): string
    {
        extract($this->vars, EXTR_REFS);
        $template = $this->template->exists($templateFile);
        ob_start();
        if (is_file($template)) {
            include $template;
        }
        return ob_get_clean();
    }

    /**
     * Remove HTML special characters
     * @param string $var
     * @return string
     */
    public function e(string $var): string
    {
        return htmlspecialchars($var);
    }

    /**
     * Return value or something default
     * @param mixed $value
     * @param mixed $default
     * @return mixed
     */
    public function ifexists(mixed $value, mixed $default): mixed
    {
        if ($value) {
            return $value;
        }
        return $default;
    }

}
