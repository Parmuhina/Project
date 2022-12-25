<?php

namespace App\Validation;

use App\Models\UserObject;

class Validation
{
    public function validateRegistration(array $post): void
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

    public function validateRegisterUsername(array $userArray, UserObject $request): void
    {
        foreach ($userArray as $row) {
            if ($row->getUsername() === $request->getUsername()) {
                $_SESSION['error']['username'] = 'Username exist';
            }
        }
    }

    public function validateRegisterEmail(array $userArray, UserObject $request): void
    {
        foreach ($userArray as $row) {
            if ($row->getEmail() === $request->getEmail()) {
                $_SESSION['error']['email'] = 'Email exist';
            }
        }
    }

    public function validateLoginUsername(int $id, array $userArray, UserObject $request): bool
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

    public function validateLoginPassword(int $id, array $userArray, UserObject $request): bool
    {

        foreach ($userArray as $row) {
            if ($row->getPassword() === $request->getPassword() && $row->getId() === $id) {
                return true;
            }
        }
        $_SESSION['error']['loginPassword'] = 'Username or password not correct';
        return false;
    }

    public function validateChangePassword(array $userArray, array $post): void
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

    public function validateChangeEmailUsername(array $post): void
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