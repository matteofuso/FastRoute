<?php
include '../utils/Session.php';
include '../utils/Log.php';
include '../utils/Database.php';
$config = include '../config.php';

function panic($error_id = -1)
{
    header("Location: ../personale/account.php?err=$error_id");
    die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['id'])) {
        panic(3);
    }

    if (Database::connect($config) == null) {
        panic(0);
    }

    try {
        $user = Database::select("SELECT * FROM personale WHERE id = :id", [
            'id' => $_SESSION['id']
        ])[0];
    } catch (Exception $e) {
        panic(4);
    }

    $nome = $_POST['nome'] ?? $user->nome;
    $cognome = $_POST['cognome'] ?? $user->cognome;
    $email = $_POST['email'] ?? $user->email;
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $password = $user->password;

    if (empty($nome) || empty($cognome) || empty($email)) {
        panic(6);
    }

    if (!empty($current_password)) {
        if (empty($new_password) || empty($confirm_password)) {
            panic(6);
        }

        if (!password_verify($current_password, $password)) {
            panic(7);
        }

        if ($new_password !== $confirm_password) {
            panic(5);
        }

        $password = password_hash($new_password, PASSWORD_DEFAULT);
    }

    try {
        Database::query("UPDATE personale SET nome = :nome, cognome = :cognome, email = :email, password = :password WHERE id = :id", [
            'nome' => $nome,
            'cognome' => $cognome,
            'email' => $email,
            'password' => $password,
            'id' => $_SESSION['id']
        ]);
    } catch (Exception $e) {
        panic(4);
    }

    $_SESSION['nome'] = $nome;
    $_SESSION['cognome'] = $cognome;
    $_SESSION['email'] = $email;

    header("Location: ../personale/account.php");
} else {
    panic(1);
}