<?php

namespace Component;

use RuntimeException;

class Config
{
    /**
     * @var Loader
     */
    protected Loader $loader;

    /**
     * @var array
     */
    protected array $sections;

    public function __construct(Loader $loader)
    {
        $this->loader = $loader;
        $this->load();
    }

    protected function load()
    {
        $configDirectory = sprintf('%s/%s', $this->loader->getProjectDirectory(), 'config/*.*');

        $files = glob($configDirectory);

        foreach ($files as $file) {
            $file    = basename($file);
            $section = basename($file, '.php');

            $this->sections[$section] = $this->loader->loadFile('config/' . $file);
        }
    }

    /**
     * @param string $section
     * @param bool $onlyValues
     * @return array
     */
    public function get(string $section, bool $onlyValues = false)
    {
        if (!array_key_exists($section, $this->sections)) {
            throw new RuntimeException(sprintf('config section %s not found.', $section));
        }

        if ($onlyValues) {
            return array_values($this->sections[$section]);
        }

        return $this->sections[$section];
    }
}
