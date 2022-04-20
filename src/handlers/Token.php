<?php
namespace Api\Handlers;
use Phalcon\Http\Response;
use Firebase\JWT\JWT;
/**
 * Class
 * To get api access token
 * To validate api token
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
            "exp" => $now->modify('+1 hour')->getTimestamp(),
            'sub' => 'api_token',
            'name' => $name
        );
        $response->setStatusCode(200)
                 ->setJsonContent(JWT::encode($payload, $key, 'HS256'))
                 ->send();
    }
}