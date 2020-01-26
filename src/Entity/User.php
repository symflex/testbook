<?php

namespace Project\Entity;

class User
{
    private int $id = 0;

    private string $email = '';

    private string $password = '';

    /**
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function email(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function password(): string
    {
        return $this->password;
    }
}
