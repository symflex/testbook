<?php
declare(strict_types=1);

namespace Project\Repository;

use Component\Database;
use Project\Entity\User as UserModel;

/**
 * Class User
 * @package Project\Repository
 */
class User
{
    private const TABLE = 'user';

    /**
     * @var Database
     */
    private Database $db;

    /**
     * User constructor.
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * @param string $email
     * @param string $password
     * @return string
     */
    public function create(string $email, string $password): string
    {
        $password = password_hash($password, PASSWORD_DEFAULT);

        return
            $this->db->insert(self::TABLE, [
                'email' => $email,
                'password' => $password
            ]);
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?UserModel
    {
        return
            $this->db->find('SELECT id, email, password FROM '.self::TABLE.' WHERE email = :email', [
                'email' => $email
            ], $this->db::FETCH_CLASS, UserModel::class);
    }
}
