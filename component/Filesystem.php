<?php

namespace Component;

/**
 * Interface Filesystem
 * @package Component
 */
interface Filesystem
{
    /**
     * @param array $data
     * @return string
     */
    public function createFromUpload(array $data): string;

    /**
     * @param string $source
     * @param string $destination
     * @return bool
     */
    public function copy(string $source, string $destination): bool;

    /**
     * @param string $file
     * @return bool
     */
    public function delete(string $file): bool;
}
