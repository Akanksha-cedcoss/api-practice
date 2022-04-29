<?php

declare(strict_types=1);

use Phalcon\Mvc\Model;

final class Users extends Model
{
    /**
     * initializing mongo constructor
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->collection = $this->di->get('mongo')->users;
    }
    /**
     * get user by email and password
     *
     * @param string $email
     * @param string $password
     * 
     * @return array
     */
    public function getUserByEmailAndPassword(string $email, string $password): array
    {
        return $this->collection->findOne(['email' => $email], ['password' => $password]);
    }
    /**
     * add new user to the database
     *
     * @param array $user
     * 
     * @return void
     */
    public function addNewUser(array $user): void
    {
        $this->collection->insertOne($user);
    }
}