<?php

namespace Api\Events;

use Exception;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use Phalcon\Mvc\Micro;

use Phalcon\Security\JWT\Token\Parser;
use Phalcon\Security\JWT\Validator;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Event handler class
 */
class Authentication implements MiddlewareInterface
{
    /**
     * Before event handler
     * Check if token is give or not
     * Validate token
     *
     * @param Micro $app
     * @return void
     */
    public function call(Micro $app)
    {
        //Action name to check if it a request to generate API token
        $action = explode('/',$app->request->get()['_url'])[2];
        if ($action != 'api_token') {
            $token = $app->request->getQuery('token');
            if ($token != '') {
                try {
                    $key = "example_key";
                    $now = new \DateTimeImmutable();
                    /**
                     * parsing token bearer
                     */
                    $parser = new Parser();
                    $tokenObject = $parser->parse($token);

                    /**
                     * validating token
                     */
                    $validator = new Validator($tokenObject, 100);
                    $validator->validateExpiration($now->getTimestamp())
                        ->validateNotBefore($now->modify('-1 minute')->getTimestamp());

                    /**
                     * decoding token using the same key that is used to encode
                     */
                    $decodedToken = JWT::decode($token, new Key($key, 'HS256'));
                    
                    if ($decodedToken->sub != 'api_token' || $decodedToken->name == '') {
                        $app->response->setStatusCode(400)
                            ->setJsonContent("Invalid token!")
                            ->send();
                        die;
                    }
                } catch (Exception $e) {
                    $app->response->setStatusCode(400)
                        ->setJsonContent($e->getMessage())
                        ->send();
                    die;
                }
            } else {
                $app->response->setStatusCode(401, "Token not found")
                    ->setJsonContent("Token not found")
                    ->send();
                die;
            }
        }
    }
}
