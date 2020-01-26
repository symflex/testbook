<?php
declare(strict_types=1);

namespace Project\Validator;

use Component\Validator;

/**
 * Class ItemValidator
 * @package Project\Validator
 */
class ItemValidator extends Validator
{
    public const FIELD_NAME = 'Items[name]';
    public const FIELD_SURNAME = 'Item[surname]';
    public const FIELD_PHONE = 'Item[phone]';
    public const FIELD_EMAIL = 'Item[email]';

    public const FIELD_FILE = 'image';

    public const TYPES = [
        'image/jpeg',
        'image/png'
    ];

    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @var array
     */
    protected array $file = [];

    /**
     * ItemValidator constructor.
     * @param array $data
     * @param array $file
     */
    public function __construct(array $data, array $file)
    {
        $this->validate($data, $file);
    }

    /**
     * @param array $data
     * @param array $file
     */
    protected function validate(array $data, array $file): void
    {
        foreach ($data as $field => $value) {
            switch ($field) {
                case self::FIELD_EMAIL:
                    $this->validateEmail($value);
                    break;
                case self::FIELD_PHONE:
                    $this->validatePhone($value);
                    break;
                case self::FIELD_NAME:
                case self::FIELD_SURNAME:
                    $this->validateName($value);
                    break;
            }
        }

        $this->validateFile($file);
    }

    /**
     * @param array $file
     */
    public function validateFile(array $file = []): void
    {
        if (empty($file)) {
            return;
        }

        if ($file['size'] > 2097152) {
            $this->errors[self::FIELD_FILE] = 'Не допустимый размер файла';
        } elseif (!in_array($file['type'], self::TYPES)) {
            $this->errors[self::FIELD_FILE] = 'Недопустимый формат изображения';
        }
    }

    /**
     * @param string $phone
     */
    public function validatePhone(string $phone): void
    {
        if (!is_numeric($phone)) {
            $this->errors[self::FIELD_PHONE] = 'Не корректный телефон.';
        }
    }

    /**
     * @param string $email
     */
    public function validateEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[self::FIELD_EMAIL] = 'Не корректная почта.';
        }
    }

    /**
     * @param string $name
     */
    public function validateName(string $name): void
    {
        if (strlen($name) < 2) {
            $this->errors[self::FIELD_NAME] = 'Имя не может быть меньше 2х символов.';
        }

        if (!preg_match('#^([a-zA-Zа-яА-я]+)$#u', $name)) {
            $this->errors[self::FIELD_NAME] = 'Имя содержит не допустимые символы.';
        }
    }
}
