<?php

namespace Component;

use Component\Session;
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
    public const MESSAGE = 'message';

    /**
     * @var string
     */
    private string $templatePath;

    private Session $session;

    public function __construct(string $templatePath, Session $session)
    {
        $this->session = $session;
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

    /**
     * @return bool
     */
    public function isMessage(): bool
    {
        return !empty($this->session->get(self::MESSAGE)) ?? false;
    }

    /**
     * @return bool|mixed
     */
    public function getMessage()
    {
        $message = $this->session->get(self::MESSAGE);
        $this->session->remove(self::MESSAGE);
        return $message;
    }
}
