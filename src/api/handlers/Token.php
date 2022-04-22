<?php
namespace Api\Handlers;
use Phalcon\Http\Response;
use Firebase\JWT\JWT;
/**
 * Class
 * To get api access token
 */
class Token
{
    /**
     * To generate API access token
     *
     * @return void
     */
    function getToken($name)
    {
        $response=new Response();
        $key = "example_key";
        $now = new \DateTimeImmutable();
        $payload = array(
            "iat" => $now->getTimestamp(),
            "nbf" => $now->modify('-1 minute')->getTimestamp(),
            "exp" => $now->modify('+1 day')->getTimestamp(),
            'sub' => 'api_token',
            'nam' => $name
        );
        $response->setStatusCode(200)
                 ->setContent(JWT::encode($payload, $key, 'HS256'))
                 ->send();
    }
}