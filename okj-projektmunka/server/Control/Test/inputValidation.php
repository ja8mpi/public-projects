<?php
function testInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
function validateNameFormat($name)
{
    return preg_match('/^[a-zA-Z0-9áéíóöőúüűÁÉÍÓÖŐÜŰ]+$/', $name);
}
function validateEmailFormat($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

//REGISTRATION FUNCTIONS
function validateName($conn, $type, $name)
{
    switch ($type) {
        case "register":
            if (!User::checkName($conn, $name)) {
                return false;
            } else {
                throw new Exception("Foglalt felhasználónév");
            }
            break;
        case "login":
            if (User::checkName($conn, $name)) {
                return true;
            } else {
                throw new Exception("Hibás felhasználónév");
            }
            break;
    }
}
function validateEmail($conn, $type, $email)
{
    if (!User::checkEmail($conn, $email)) {
        return false;
    } else {
        throw new Exception("Foglalt email cím");
    }
}
function validateRegistration($conn, $type, $name, $email, $pwd)
{
    validateName($conn, $type, $name);
    validateEmail($conn, $type, $email);
    if (User::createUser($conn, $name, $email, $pwd)) {
        return ("Sikeres regisztráció!");
    } else {
        throw new Exception("Sikertelen regisztráció!");
    }
}


//LOGIN FUNCTIONS
function validateLogin($conn, $type, $name, $password)
{
    validateName($conn, $type, $name);
    if (password_verify($password, User::checkPassword($conn, $name))) {
        return true;
    } else {
        throw new Exception("Hibás felhasználónév vagy jelszó");
    }
}
