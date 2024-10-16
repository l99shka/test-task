<?php

namespace App\Controller;



use App\Model\User;

class UserController
{
    public function signup(): array
    {
        session_start();
        $errors = [];

        if (isset($_SESSION['user_id'])) {
            header('Location: /');
        }

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $errors = $this->isValidSignUp($_POST);

            if (empty($errors)) {
                $password = $_POST['password'];
                $hash = password_hash($password, PASSWORD_DEFAULT);

                $user = new User($_POST['name'], $_POST['email'], $_POST['phone'], $hash);

                $user->save();

                header('Location: /login');
            }
        }

        return [
            'view' => 'signup',
            'data' => ['errors' => $errors]
        ];
    }

    private function isValidSignUp(array $data):array
    {
        $errors = [];

        if (empty($data['email'])) {
            $errors['email'] = '* Введите E-mail';
        } else {
            $userEmail = User::getUserEmail($data['email']);

            if (!empty($userEmail)) {
                $errors['email'] = '* Такой E-mail уже сущесвует';
            }
        }

        if (empty($data['name'])) {
            $errors['name'] = '* Введите Имя';
        } else {
            $userName = User::getUserName($data['name']);

            if (!empty($userName)) {
                $errors['name'] = '* Такое Имя уже сущесвует';
            }
        }

        if (empty($data['phone'])) {
            $errors['phone'] = '* Введите Телефон';
        } else {
            $userPhone = User::getUserPhone($data['phone']);

            if (!empty($userPhone)) {
                $errors['phone'] = '* Такой Телефон уже сущесвует';
            }
        }

        if (empty($data['password'])) {
            $errors['password'] = '* Введите пароль';
        }

        if (empty($data['psw'])) {
            $errors['psw'] = '* Повторите пароль';
        } elseif ($data['psw'] !== $data['password']) {
            $errors['psw'] = '* Пароли не совпадают';
        }
        return $errors;
    }

    public function login(): array
    {
        return [
            'view' => 'login',
            'data' => []
        ];
    }

    public function logout()
    {

    }
}