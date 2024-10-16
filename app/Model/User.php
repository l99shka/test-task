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

    public static function getUserEmail(string $email):User|null
    {
        $stmt = ConnectFactory::create()->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch();

        if (!$data) {
            return null;
        }

        $user = new User($data['name'], $data['email'], $data['phone'], $data['password']);
        $user->setId($data['id']);

        return $user;
    }

    public static function getUserName(string $name):User|null
    {
        $stmt = ConnectFactory::create()->prepare("SELECT * FROM users WHERE name = :name");
        $stmt->execute(['name' => $name]);
        $data = $stmt->fetch();

        if (!$data) {
            return null;
        }

        $user = new User($data['name'], $data['email'], $data['phone'], $data['password']);
        $user->setId($data['id']);

        return $user;
    }

    public static function getUserPhone(string $phone):User|null
    {
        $stmt = ConnectFactory::create()->prepare("SELECT * FROM users WHERE phone = :phone");
        $stmt->execute(['phone' => $phone]);
        $data = $stmt->fetch();

        if (!$data) {
            return null;
        }

        $user = new User($data['name'], $data['email'], $data['phone'], $data['password']);
        $user->setId($data['id']);

        return $user;
    }

    public static function getUser(int $id):User|null
    {
        $stmt = ConnectFactory::create()->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $data = $stmt->fetch();

        if (!$data) {
            return null;
        }

        $user = new User($data['name'], $data['email'], $data['phone'], $data['password']);
        $user->setId($data['id']);

        return $user;
    }

    public function save(): void
    {
        $stmt = ConnectFactory::create()->prepare("INSERT INTO users (name, email, phone, password) VALUES (:name, :email, :phone, :password)");
        $stmt->execute(['name' => $this->name, 'email' => $this->email, 'phone' => $this->phone, 'password' => $this->password]);
    }

    public function update(int $id): void
    {
        $stmt = ConnectFactory::create()->prepare("UPDATE users SET name = :name, email = :email, phone = :phone, password = :password WHERE id = :id");
        $stmt->execute(['name' => $this->name, 'email' => $this->email, 'phone' => $this->phone, 'password' => $this->password, 'id' => $id]);
    }

    private function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName():string
    {
        return $this->name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getEmail():string
    {
        return $this->email;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getPhone():string
    {
        return $this->phone;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getPassword():string
    {
        return $this->password;
    }
}
