<?php

require dirname(realpath(__FILE__)) . '/component/Loader.php';

use Component\Loader;

class ProjectLoader implements Loader
{
    private string $projectDir;

    public function __construct()
    {
        $this->projectDir = dirname(realpath(__FILE__));
    }

    public function autoload($class)
    {
        $class = str_replace(
            ['\\', 'Component', 'Project'],
            ['/', 'component', '/src'],
            $class
        ) . '.php';
        $this->loadFile($class);
    }

    public function loadFile(string $file)
    {
        $fileWithAbsolutePath = sprintf('%s/%s', $this->projectDir, $file);
        if (!file_exists($fileWithAbsolutePath)) {
            throw new \RuntimeException(sprintf('file %s not found.', $file));
        }

        return include $fileWithAbsolutePath;
    }

    public function getProjectDirectory(): string
    {
        return $this->projectDir;
    }
}

$loader = new ProjectLoader();
spl_autoload_register([$loader, 'autoload']);
