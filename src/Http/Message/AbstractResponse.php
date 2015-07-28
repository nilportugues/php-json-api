<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 7/28/15
 * Time: 1:13 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Api\Http\Message;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Class AbstractResponse.
 */
abstract class AbstractResponse implements ResponseInterface
{
    protected $httpCode = 0;

    /**
     * @var \Zend\Diactoros\Response
     */
    protected $response;

    /**
     * @var array
     */
    protected $headers = ['Content-type' => 'application/json; charset=utf-8'];

    /**
     * @param string $body
     */
    public function __construct($body)
    {
        $this->response = self::instance($body, $this->httpCode, $this->headers);
    }

    /**
     * @param string $body
     * @param int    $status
     * @param array  $headers
     *
     * @return AbstractResponse
     */
    protected function instance($body, $status = 200, array $headers = [])
    {
        $response = new \Zend\Diactoros\Response('php://memory', $status, $headers);
        $response->getBody()->write($body);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    /**
     * {@inheritdoc}
     */
    public function getReasonPhrase()
    {
        return $this->response->getReasonPhrase();
    }

    /**
     * {@inheritdoc}
     */
    public function getProtocolVersion()
    {
        return $this->response->getProtocolVersion();
    }

    /**
     * {@inheritdoc}
     */
    public function withProtocolVersion($version)
    {
        return $this->response->withProtocolVersion($version);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders()
    {
        return $this->response->getHeaders();
    }

    /**
     * {@inheritdoc}
     */
    public function hasHeader($header)
    {
        return $this->response->hasHeader($header);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader($header)
    {
        return $this->response->getHeader($header);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaderLine($name)
    {
        return $this->response->getHeaderLine($name);
    }

    /**
     * {@inheritdoc}
     */
    public function withHeader($header, $value)
    {
        return $this->response->withHeader($header, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function withAddedHeader($header, $value)
    {
        return $this->response->withAddedHeader($header, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function withoutHeader($header)
    {
        return $this->response->withoutHeader($header);
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->response->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function withBody(StreamInterface $body)
    {
        return $this->response->withBody($body);
    }

    /**
     * {@inheritdoc}
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        return $this->response->withStatus($code, $reasonPhrase);
    }
}
