<?php

namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;
use Firebase\JWT\JWT;
use Users;
use Phalcon\Di\Injectable;
/**
 * @property Response $response
 */

class AuthenticationController extends Controller
{
    public function index()
    {
        // ...
    }
    /**
     * generate token using jwt
     *
     * @return void
     */
    public function generateToken($email)
    {
        $email = explode("=", $email)[1];
        $users = new Users;
        $user = $users->getUserByEmail($email);
        /**
         * if e-mail does not found in the db
         */
        if (is_null($user)) {
            $content = [
                "success" => false,
                "payload" => [
                    'E-mail' => $email,
                    "message" => "Token can not be generated.",
                    "error" => "User e-mail is not exits in the database."
                ],
            ];
            $this->response->setStatusCode(400)->setJsonContent($content);
            return $this->response;
        } else {
            $role = $user->role;
            $key = "example_key";
            $now = new \DateTimeImmutable();
            $payload = array(
                "iss" => "http://example.org",
                "aud" => "http://example.com",
                "iat" => $now->getTimeStamp(),
                "nbf" => $now->modify("-1 minute")->getTimeStamp(),
                "exp" => $now->modify("+1 day")->getTimeStamp(),
                "uid" => $user->_id,
                "sub" => $role
            );
            $token = JWT::encode($payload, $key, 'HS256');
            $content = [
                "success" => true,
                "payload" => [
                    'User id' => $user->_id,
                    'Role of User' => $role,
                    "token" => $token,
                    "message" => "Token is generated successfully."
                ],
            ];
            $this->response->setStatusCode(200, 'Token Generated')->setJsonContent($content);
            return $this->response;
        }
        
    }
}
