<?php
declare(strict_types=1);

namespace Component;

class Session
{
    protected const USER_KEY = 'user_id';

    /**
     * @var Session|null
     */
    public static Session $instance;

    /**
     * Session constructor.
     */
    private function __construct()
    {
        if (PHP_SESSION_NONE === session_status()) {
            session_start();
        }
    }

    /**
     * @return Session
     */
    public static function instance(): Session
    {
        return static::$instance ?? static::$instance = new static();
    }

    /**
     * @param $key
     * @return bool|mixed
     */
    public function get($key)
    {
        return $this->fetch($key);
    }

    /**
     * @param string $key
     * @param $value
     */
    public function add(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param $key
     */
    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * @return bool|mixed
     */
    public function userId()
    {
        return $this->fetch(self::USER_KEY);
    }

    /**
     * @param string $key
     * @return bool|mixed
     */
    private function fetch(string $key)
    {
        return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : false;
    }

    public function destroy()
    {
        $_SESSION = [];
        session_destroy();
    }
}
