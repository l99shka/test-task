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

    private function isValidSignUp(array $data): array
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
        session_start();
        $errors = [];
        $errorsLoginPsw = [];

        if (isset($_SESSION['user_id'])) {
            header('Location: /');
        }

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $errors = $this->isValidLogin($_POST);


            if (empty($errors)) {
                $login = $_POST['email-phone'];
                $password = $_POST['password'];

                if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
                    $user = User::getUserEmail($login);
                } else {
                    $user = User::getUserPhone($login);
                }

                if (!empty($user) && password_verify($password, $user->getPassword())) {
                    $_SESSION['user_id'] = $user->getId();

                    header('Location: /profile');
                } else {
                    $errorsLoginPsw = ['errors' => '* Неверный логин или пароль'];
                }
            }
        }

        return [
            'view' => 'login',
            'data' => [
                'errors' => $errors,
                'errorsLoginPsw' => $errorsLoginPsw
            ]
        ];
    }

    private function isValidLogin(array $data): array
    {
        $errors = [];

        if (empty($data['email-phone'])) {
            $errors['email-phone'] = '* Ввведите E-mail или телефон';
        }

        if (empty($data['password'])) {
            $errors['password'] = '* Введите пароль';
        }

        $token = $data['smart-token'];

        if (!$this->check_captcha($token)) {
            $errors['captcha'] = "* Пройдите каптчу";
        }

        return $errors;
    }

    public function check_captcha(string $token): bool
    {
        $user_ip = $_SERVER['REMOTE_ADDR'];
        define('SMARTCAPTCHA_SERVER_KEY', 'ysc2_MqmtcEovTOg5lhVvNO8ECy6S3Agq3JzYZYhJqXEha8fe3dc1');

        $ch = curl_init("https://smartcaptcha.yandexcloud.net/validate");
        $args = [
            "secret" => SMARTCAPTCHA_SERVER_KEY,
            "token" => $token,
            "ip" => "$user_ip"
        ];
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($args));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode !== 200) {
            return true;
        }

        $resp = json_decode($server_output);
        return $resp->status === "ok";
    }

    public function logout(): void
    {
        session_start();

        unset($_SESSION['user_id']);
        header('Location: /');
    }
}