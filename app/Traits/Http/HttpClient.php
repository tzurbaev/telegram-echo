<?php

namespace App\Traits\Http;

use GuzzleHttp\ClientInterface;

trait HttpClient
{
    /**
     * @var \GuzzleHttp\ClientInterface|null
     */
    protected $httpClient;

    /**
     * @var \GuzzleHttp\ClientInterface|null
     */
    protected static $fakeHttpClient;

    /**
     * Устанавливает фейковый HTTP-клиент.
     *
     * @param \GuzzleHttp\ClientInterface
     *
     * @return \GuzzleHttp\ClientInterface
     */
    public static function setFakeHttpClient(ClientInterface $client)
    {
        return static::$fakeHttpClient = $client;
    }

    /**
     * Удаляет сохраненный ранее фейковый HTTP-клиент.
     */
    public static function removeFakeHttpClient()
    {
        return static::$fakeHttpClient = null;
    }

    /**
     * Возвращает HTTP-клиент.
     *
     * @return \GuzzleHttp\ClientInterface
     */
    public function getHttpClient()
    {
        if (!is_null(static::$fakeHttpClient)) {
            return static::$fakeHttpClient;
        }

        if (!is_null($this->httpClient)) {
            return $this->httpClient;
        }

        return $this->httpClient = $this->createHttpClient();
    }

    /**
     * Создает новый HTTP-клиент.
     *
     * @return \GuzzleHttp\ClientInterface
     */
    abstract public function createHttpClient();

    /**
     * Устанавливает HTTP-клиент.
     *
     * @param \GuzzleHttp\ClientInterface $client
     *
     * @return self
     */
    public function setHttpClient(ClientInterface $client)
    {
        $this->httpClient = $client;

        return $this;
    }
}
