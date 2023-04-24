<?php

declare(strict_types=1);

namespace Core\View\Php;

use Core\View\ViewException;

use function file_exists,
             is_string,
             in_array;

class Template
{

    private array $path = [];
    
    public function exists(string $template): string
    {
        foreach ($this->path as $dir) {
            $current = $dir . DIRECTORY_SEPARATOR . $template;
            if (file_exists($current)) {
                return $current;
            }
        }

        throw new ViewException("Template not found:" . $template);
    }

    public function setPath(string|array $path): self
    {
        if (is_string($path)) {
            $this->addPathItem($path);
        } else {
            foreach ($path as $item) {
                $this->addPathItem($item);
            }
        }
        return $this;
    }

    private function addPathItem(string $path): void
    {
        if (!in_array($path, $this->path)) {
            $this->path[] = $path;
        }
    }

}
