<?php

namespace WeCanTrack\Helper;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\{ClientException,
    ConnectException,
    RequestException,
    ServerException,
    TooManyRedirectsException,
    TransferException};
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Message;
use Promise;
use ResponseInterface;
use UriInterface;

/**
 * Class Curl
 * @package Libs
 *
 * @author: vzangloo <zang@saleduck.com>
 * @link: https://www.saleduck.com
 * @since 1.0.0
 * @copyright 2020 Saleduck Asia Sdn Bhd
 *
 * @method ResponseInterface get(string|UriInterface $uri, array $options = [])
 * @method ResponseInterface head(string|UriInterface $uri, array $options = [])
 * @method ResponseInterface put(string|UriInterface $uri, array $options = [])
 * @method ResponseInterface post(string|UriInterface $uri, array $options = [])
 * @method ResponseInterface patch(string|UriInterface $uri, array $options = [])
 * @method ResponseInterface delete(string|UriInterface $uri, array $options = [])
 * @method Promise\PromiseInterface getAsync(string|UriInterface $uri, array $options = [])
 * @method Promise\PromiseInterface headAsync(string|UriInterface $uri, array $options = [])
 * @method Promise\PromiseInterface putAsync(string|UriInterface $uri, array $options = [])
 * @method Promise\PromiseInterface postAsync(string|UriInterface $uri, array $options = [])
 * @method Promise\PromiseInterface patchAsync(string|UriInterface $uri, array $options = [])
 * @method Promise\PromiseInterface deleteAsync(string|UriInterface $uri, array $options = [])
 */
class Curl
{
    /**
     * @var array Curl options
     */
    protected array $config = [
        'timeout' => 5, // 3 sec
        'http_errors' => false,
    ];

    /**
     * @var bool Decode response body
     */
    protected bool $decodeBody = true;

    /**
     * @var bool In debug mode
     */
    protected bool $debugMode = false;

    /**
     * @var array Error message
     */
    protected static array $error = [];

    /**
     * Curl constructor.
     *
     * @link https://docs.guzzlephp.org/en/stable/request-options.html
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config($config);
    }

    /**
     * Get curl request object
     *
     * @link https://docs.guzzlephp.org/en/stable/request-options.html
     * @param array $config
     * @return static
     */
    public static function request(array $config = []): self
    {
        return new static($config);
    }

    /**
     * Enable decode response body. default is true
     *
     * @param bool $enable
     * @return $this
     */
    public function decodeBody(bool $enable = true): self
    {
        $this->decodeBody = $enable;
        return $this;
    }

    /**
     * Enable debug mode
     *
     * @param bool $enabled Enable debug mode. Default: true
     * @return $this
     */
    public function debug(bool $enabled = true): self
    {
        if ($enabled) {
            $this->config['debug'] = $this->debugMode = $enabled;
        }
        return $this;
    }

    /**
     * Set request options
     *
     * @link https://docs.guzzlephp.org/en/stable/request-options.html
     * @param array $config
     * @return $this
     */
    public function config(array $config = []): self
    {
        if ($config) {
            $this->config = array_merge($config, $this->config);
        }
        return $this;
    }

    /**
     * Get query string values
     *
     * @param array $data
     * @return $this
     */
    public function query(array $data = []): self
    {
        $this->config['query'] = $data;
        return $this;
    }

    /**
     * Enable SSL verification
     *
     * @param bool $enable Default: true
     * @return $this
     */
    public function verifySSL(bool $enable = true): self
    {
        $this->config['verify'] = $enable;
        return $this;
    }

    /**
     * Submit form data
     *
     * Form content is expecting an array.
     * Example:
     * [
     *   'field1' => 'value 1',
     *   'field2' => 'value 2',
     * ]
     *
     * @param array $params Form array content.
     * @return $this
     */
    public function form(array $params = []): self
    {
        if ($params && is_array($params)) {
            $this->config['form_params'] = $params;
        }
        return $this;
    }

    /**
     * Download remote file to path/filename
     *
     * @param string $filePath Filepath name. format: /path/to/filename.extension
     * @return $this
     */
    public function downloadTo(string $filePath = null): self
    {
        if ($filePath && is_string($filePath)) {
            $this->config['sink'] = $filePath;
        }
        return $this;
    }

    /**
     * Submit JSON content
     *
     * @param array $content
     * @return $this
     */
    public function json(array $content = []): self
    {
        if ($content && is_array($content)) {
            $this->config['json'] = $content;
        }
        return $this;
    }

    /**
     * @inheritDoc
     *
     * @return bool|mixed
     */
    public function __call($method, $arguments)
    {
        self::$error = []; // reset error
        try {
            $response = call_user_func_array([(new Client($this->config)), $method], $arguments);
            if ($response instanceof PromiseInterface) {
                return $response; // return promise
            }

            if ($response->getStatusCode() != 200) {
                throw new Exception("{$response->getStatusCode()} {$response->getReasonPhrase()}");
            }

            return $this->decodeBody
                ? json_decode((string)$response->getBody(), true)
                : $response->getBody();

        } catch (RequestException $e) {
            $this->error('request', $e);
        } catch (ConnectException $e) {
            $this->error('connect', $e);
        } catch (TransferException $e) {
            $this->error('transfer', $e);
        } catch (ServerException $e) {
            $this->error('server', $e);
        } catch (TooManyRedirectsException $e) {
            $this->error('too_many_redirect', $e);
        } catch (ClientException $e) {
            $this->error('client', $e);
        } catch (Exception $e) {
            self::$error = [
                'error' => 'Exception',
                'request' => null,
                'response' => $e->getMessage(),
                'params' => $this->debugMode ? $this->config : [],
            ];
        }
        return false;
    }

    /**
     * Set error message
     *
     * @param string $exception Exception type
     * @param Exception $e Error object
     */
    protected function error(string $exception, $e)
    {
        self::$error = [
            'error' => $exception,
            'request' => Message::toString($e->getRequest()),
            'response' => method_exists($e, 'hasResponse') && $e->hasResponse() ? Message::toString($e->getResponse()) : null,
            'params' => $this->debugMode ? $this->config : [],
        ];
    }

    /**
     * Get error message in json format
     *
     * @param bool $returnArray Whether return error message in array. Default: false
     * @return array|false|string
     */
    public static function getError(bool $returnArray = false)
    {
        return $returnArray ? self::$error : json_encode(self::$error);
    }
}