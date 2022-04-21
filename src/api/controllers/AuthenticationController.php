<?php
namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;
use Firebase\JWT\JWT;

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
    public function generateToken($name, $role)
    {
        $name = explode("=", $name)[1];
        $role = explode("=", $role)[1];
        $key = "example_key";
        $now = new \DateTimeImmutable();
        $payload = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => $now->getTimeStamp(),
            "nbf" => $now->modify("-1 minute")->getTimeStamp(),
            "exp" => $now->modify("+1 day")->getTimeStamp(),
            "nam" => $name,
            "sub" => $role
        );
        $token = JWT::encode($payload, $key, 'HS256');
        $content = [
            "success" => true,
            "payload" => [
                'token for user' =>$name,
                'Role of User' => $role,
                "token" => $token,
                "message" => "Token is generated successfully."
            ],
        ];
        $this->response->setStatusCode(200, 'Token Generated')->setJsonContent($content);
        return $this->response;
    }
}