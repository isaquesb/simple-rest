<?php
namespace Simple\Rest\Service;

use Simple\Http\Response\ResponseInterface;
use Simple\Rest\Ambient\Ambient;
use Simple\Rest\Auth\Auth;
use Simple\Rest\Auth\PublicAccess;
use Simple\Http\Request\Adapter\AdapterInterface;
use Simple\Http\Request\Adapter\CurlAdapter;
use Simple\Http\Request\Request;

abstract class Service
{
    /**
     * @var Ambient
     */
    protected $ambient;

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * Client constructor.
     * @param Ambient $ambient
     * @param Auth $auth
     * @param AdapterInterface|null $adapter
     */
    public function __construct(Ambient $ambient, Auth $auth, AdapterInterface $adapter = null)
    {
        if (is_null($adapter)) {
            $adapter = new CurlAdapter([]);
        }
        $this->ambient = $ambient;
        $this->auth = $auth;
        $this->adapter = $adapter;
    }

    /**
     * @param Request $request
     */
    public abstract function requestOptions(Request $request);

    /**
     * @return Request
     */
    protected function newRequest()
    {
        return new Request($this->adapter);
    }

    /**
     * @param array $params
     * @return ServiceParameters
     */
    protected function parameters($params)
    {
        return new ServiceParameters($params);
    }

    /**
     * @throws ServiceResponseException
     * @param string $description
     * @param string $method
     * @param string $uri
     * @param ServiceParameters|null $parameters
     * @return array
     */
    protected function doRequest($description, $method, $uri, ServiceParameters $parameters = null)
    {
        $request = $this->newRequest();
        $this->requestOptions($request);
        if (!($this instanceof PublicAccess)) {
            $this->auth->authenticate($this, $request);
        }
        /**
         * @var $response ResponseInterface
         */
        $response = $request->$method($this->ambient->getUri() . $uri, $parameters ?: []);
        $status = $response->getHttpStatus();
        $body = $response->getRawBody();
        if (200 != $status) {
            $errors = print_r($response->getErrors(), 1);
            throw new ServiceResponseException($description . ' Failed: ' . $status . ' - ' . $body . ' - '. $errors);
        }
        return json_decode($body, true);
    }
}
