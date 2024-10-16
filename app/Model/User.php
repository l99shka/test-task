<?php

namespace App\Model;

class User
{
    private int $id;
    private string $name;
    private string $email;
    private string $phone;
    private string $password;
    public function __construct(string $name, string $email, string $phone, string $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->password = $password;
    }
}
