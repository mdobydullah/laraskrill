<?php

namespace Obydul\LaraSkrill;

class SkrillRequest
{
    private $vars;

    public function __set($name, $value)
    {
        $this->vars[$name] = $value;
    }

    public function __get($name)
    {
        return $this->vars[$name];
    }

    public function toArray()
    {
        return $this->vars;
    }
}
