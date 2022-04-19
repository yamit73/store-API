<?php
namespace Api\Handlers;

use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;
use Phalcon\Security\JWT\Token\Parser;
use Phalcon\Security\JWT\Validator;
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
    function getToken()
    {
        $signer  = new Hmac();
        // Builder object
        $builder = new Builder($signer);
        $now        = new \DateTimeImmutable();
        $issued     = $now->getTimestamp();
        $notBefore  = $now->modify('-1 minute')->getTimestamp();
        $expires    = $now->modify('+1 hour')->getTimestamp();
        $passphrase = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';
        // Setup
        $builder
            ->setContentType('application/json')        // cty - header
            ->setExpirationTime($expires)               // exp 
            ->setIssuedAt($issued)                      // iat 
            ->setNotBefore($notBefore)                  // nbf
            ->setSubject('api_token')                   // sub
            ->setPassphrase($passphrase)                // password 
        ;
        // Phalcon\Security\JWT\Token\Token object
        $tokenObject = $builder->getToken();
        // The token
        return $tokenObject->getToken();
    }
}