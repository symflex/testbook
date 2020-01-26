<?php

namespace Component;

class Image implements Filesystem
{
    public const TYPES = [
        'image/jpeg' => 'jpeg',
        'image/png' => 'png',
    ];

    protected string $imageDirectory;

    public function __construct(string $imageDirectory)
    {
        $this->imageDirectory = $imageDirectory;
    }

    public function createFromUpload(array $file): string
    {
        $type = $file['type'];

        if (array_key_exists($type, self::TYPES)) {
            $filename = sprintf('%s.%s', sha1(random_bytes(15)), self::TYPES[$type]);
        }

        $destinationFilePath = sprintf('%s/%s', $this->imageDirectory, $filename);

        if (!$this->copy($file['tmp_name'], $destinationFilePath)) {
            throw new \RuntimeException('Не удалось загрузить изображение');
        }

        return $filename;
    }

    public function copy(string $source, string $destination): bool
    {
        return copy($source, $destination);
    }

    public function delete(string $file): bool
    {
        $filePath = sprintf('%s/%s', $this->imageDirectory, $file);
        if (file_exists(sprintf('%s/%s', $this->imageDirectory, $file))) {
            return unlink($filePath);
        }
        return true;
    }
}
