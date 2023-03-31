<?php

declare(strict_types=1);

namespace Core\View\Php;

use Psr\Log\LoggerInterface;
use \Exception;
use function file_exists,
             is_string,
             in_array;

class Template
{

    private array $path = [];
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function exists(string $template): string
    {
        foreach ($this->path as $dir) {
            $current = $dir . DIRECTORY_SEPARATOR . $template;
            if (file_exists($current)) {
                return $current;
            }
        }

        $this->logger->debug("Template not found:" . $template);
        throw new Exception("Template not found:" . $template);
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
