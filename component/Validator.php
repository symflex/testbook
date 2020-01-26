<?php

namespace Component;

/**
 * Class Validator
 * @package Component
 */
abstract class Validator
{
    /**
     * @var array
     */
    protected array $errors = [];

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return empty($this->errors) ? true : false;
    }


    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
