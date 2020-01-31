<?php

namespace Project\Repository;

use Component\Database;
use Component\Filesystem;
use NumberFormatter;

/**
 * Class Book
 * @package Project\Repository
 */
class Book
{
    private const TABLE = 'book_item';

    /**
     * @var Database
     */
    private Database $db;

    /**
     * Book constructor.
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * @param int $userId
     * @return array|null
     */
    public function getItems(int $userId): ?array
    {
        return $this->db->select(
            sprintf(
                '* FROM %s WHERE user_id = :user_id',
                self::TABLE
            ),
            [
                'user_id' => $userId
            ]
        );
    }

    /**
     * @param int $id
     * @param int $userId
     * @return object|null
     */
    public function find(int $id, int $userId): ?object
    {
        return $this->db->find(
            sprintf('SELECT name, surname, phone, email, image FROM %s WHERE id = :id AND user_id = :user_id', self::TABLE),
            [
                'id' => $id,
                'user_id' => $userId
            ]
        );
    }

    /**
     * @param int $id
     * @param int $userId
     * @param Filesystem $filesystem
     * @return int
     */
    public function deleteItem(int $id, int $userId, Filesystem $filesystem): int
    {
        $item = $this->db->find(
            sprintf('id, image FROM %s WHERE id = :id AND user_id = :user_id', self::TABLE),
            [
                        'id' => $id,
                        'user_id' => $userId
                    ]
        );
        $filesystem->delete($item->image);
        return $this->db->delete(self::TABLE, ['id' => $id]);
    }

    /**
     * @param array $data
     * @return array
     */
    public function createItem(array $data): array
    {
        $id = $this->db->insert(self::TABLE, $data);
        $data['id'] = $id;
        return $data;
    }

    /**
     * @param int $id
     * @param array $data
     * @return int
     */
    public function update(int $id, array $data): int
    {
        return $this->db->update(self::TABLE, $data, ['id' => $id]);
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function findByUserId(int $id): ?array
    {
        $numberFormatter = new NumberFormatter("ru", NumberFormatter::SPELLOUT);

        $items = $this->db->select('* FROM '.self::TABLE.' WHERE user_id = :id', ['id' => $id]);

        foreach ($items as $i => $item) {
            if ($i % 2 ===0) {
                $items[$i]->phoneFormat = $numberFormatter->format($items[$i]->phone);
            }


        }

        return $items;
    }
}
