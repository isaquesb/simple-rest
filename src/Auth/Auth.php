<?php
namespace Simple\Rest\Auth;

use Simple\Http\Request\RequestInterface;
use Simple\Rest\Service\Service;

interface Auth
{
    /**
     * @param Service $service
     * @param RequestInterface $request
     */
    public function authenticate(Service $service, RequestInterface $request);
}
