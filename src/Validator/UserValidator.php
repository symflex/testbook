<?php
declare(strict_types=1);

namespace Project\Validator;

use Component\Validator;
use Project\Repository\User;
use const FILTER_VALIDATE_EMAIL;

class UserValidator extends Validator
{
    /**
     * @var User
     */
    private User $userRepository;

    public function __construct(User $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validate(string $email, string $password): void
    {
        if (!$this->isEmail($email)) {
            $this->errors['email'] = 'Не корректная почта.';
        }

        if ($this->isExistsUser($email)) {
            $this->errors['email'] = 'Данная почта не доступна для регистрации';
        }

        if (!$this->isValidPassword($password)) {
            $this->errors['password'] = 'Пароль содержит недопустимые символы.';
        }
    }

    /**
     * @param string $email
     * @return bool
     */
    public function isExistsUser(string  $email)
    {
        return $this->userRepository->findByEmail($email) ? true : false;
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
