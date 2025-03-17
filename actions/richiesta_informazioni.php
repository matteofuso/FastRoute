<?php

include "../utils/Database.php";
include "../utils/Mailer.php";
include_once "../utils/Log.php";
$config = require "../config.php";

function panic($id, $e = null)
{
    header("Location: ../?err=$id");
    if ($e) {
        Log::errlog($e);
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'] ?? null;
    $email = $_POST['email'] ?? null;
    $messaggio = $_POST['messaggio'] ?? null;

    if (empty($nome) || empty($email) || empty($messaggio)) {
        panic(2);
    }

    if (!Database::connect($config)) {
        panic(1);
    }

    if (!Mailer::init($config)) {
        panic(1);
    }

    try {
        $mail = Mailer::send($config['info_user'], 'Richiesta informazioni', "Richiesta di informazioni da parte di $nome ($email):<br><br>$messaggio");
        header('Location: ..');
    } catch (Exception $e) {
        Log::errlog($e);
        panic(1);
    }
} else {
    panic(1);
}