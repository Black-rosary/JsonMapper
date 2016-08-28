<?php

namespace Yaroslav\JsonMapperBundle\HttpWrapper;

use Yaroslav\JsonMapperBundle\HttpWrapper\Exception\CurlWrapperException;
use Yaroslav\JsonMapperBundle\HttpWrapper\Exception\CurlException;


/**
 * The class implements wrapper of the cURL extension
 * 
 */
class CurlWrapper {

    /**
     * @var cURL handle
     */
    protected $ch = null;

    /**
     * @var array headers
     */
    protected $headers = array();

    /**
     * @var array cURL options
     */
    protected $options = array();

    /**
     * @var array GET/POST params to send
     */
    protected $requestParams = array();

    /**
     * @var string cURL response data
     */
    protected $response = '';

    /**
     * Initiates the cURL handle
     *
     * @throws CurlWrapperCurlException
     */
    public function __construct()
    {
        if (!extension_loaded('curl')) {
            throw new CurlWrapperException('cURL extension is not loaded.');
        }
        $this->ch = curl_init();
        if (!$this->ch) {
            throw new CurlWrapperCurlException($this->ch);
        }
        $this->setDefaults();
    }

    /**
     * Closes cURL handle
     */
    public function __destruct()
    {
        if (is_resource($this->ch)) {
            curl_close($this->ch);
        }
        $this->ch = null;
    }

    /**
     * Add header
     *
     * @param string|array $header
     * @param string $value
     */
    public function addHeader($header, $value = null)
    {
        $this->headers[$header] = $value;
    }

    /**
     * Adds an option for a cURL
     *
     * @param integer|array $option
     * @param mixed $value Value of option (CURLOPT constant)
     */
    public function addOption($option, $value = null)
    {
        $this->options[$option] = $value;
    }

    /**
     * Adds a request parameter for a cURL
     *
     * @param string|array $name
     * @param string $value
     */
    public function addRequestParam($name, $value = null)
    {
        $this->requestParams[$name] = $value;
    }

    /**
     * Clears the headers
     */
    public function clearHeaders()
    {
        $this->headers = array();
    }

    /**
     * Clears the options
     */
    public function clearOptions()
    {
        $this->options = array();
    }

    /**
     * Clears the request parameters
     */
    public function clearRequestParams()
    {
        $this->requestParams = array();
    }

    /**
     * Makes the 'GET' request to the $url with an optional $requestParams
     *
     * @param string $url
     * @param array $requestParams
     * @return string
     */
    public function get($url, $requestParams = null)
    {
        return $this->request($url, 'GET', $requestParams);
    }

    /**
     * Returns the last transfer's response data
     *
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Makes the 'POST' request to the $url with an optional $requestParams
     *
     * @param string $url
     * @param array $requestParams
     * @return string
     */
    public function post($url, $requestParams = null)
    {
        return $this->request($url, 'POST', $requestParams);
    }

    /**
     * Removes the header for next cURL transfer
     *
     * @param string $header
     */
    public function removeHeader($header)
    {
        if (isset($this->headers[$header])) {
            unset($this->headers[$header]);
        }
    }

    /**
     * Removes the option for next cURL transfer
     *
     * @param integer $option CURLOPT_XXX predefined constant
     */
    public function removeOption($option)
    {
        if (isset($this->options[$option])) {
            unset($this->options[$option]);
        }
    }

    /**
     * Removes the request parameter for next cURL transfer
     *
     * @param string $name
     */
    public function removeRequestParam($name)
    {
        if (isset($this->requestParams[$name])) {
            unset($this->requestParams[$name]);
        }
    }

    
    /**
     * 
     * @return  mixed
     */
    public function execute() {
        return curl_exec($this->ch);
    }

    /**
     * Makes the request of the specified $method to the $url with an optional $requestParams
     *
     * @param string $url
     * @param string $method
     * @param array $requestParams
     * @throws CurlWrapperCurlException
     * @return string
     */
    public function request($url, $method = 'GET', $requestParams = null)
    {
        $this->setURL($url);
        $this->setRequestMethod($method);
        if (!empty($requestParams)) {
            $this->addRequestParam($requestParams);
        }
        $this->initOptions();
        $this->response = $this->execute();
        if ($this->response === false) {
            throw new CurlException($this->ch);
        }
        return $this->response;
    }

    /**
     * Sets the number of seconds to wait while trying to connect, use 0 to wait indefinitely
     *
     * @param integer $seconds
     */
    public function setConnectTimeOut($seconds)
    {
        $this->addOption(CURLOPT_CONNECTTIMEOUT, $seconds);
    }

    /**
     * Sets the default headers
     */
    public function setDefaultHeaders()
    {
        $this->headers = array(
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Charset' => 'utf-8;q=0.7,*;q=0.7',
            'Accept-Language' => 'en-US,en;q=0.8',
            'Accept-Encoding' => 'gzip,deflate',
            'Keep-Alive' => '300',
            'Connection' => 'keep-alive',
            'Cache-Control' => 'max-age=0',
            'Pragma' => ''
        );
    }

