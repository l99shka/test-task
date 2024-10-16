<?php

namespace App\Controller;


use App\Model\User;

class ProfileController
{
    public function profile(): array
    {
        $user = [];

        session_start();

        if (isset($_SESSION['user_id'])) {
            $user = User::getUser($_SESSION['user_id']);
        } else {
            header('Location: /');
        }

        return [
            'view' => 'profile',
            'data' => ['user' => $user]
        ];
    }

    public function editProfile(): array
    {
        session_start();
        $updated = false;
        $errors = [];

        $user = User::getUser($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $errors = $this->isValidEdit($_POST);

            if (empty($errors)) {
                $password = $_POST['password'];
                $name = $_POST['name'];
                $email = $_POST['email'];
                $phone = $_POST['phone'];

                if (!password_verify($password, $user->getPassword())) {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $user->setPassword($hash);
                    $updated = true;
                }
                if ($user->getName() !== $name) {
                    $user->setName($name);
                    $updated = true;
                }
                if ($user->getEmail() !== $email) {
                    $user->setEmail($email);
                    $updated = true;
                }
                if ($user->getPhone() !== $phone) {
                    $user->setPhone($phone);
                    $updated = true;
                }

                if ($updated) {
                    $user->update($user->getId());
                    header('Location: /profile');
                }
            }
        }

        return [
            'view' => 'profile',
            'data' => [
                'errors' => $errors,
                'user' => $user,
            ]
        ];
    }

    private function isValidEdit(array $data): array
    {
        $errors = [];

        if (empty($data['email'])) {
            $errors['email'] = '* Введите E-mail';
        }

        if (empty($data['name'])) {
            $errors['name'] = '* Введите Имя';
        }

        if (empty($data['phone'])) {
            $errors['phone'] = '* Введите Телефон';
        }

        return $errors;
    }
}