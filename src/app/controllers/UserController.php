<?php

use Phalcon\Mvc\Controller;
use Firebase\JWT\JWT;


class UserController extends Controller
{
    public $user;
    public function initialize()
    {
        $this->user = new Users;
    }
    public function loginAction()
    {
        if ($_POST) {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            if (!empty($email) and !empty($password)) {
                $user = $this->user->getUserByEmailAndPassword($email, $password);
                if ($user) {
                    if ($user->role == 'admin') {
                        $this->session->set('user',  $user->name);
                        $this->flash->success('User logged in');
                    } else {
                        $this->flash->error("Only admin can login");
                    }
                } else {
                    $this->flash->error("E-mail or password is wrong.");
                }
            } else {
                $this->flash->error("One or more field is empty.");
            }
        }
    }
    /**
     * log out session
     *
     * @return void
     */
    public function logoutAction()
    {
        $this->session->destroy();
    }
    public function addNewUserAction()
    {
        if (!isset($this->session->user)) {
            die('Please login before proceed.');
        }
        if ($_POST) {
            
            $name = $this->request->getPost('user_name');
            $role = $this->request->getPost('role');
            $Product = array(
                'name' => $this->escaper->escapeHtml($name),
                'email' => $this->escaper->escapeHtml($this->request->getPost('email')),
                'password' => $this->escaper->escapeHtml($this->request->getPost('password')),
                'role' => $this->escaper->escapeHtml($role)
            );
            try {
                $this->user->addNewUser($Product);
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
                $this->flash->success('New User Added. Token :- '.$token.'');
            } catch (\Exception $e) {
                $this->flash->error($e);
            }
        }
    }
}
