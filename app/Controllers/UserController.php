<?php

namespace App\Controllers;

use App\Repositories\DatabaseApiRepository;
use App\Repositories\DatabaseConnection;
use App\Repositories\DatabaseRepository;
use App\Views\Redirect;
use App\Services\RegisterService;
use App\Models\UserObject;
use App\Views\Template;
use App\Validation\Validation;

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
        $validation = new Validation();
        $validation->validateRegistration($_POST);
        $registerService = $this->registerService;
        $serviceUser = new UserObject(
            $id = 0,
            $_POST["username"],
            $_POST["email"],
            $_POST["password"],
        );

        $validation->validateRegisterUsername($this->database->getAllDatabase()->getUsers(), $serviceUser);
        $validation->validateRegisterEmail($this->database->getAllDatabase()->getUsers(), $serviceUser);

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

        $validation = new Validation();
        $valUsername = $validation->validateLoginUsername($_SESSION['id'], $this->database->getAllDatabase()->getUsers(), $user);
        $valPassword = $validation->validateLoginPassword($_SESSION['id'], $this->database->getAllDatabase()->getUsers(), $user);

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
        (new Validation())->validateChangeEmailUsername($_POST);
        if (!empty ($_SESSION['error'])) {
            return new Redirect('/change');
        }
        if (strlen($_POST['username']) === 0) {
            $database->executeStatement('UPDATE new_schema.usersRegister SET email=? WHERE id = ?', [$_POST['email'], $_SESSION['id']]);
        } elseif (strlen($_POST['email']) === 0) {
            $database->executeStatement('UPDATE new_schema.usersRegister SET username = ? WHERE id = ?', [$_POST['username'], $_SESSION['id']]);
        } else {
            $database->executeStatement('UPDATE new_schema.usersRegister SET username = ?,email=? WHERE id = ?', [$_POST['username'], $_POST['email'], $_SESSION['id']]);
        }
        return new Redirect('/');
    }

    public function changePassword(): Redirect
    {
        (new Validation())->validateChangePassword($this->database->getAllDatabase()->getUsers(), $_POST);

        $database = (new DatabaseConnection())->getConnection();

        if (!empty ($_SESSION['error'])) {
            return new Redirect('/change');
        }
        $database->executeStatement('UPDATE new_schema.usersRegister SET password=? WHERE id = ?', [$_POST['new-password'], $_SESSION['id']]);


        return new Redirect('/');
    }
}