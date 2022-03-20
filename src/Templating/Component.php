<?php

namespace Rxak\Framework\Templating;

use Rxak\Framework\Filesystem\Filesystem;

abstract class Component
{
    abstract public static function getFile(): string;

    public function buildPart(): string
    {
        $vars = get_object_vars($this);

        foreach ($vars as $key => $value) {
            $$key = $value;
        }

        ob_start();

        require Filesystem::getInstance()->baseDir . '/templating/' . static::getFile() . '.php';

        $page = ob_get_contents();

        ob_end_clean();

        return $page;
    }

    public function build(): string
    {
        return $this->buildPart();
    }

    public function __toString(): string
    {
        return $this->build();
    }
}