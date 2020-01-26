<?php
declare(strict_types=1);

namespace Project\Validator;

use Component\Validator;
use const FILTER_VALIDATE_EMAIL;

class UserValidator extends Validator
{
    public function validate(string $email, string $password): void
    {
        if (!$this->isEmail($email)) {
            $this->errors['email'] = 'Не корректная почта.';
        }

        if (!$this->isValidPassword($password)) {
            $this->errors['password'] = 'Пароль содержит недопустимые символы.';
        }
    }

    /**
     * @param string $email
     * @return bool
     */
    public function isEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? true : false;
    }

    /**
     * @param string $password
     * @return bool
     */
    public function isValidPassword(string $password): bool
    {
        return (bool)preg_match('#[A-Za-z0-9]+#', $password);
    }
}
