<?php

namespace anxu\HttpClient;

use RuntimeException;

class Client
{

    protected $headers;

    public function __construct($headers = array())
    {
        $this->headers = $headers;
    }


    /**
     * Send a GET request
     *
     * @param string $url The request url
     * @param array $parameters The request parameters
     * @param array $headers The request HTTP headers
     * @return ClientResponse The response object
     */
    public function get($url, array $parameters = array(), $headers = array())
    {
        return $this->call(ClientRequest::METHOD_GET, $url, $parameters, null, $headers);
    }

    /**
     * Send a POST request
     *
     * @param string $url The request URL
     * @param array $parameters The request parameters
     * @param string $content The raw content
     * @param array $headers The request HTTP headers
     * @return ClientResponse The response object
     */
    public function post($url, array $parameters = array(), $content = null, $headers = array())
    {
        return $this->call(ClientRequest::METHOD_POST, $url, $parameters, $content, $headers);
    }

    /**
     * Send a HTTP request
     *
     * @param string $method The HTTP method request
     * @param string $url The request URL
     * @param array $parameters The request parameters
     * @param string $content The raw content
     * @param array $headers The request HTTP headers
     *
     * @return ClientResponse The response object
     */
    public function call($method, $url, array $parameters = array(), $content = null, array $headers = array())
    {
        $headers = $this->getHeader($headers);

        $request = new ClientRequest($url, $method, $parameters, $content, $headers);

        return $this->send($request);
    }


    /**
     * Gets the HTTP header.
     *
     * @param array $header the HTTP header
     * @return array
     */
    protected function getHeader(array $header)
    {
        return array_merge($this->headers, $header);
    }


    /**
     * Send the given HTTP request by using this adapter
     *
     * @param ClientRequest $request The request object
     *
     * @return ClientResponse The response object
     *
     */
    protected function send(ClientRequest $request)
    {
        $curl = $this->prepareRequest($request);

        $data = curl_exec($curl);
        if (false === $data) {
            $errorMsg = curl_error($curl);
            $errorNo = curl_errno($curl);
            throw new RuntimeException($errorMsg, $errorNo);
        }

        return $this->prepareResponse($data, curl_getinfo($curl, CURLINFO_HTTP_CODE));
    }

    /**
     * Prepare cURL with the given request
     *
     * @param ClientRequest $request
     * @return false|resource
     */
    protected function prepareRequest(ClientRequest $request)
    {
        $curl = curl_init();

        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_CONNECTTIMEOUT_MS => 1000,
            CURLOPT_TIMEOUT_MS => 3000,
            CURLOPT_CUSTOMREQUEST => $request->getMethod(),
            CURLOPT_URL => $request->getUri(),
            CURLOPT_HTTPHEADER => $request->getHeaders(),
            CURLOPT_HTTPGET => false,
            CURLOPT_NOBODY => false,
            CURLOPT_POST => false,
            CURLOPT_POSTFIELDS => null,
        );
        switch ($request->getMethod()) {
            case ClientRequest::METHOD_GET:
                $options[CURLOPT_HTTPGET] = true;
                $query = http_build_query($request->getParameters());
                if (!empty($query)) {
                    $options[CURLOPT_URL] = $request->getUri() . '?' . $query;
                }
                break;
            case ClientRequest::METHOD_POST:
                $options[CURLOPT_POST] = true;
                $options[CURLOPT_POSTFIELDS] = http_build_query($request->getParameters());
                break;
        }

        curl_setopt_array($curl, $options);

        return $curl;
    }


    /**
     * Generate the response object out of the raw response
     *
     * @param string $data Raw response
     * @param int $code HTTP status code
     *
     * @return ClientResponse The response object
     */
    protected function prepareResponse($data, $code)
    {
        $rawHeaders = array();
        $rawContent = null;

        $lines = preg_split('/(\\r?\\n)/', $data, -1, PREG_SPLIT_DELIM_CAPTURE);
        for ($i = 0, $count = count($lines); $i < $count; $i += 2) {
            $line = $lines[$i];
            if (!empty($line)) {
                $rawHeaders[] = $line;
            } else {
                $rawContent = implode('', array_slice($lines, $i + 2));
                break;
            }
        }

        $headers = $this->prepareHeaders($rawHeaders);
        return new ClientResponse($rawContent, $code, $headers);
    }

    /**
     * Returns the header as an associated array
     *
     * @param array $rawHeaders
     *
     * @return array
     */
    protected function prepareHeaders(array $rawHeaders)
    {
        $headers = array();

        foreach ($rawHeaders as $rawHeader) {
            if (strpos($rawHeader, ':')) {
                $data = explode(':', $rawHeader);
                $headers[$data[0]][] = trim($data[1]);
            }
        }

        return $headers;
    }
}