    /**
     * Sets the default options
     */
    public function setDefaultOptions()
    {
        $this->options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_ENCODING => 'gzip,deflate',
            CURLOPT_AUTOREFERER => true,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_TIMEOUT => 30,
        );
    }

    /**
     * Sets default headers, options and user agent if $userAgent is given
     *
     */
    public function setDefaults()
    {
        $this->setDefaultHeaders();
        $this->setDefaultOptions();
    }

    /**
     * If $value is true sets CURLOPT_FOLLOWLOCATION option to follow any "Location: " header that the server
     * sends as part of the HTTP header (note this is recursive, PHP will follow as many "Location: " headers
     * that it is sent, unless CURLOPT_MAXREDIRS option is set).
     *
     * @param boolean $value
     */
    public function setFollowRedirects($value)
    {
        $this->addOption(CURLOPT_FOLLOWLOCATION, $value);
    }

    /**
     * Sets the contents of the "Referer: " header to be used in a HTTP request
     *
     * @param string $referer
     */
    public function setReferer($referer)
    {
        $this->addOption(CURLOPT_REFERER, $referer);
    }

    /**
     * Sets the maximum number of seconds to allow cURL functions to execute
     *
     * @param integer $seconds
     */
    public function setTimeout($seconds)
    {
        $this->addOption(CURLOPT_TIMEOUT, $seconds);
    }

    /**
     * Sets the contents of the "User-Agent: " header to be used in a HTTP request
     *
     * @param string $userAgent
     */
    public function setUserAgent($userAgent)
    {
        $this->addOption(CURLOPT_USERAGENT, $userAgent);
    }

    /**
     * Sets the HTTP Authentication type.
     *
     * Defaults to CURLAUTH_BASIC.
     *
     * @param int $type
     */
    public function setAuthType($type = CURLAUTH_BASIC)
    {
        $this->addOption(CURLOPT_HTTPAUTH, $type);
    }

    /**
     * Sets the username and password for HTTP Authentication.
     *
     * @param string $username
     * @param string $password
     */
    public function setAuthCredentials($username, $password)
    {
        $this->addOption(CURLOPT_USERPWD, "$username:$password");
    }

    /**
     * Builds url from associative array produced by parse_str() function
     *
     * @param array $parsedUrl
     * @return string
     */
    protected function buildUrl($parsedUrl)
    {
        return (isset($parsedUrl['scheme']) ? $parsedUrl["scheme"] . '://' : '') .
                (isset($parsedUrl['user']) ? $parsedUrl["user"] . ':' : '') .
                (isset($parsedUrl['pass']) ? $parsedUrl["pass"] . '@' : '') .
                (isset($parsedUrl['host']) ? $parsedUrl["host"] : '') .
                (isset($parsedUrl['port']) ? ':' . $parsedUrl["port"] : '') .
                (isset($parsedUrl['path']) ? $parsedUrl["path"] : '') .
                (isset($parsedUrl['query']) ? '?' . $parsedUrl["query"] : '') .
                (isset($parsedUrl['fragment']) ? '#' . $parsedUrl["fragment"] : '');
    }

    /**
     * Sets the final options and prepares request params, headers and cookies
     *
     * @throws CurlWrapperCurlException
     */
    protected function initOptions()
    {
        if (!empty($this->requestParams)) {
            if (isset($this->options[CURLOPT_HTTPGET])) {
                $this->prepareGetParams();
            } else {
                $this->addOption(CURLOPT_POSTFIELDS, http_build_query($this->requestParams));
            }
        }
        if (!empty($this->headers)) {
            $this->addOption(CURLOPT_HTTPHEADER, $this->prepareHeaders());
        }
        if (!curl_setopt_array($this->ch, $this->options)) {
            throw new CurlWrapperCurlException($this->ch);
        }
    }

    /**
     * Converts request parameters to the query string and adds it to the request url
     */
    protected function prepareGetParams()
    {
        $parsedUrl = parse_url($this->options[CURLOPT_URL]);
        $query = http_build_query($this->requestParams);
        if (isset($parsedUrl['query'])) {
            $parsedUrl['query'] .= '&' . $query;
        } else {
            $parsedUrl['query'] = $query;
        }
        $this->setUrl($this->buildUrl($parsedUrl));
    }

    /**
     * Converts the headers array to the cURL's option format array
     *
     * @return array
     */
    protected function prepareHeaders()
    {
        $headers = array();
        foreach ($this->headers as $header => $value) {
            $headers[] = $header . ': ' . $value;
        }
        return $headers;
    }

    /**
     * Sets the HTTP request method
     *
     * @param string $method
     */
    protected function setRequestMethod($method)
    {
        $this->removeOption(CURLOPT_HTTPGET);
        $this->removeOption(CURLOPT_POST);
        switch (strtoupper($method)) {
            case 'GET':
                $this->addOption(CURLOPT_HTTPGET, true);
                break;
            case 'POST':
                $this->addOption(CURLOPT_POST, true);
                break;
            default:
                throw new CurlWrapperException('Unknow request method');
        }
    }

    /**
     * Sets the request url
     *
     * @param string $url Request URL
     */
    protected function setUrl($url)
    {
        $this->addOption(CURLOPT_URL, $url);
    }

}

