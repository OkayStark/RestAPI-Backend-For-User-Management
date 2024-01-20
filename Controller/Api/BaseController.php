<?php

class BaseController
{
    public function __call($name, $arguments)
    {
        $this->sendOutput('', ['HTTP/1.1 404 Not Found']);
    }
    protected function getUriSegments()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uriSegments = explode('/', $uri);
        return $uriSegments;
    }
    protected function getQueryStringParams()
    {
        parse_str($_SERVER['QUERY_STRING'], $query);
        return $query;
    }
    protected function sendOutput($data, array $httpHeaders = [])
    {
        header_remove('Set-Cookie');

        if (!empty($httpHeaders)) {
            foreach ($httpHeaders as $httpHeader) {
                header($httpHeader);
            }
        }

        echo $data;
        exit;
    }

    public function decodeJSON($jsonString)
    {
        return json_decode($jsonString, true);
    }
}
