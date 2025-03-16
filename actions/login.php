<?php
include '../utils/Session.php';
include '../utils/Log.php';
include '../utils/Database.php';
$config = include '../config.php';

function panic($error_id = -1, $referer = '..')
{
    header("Location: ../personale/login.php?ref=$referer&err=$error_id");
    die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $remember = $_POST['remember'] ?? null;
    $referer = $_POST['referer'] ?? '..';

    if (empty($email) || empty($password)) {
        panic(1, $referer);
    }

    if (Database::connect($config) == null) {
        panic(0, $referer);
    }

    $pass_hash = password_hash($password, PASSWORD_DEFAULT);
    $user = Database::select("select * from personale where email = :email", [
        'email' => $email,
    ]);

    if (count($user) == 0) {
        panic(2, $referer);
    }

    if (!password_verify($password, $user[0]->password)) {
        panic(2, $referer);
    }

    if (isset($remember)) {
        Session::setLifetime(60 * 60 * 24 * 30); // 30 days
    }
    Session::start();

    $_SESSION['id'] = $user[0]->id;
    $_SESSION['nome'] = $user[0]->nome;
    $_SESSION['cognome'] = $user[0]->cognome;
    $_SESSION['email'] = $user[0]->email;

    header("Location: $referer");
} else {
    panic(1);
}