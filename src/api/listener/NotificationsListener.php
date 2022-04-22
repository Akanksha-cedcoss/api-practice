<?php

namespace App\Listeners;


use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use Phalcon\Http\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Phalcon\Security\JWT\Token\Parser;
use Phalcon\Security\JWT\Validator;
use Phalcon\Cache\AdapterFactory;
use Phalcon\Storage\SerializerFactory;

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

        $url = explode('/', $application->request->get()['_url']);
        $response = new Response;
        $bearer = $application->request->get("bearer");
        if ($url[1] == 'api' and $url[2] !== 'authenticate') {
            if ($bearer) {
                $key = "example_key";
                $parser = new Parser();
                $now = new \DateTimeImmutable();
                $expires = $now->getTimestamp();
                $token = $parser->parse($bearer);
                try {
                    /**
                     * validating token
                     */
                    $validator = new Validator($token, 100);
                    $validator->validateExpiration($expires);
                    $jwt = JWT::decode($bearer, new Key($key, 'HS256'));
                    $role = $jwt->sub;
                    $user_id = $jwt->uid;
                    /**
                     * defining user id and role
                     */
                    // return '1233435';
                    define("USER_ID",$user_id);
                    define("ROLE",$role);
                } catch (\Exception $e) {
                    $content = [
                        "success" => false,
                        "payload" => [
                            "message" => "Bearer can not be authorized.",
                            "error" => $e->getMessage()
                        ],
                    ];
                    $response
                        ->setStatusCode(401, 'Token authorization failed')
                        ->sendHeaders()
                        ->setJsonContent($content)
                        ->send();
                    die;
                }
            } else {
                $content = [
                    "success" => false,
                    "payload" => [
                        "message" => "Bearer is required to process request.",
                        "error" => "Bearer is not provided."
                    ],
                ];
                $response
                    ->setStatusCode(404, 'Token Invalid')
                    ->sendHeaders()
                    ->setJsonContent($content)
                    ->send();
                die;
            }
        }
    }
}
