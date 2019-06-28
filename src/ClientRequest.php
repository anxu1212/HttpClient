<?php

namespace anxu\HttpClient;

class ClientRequest
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';

    /**
     * @var string
     */
    protected $uri;
    /**
     * @var string
     */
    protected $method;
    /**
     * @var array
     */
    protected $parameters;
    /**
     * @var string|null
     */
    protected $content;

    /**
     * Request HTTP headers
     *
     * @var array
     */
    private $headers = array();

    /**
     * Constructor.
     *
     * @param string $uri The request URI
     * @param string $method The HTTP method request
     * @param array $parameters The request parameters
     * @param string $content The raw body data
     * @param array $headers The request HTTP headers
     *
     */
    public function __construct(
        $uri,
        $method,
        array $parameters = array(),
        $content = null,
        array $headers = array()
    ) {
        $this->uri = $uri;
        $this->method = $method;
        $this->parameters = $parameters;
        $this->content = $content;
        $this->headers = $headers;
    }

    /**
     * Gets the request URI.
     *
     * @return string The request URI
     *
     * @api
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Gets the request HTTP method.
     *
     * @return string The request HTTP method
     *
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Gets the request parameters.
     *
     * @return array The request parameters
     *
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Set many GET or POST parameters
     *
     * @param array $parameters Array of parameters (Format: array('key' => 'value',...))
     * @param bool $append Append the paramters
     *
     * @return void
     */
    public function setParameters(array $parameters, $append = false)
    {
        if (!$append) {
            $this->parameters = $parameters;
        } else {
            foreach ($parameters as $field => $value) {
                $this->setParameter($field, $value);
            }
        }
    }

    /**
     * Set a GET or POST parameter
     *
     * @param string $field Field name
     * @param mixed $value Value of the field
     *
     * @return void
     */
    public function setParameter($field, $value)
    {
        $this->parameters[$field] = $value;
    }

    /**
     * Gets one get/post parameter
     *
     * @param string $key Field name
     *
     * @return mixed
     */
    public function getParameter($key)
    {
        return isset($this->parameters[$key]) ? $this->parameters[$key] : null;
    }

    /**
     * Gets the request raw body data.
     *
     * @return string The request raw body data.
     *
     * @api
     */
    public function getContent()
    {
        return $this->content;
    }


    /**
     * Return the raw HTTP headers
     *
     * @return array
     */
    public function getRawHeaders()
    {
        return $this->headers;
    }

    /**
     * Return the HTTP header
     *
     * @return array
     */
    public function getHeaders()
    {
        $headerFields = array_keys($this->headers);

        $result = array();
        foreach ($headerFields as $field) {
            $result[] = sprintf('%s: %s', $field, $this->getHeader($field));
        }

        return $result;
    }

    /**
     * Set or reset the headers
     *
     * @param array $headers Array of headers
     * @param bool $append (Optional) Reset the existing headers
     *
     * @return void
     */
    public function setHeaders(array $headers, $append = false)
    {
        if (!$append) {
            $this->headers = $headers;
        } else {
            foreach ($headers as $field => $value) {
                $this->setHeader($field, $value);
            }
        }
    }

    /**
     * Return one field from header
     *
     * @param string $field Header field name
     *
     * @return null|string
     */
    public function getHeader($field)
    {
        if (!isset($this->headers[$field])) {
            return null;
        }

        return is_array($this->headers[$field]) ? implode(';', $this->headers[$field]) : $this->headers[$field];
    }

    /**
     * Set one field in HTTP header
     *
     * @param string $field
     * @param mixed $value
     */
    public function setHeader($field, $value)
    {
        if (is_array($this->headers[$field])) {
            $this->headers[$field] = array_merge($this->headers[$field], $value);
        } else {
            $this->headers[$field] = $value;
        }
    }

}