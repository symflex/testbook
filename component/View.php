<?php

namespace Component;

use Exception;
use RuntimeException;
use function extract;
use function ob_start;
use function ob_get_contents;
use function ob_end_clean;
use const EXTR_SKIP;

/**
 * Class View
 * @package Symflex
 */
class View
{
    /**
     * @var string
     */
    private string $templatePath;

    public function __construct(string $templatePath)
    {
        $this->templatePath = $templatePath;
    }

    /**
     * @param string $templateFile
     * @return string
     * @throws Exception
     */
    public function findTemplate(string $templateFile): string
    {
        $filePath = sprintf('%s/%s', $this->templatePath, $templateFile);
        if (!file_exists($filePath)) {
            throw new RuntimeException(sprintf('template file %s not found.', $templateFile));
        }

        return $filePath;
    }

    public function render(string $template, array $data = [])
    {
        $template = $this->findTemplate($template);
        extract($data, EXTR_SKIP);
        ob_start();
        include $template;
        return (string) ob_get_clean();
    }
}
