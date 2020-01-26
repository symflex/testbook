<?php

namespace Component;

interface Loader
{
    public function autoload($class);

    public function loadFile(string $file);

    public function getProjectDirectory(): string;
}
