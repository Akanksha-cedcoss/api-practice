<?php

declare(strict_types=1);

use Firebase\JWT\JWT;
use Phalcon\Mvc\Controller;

final class UserController extends Controller
{
    /**
     * initializing User collection object
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->user = new Users();
    }
    /**
     * login action only for admin
     *
     * @return void
     */
    public function loginAction(): void
    {
        if ($this->request->isPost()) {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            if (!is_null($email) and !is_null($password)) {
                $user = $this->user->getUserByEmailAndPassword($email, $password);
                if ($user) {
                    if ($user->role === 'admin') {
                        $this->session->set('user', $user->name);
                        $this->flash->success('User logged in');
                    } else {
                        $this->flash->error('Only admin can login');
                    }
                } else {
                    $this->flash->error('E-mail or password is wrong.');
                }
            } else {
                $this->flash->error('One or more field is empty.');
            }
        }
    }
    /**
     * log out session
     *
     * @return void
     */
    public function logoutAction(): void
    {
        $this->session->destroy();
    }
    /**
     * add new user to the User Collection
     *
     * @return void
     */
    public function addNewUserAction(): void
    {
        if (!isset($this->session->user)) {
            die('Please login before proceed.');
        }
        if ($this->request->isPost()) {
            $name = $this->request->getPost('user_name');
            $role = $this->request->getPost('role');
            $Product = array(
                'name' => $this->escaper->escapeHtml($name),
                'email' => $this->escaper->escapeHtml($this->request->getPost('email')),
                'password' => $this->escaper->escapeHtml($this->request->getPost('password')),
                'role' => $this->escaper->escapeHtml($role)
            );
            try {   // user to the DB
                $this->user->addNewUser($Product);
                //generating token for the user
                $key = 'example_key';
                $now = new \DateTimeImmutable();
                $payload = array(
                    'iss' => 'http://example.org',
                    'aud' => 'http://example.com',
                    'iat' => $now->getTimeStamp(),
                    'nbf' => $now->modify('-1 minute')->getTimeStamp(),
                    'exp' => $now->modify('+1 day')->getTimeStamp(),
                    'nam' => $name,
                    'sub' => $role
                );
                $token = JWT::encode($payload, $key, 'HS256');
                $this->flash->success('New User Added. Token :- ' . $token . '');
            } catch (\Exception $e) {
                $this->flash->error($e);
            }
        }
    }
}
