<?php

use Phalcon\Mvc\Model;

class Users extends Model
{
    public $collection;
    /**
     * initializing mongo constructor
     *
     * @return void
     */
    public function initialize()
    {
        $this->collection = $this->di->get("mongo")->users;
    }
    public function getUserByEmailAndPassword($email, $password)
    {
        return $this->collection->findOne(['email'=>$email], ['password'=>$password]);
    }
    /**
     * add new user to the database
     *
     * @param [type] $product
     * @return void
     */
    public function addNewUser($user)
    {
        $this->collection->insertOne($user);
    }
}