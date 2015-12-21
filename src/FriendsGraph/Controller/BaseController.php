<?php
namespace FriendsGraph\Controller;

use Symfony\Component\HttpFoundation\Response;

class BaseController
{
    protected function sendJson(array $data, $httpCode)
    {
        return new Response(
            json_encode($data),
            $httpCode,
            ['Content-Type' => 'application/json']
        );
    }

}