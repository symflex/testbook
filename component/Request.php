<?php

namespace Component;

use function array_key_exists;

class Request
{
    public const SECTION_QUERY   = 'query';
    public const SECTION_REQUEST = 'request';
    public const SECTION_COOKIE  = 'cookie';
    public const SECTION_FILES   = 'files';
    public const SECTION_SERVER  = 'server';

    public const REQUEST_METHOD        = 'REQUEST_METHOD';
    public const HTTP_X_REQUESTED_WITH = 'HTTP_X_REQUESTED_WITH';
    public const XML_HTTP_REQUEST      = 'XMLHttpRequest';


    /**
     * @var array
     */
    private $query;

    /**
     * @var array
     */
    private $request;

    /**
     * @var array
     */
    private $cookie;

    /**
     * @var array
     */
    private $files;

    /**
     * @var array
     */
    private $server;

    /**
     * Request constructor.
     * @param array $query
     * @param array $request
     * @param array $cookie
     * @param array $files
     * @param array $server
     */
    public function __construct(
        array $query,
        array $request,
        array $cookie,
        array $files,
        array $server
    ) {
        $this->query = $query;
        $this->request = $request;
        $this->cookie = $cookie;
        $this->files = $files;
        $this->server = $server;
    }

    /**
     * @param null $key
     * @return mixed
     */
    public function get($key = null)
    {
        return $this->from(self::SECTION_QUERY, $key);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function post($key = null)
    {
        return $this->from(self::SECTION_REQUEST, $key);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function cookie($key)
    {
        return $this->from(self::SECTION_COOKIE, $key);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function files($key)
    {
        return $this->from(self::SECTION_FILES, $key);
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function server(string $key)
    {
        return $this->from(self::SECTION_SERVER, $key);
    }

    /**
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->from(self::SECTION_SERVER, self::REQUEST_METHOD) === 'POST' ?? false;
    }

    /**
     * @return bool
     */
    public function isXmlHttpRequest(): bool
    {
        return $this
                ->from(self::SECTION_SERVER, self::HTTP_X_REQUESTED_WITH) === self::XML_HTTP_REQUEST
                ?? false;
    }

    /**
     * @param string $section
     * @param null $key
     * @return mixed|null
     */
    private function from(string $section, $key = null)
    {
        $value = null;

        switch ($section) {
            case self::SECTION_QUERY:
                $value = array_key_exists($key, $this->query) ? $this->query[$key] : null;
                break;
            case self::SECTION_REQUEST:
                $value = array_key_exists($key, $this->request) ? $this->request[$key] : null;
                break;
            case self::SECTION_COOKIE:
                $value = array_key_exists($key, $this->cookie) ? $this->cookie[$key] : null;
                break;
            case self::SECTION_FILES:
                $value = array_key_exists($key, $this->files) ? $this->files[$key] : null;
                break;
            case self::SECTION_SERVER:
                $key = strtoupper($key);
                $value = array_key_exists($key, $this->server) ? $this->server[$key] : null;
        }

        return $value;
    }
}
