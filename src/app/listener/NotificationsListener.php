<?php

namespace App\Listeners;


use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use Phalcon\Http\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Phalcon\Security\JWT\Token\Parser;
use Phalcon\Security\JWT\Validator;

/**
 * event listener class
 */
class NotificationsListener implements MiddlewareInterface
{
    /**
     * before loading url event
     *
     * @param Event $event
     * @param \Phalcon\Mvc\Application $application
     * @return void
     */
    public function call(Micro $application)
    {
        $response = new Response;
        $bearer = $application->request->get("bearer");
        if ($bearer) {
            $key = "example_key";
            $parser = new Parser();
            $now = new \DateTimeImmutable();
            $expires = $now->getTimestamp();
            $token = $parser->parse($bearer);
            try {
                $validator = new Validator($token, 100);
                $validator->validateExpiration($expires);
                $jwt = JWT::decode($bearer, new Key($key, 'HS256'));
            } catch (\Exception $e) {
                $response
                    ->setStatusCode(401, 'Token expired')
                    ->sendHeaders()
                    ->setJsonContent($e->getMessage())
                    ->send();
                die;
            }
        } else {
            $response
                ->setStatusCode(404, 'Token Invalid')
                ->sendHeaders()
                ->setJsonContent('Token is required.')
                ->send();
            die;
        }
    }
}
