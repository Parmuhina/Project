<?php

namespace App\Controllers;

use App\Repositories\DatabaseConnection;
use App\Repositories\DatabaseRepository;
use App\Views\Redirect;
use App\Services\RegisterService;
use App\Models\UserObject;
use App\Views\Template;

class UserController
{
    private DatabaseRepository $database;
    private RegisterService $registerService;

    public function __construct(DatabaseRepository $database, RegisterService $registerService)
    {
        $this->database = $database;
        $this->registerService = $registerService;
    }

    public function showForm(): Template
    {
        return new Template(
            'registration.twig'
        );
    }

    public function useNewUser(): Redirect
    {
        $this->validateRegistration($_POST);
        $registerService = $this->registerService;
        $serviceUser = new UserObject(
            $id = 0,
            $_POST["username"],
            $_POST["email"],
            $_POST["password"],
        );

        $this->validateRegisterUsername($this->database->getAllDatabase()->getUsers(), $serviceUser);
        $this->validateRegisterEmail($this->database->getAllDatabase()->getUsers(), $serviceUser);

        if (!empty ($_SESSION['error'])) {
            unset($_SESSION['id']);
            return new Redirect('/registration');
        }

        $registerService->execute($serviceUser);
        return new Redirect('/');
    }

    public function loginUser(): Redirect
    {
        $registerService = $this->registerService;
        $user = new UserObject(
            $id = 0,
            $_POST["username"],
            $email = "",
            $_POST["password"]
        );
        $_SESSION['id'] = $registerService->findID($user);
        if ($_SESSION['id'] === null) {
            $_SESSION['id'] = 0;
        }

        $valUsername = $this->validateLoginUsername($_SESSION['id'], $this->database->getAllDatabase()->getUsers(), $user);
        $valPassword = $this->validateLoginPassword($_SESSION['id'], $this->database->getAllDatabase()->getUsers(), $user);

        if ($valUsername === true && $valPassword === true) {
            return new Redirect('/');
        }
        unset($_SESSION['id']);
        unset($_SESSION['error']);
        return new Redirect('/');
    }

    public function logoutUser(): Redirect
    {
        unset($_SESSION['id']);
        unset($_SESSION['loginName']);
        unset($_SESSION['loginPassword']);
        return new Redirect(
            '/'
        );
    }

    public function change(): Template
    {
        return new Template ('change.twig');
    }

    public function changeUser(): Redirect
    {
        $database = (new DatabaseConnection())->getConnection();
        $this->validateChangeEmailUsername($_POST);
        if (!empty ($_SESSION['error'])) {
            return new Redirect('/change');
        }
        if (strlen($_POST['username']) === 0) {
            $database->executeStatement('UPDATE user_schema.usersRegister SET email=? WHERE id = ?', [$_POST['email'], $_SESSION['id']]);
        } elseif (strlen($_POST['email']) === 0) {
            $database->executeStatement('UPDATE user_schema.usersRegister SET username = ? WHERE id = ?', [$_POST['username'], $_SESSION['id']]);
        } else {
            $database->executeStatement('UPDATE user_schema.usersRegister SET username = ?,email=? WHERE id = ?', [$_POST['username'], $_POST['email'], $_SESSION['id']]);
        }
        return new Redirect('/');
    }

    public function changePassword(): Redirect
    {
        $this->validateChangePassword($this->database->getAllDatabase()->getUsers(), $_POST);

        $database = (new DatabaseConnection())->getConnection();

        if (!empty ($_SESSION['error'])) {
            return new Redirect('/change');
        }
        $database->executeStatement('UPDATE user_schema.usersRegister SET password=? WHERE id = ?', [$_POST['new-password'], $_SESSION['id']]);


        return new Redirect('/');
    }

    private function validateRegistration(array $post): void
    {
        unset($_SESSION['error']);

        if ($post["password"] != $post["password-repeat"]) {
            $_SESSION['error']['password'] = 'Passwords are not equal';
        }

        if (strlen($post["password"]) === 0) {
            $_SESSION['error']['password'] = 'Password is empty';
        }

        if (strlen($post["username"]) === 0) {
            $_SESSION['error']['username'] = 'Username is empty';
        }

        if (strlen($post["email"]) === 0) {
            $_SESSION['error']['email'] = 'Email is empty';
        }
    }

    private function validateRegisterUsername(array $userArray, UserObject $request): void
    {
        foreach ($userArray as $row) {
            if ($row->getUsername() === $request->getUsername()) {
                $_SESSION['error']['username'] = 'Username exist';
            }
        }
    }

    private function validateRegisterEmail(array $userArray, UserObject $request): void
    {
        foreach ($userArray as $row) {
            if ($row->getEmail() === $request->getEmail()) {
                $_SESSION['error']['email'] = 'Email exist';
            }
        }
    }

    private function validateLoginUsername(int $id, array $userArray, UserObject $request): bool
    {
        unset($_SESSION['error']);
        foreach ($userArray as $row) {
            if ($row->getUsername() === $request->getUsername() && $row->getId() === $id) {
                return true;
            }
        }
        $_SESSION['error']['loginName'] = 'Username or password not correct';
        return false;
    }

    private function validateLoginPassword(int $id, array $userArray, UserObject $request): bool
    {

        foreach ($userArray as $row) {
            if ($row->getPassword() === $request->getPassword() && $row->getId() === $id) {
                return true;
            }
        }
        $_SESSION['error']['loginPassword'] = 'Username or password not correct';
        return false;
    }

    private function validateChangePassword(array $userArray, array $post): void
    {
        unset($_SESSION['error']);
        foreach ($userArray as $row) {
            if ($row->getPassword() != $post['password'] && $row->getId() === $_SESSION['id']) {
                $_SESSION['error']['changePassword'] = 'Password not correct';
            }
        }

        if ($post['new-password'] != $post['repeat-new-password']) {
            $_SESSION['error']['newPasswordChange'] = 'Passwords isn`t the same';
        }
        if (empty ($_SESSION['id'])) {
            $_SESSION['error']['authorizationE'] = 'You need to authorize.';
        }
    }

    private function validateChangeEmailUsername(array $post): void
    {
        unset($_SESSION['error']);
        if (strlen($post['username']) === 0 && strlen($post['email']) === 0) {
            $_SESSION['error']['changeEmailUsername'] = 'Both are empty';
        }
        if (strlen($post['username']) === 0 && strlen($post['email']) === 0) {
            $_SESSION['error']['changeEmailUsername'] = 'Both are empty';
        }
        if (empty ($_SESSION['id'])) {
            $_SESSION['error']['authorizationP'] = 'You need to authorize.';
        }
    }
}