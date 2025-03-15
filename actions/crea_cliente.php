<?php
include '../utils/Database.php';
include_once '../utils/Log.php';
$config = require '../config.php';

function panic($id){
    header("Location: ../personale/clienti.php?error=$id");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? null;
    $cognome = $_POST['cognome'] ?? null;
    $email = $_POST['email'] ?? null;
    $numero = $_POST['numero'] ?? null;
    $indirizzo = $_POST['indirizzo'] ?? null;

    if (empty($nome) || empty($cognome) || empty($email) || empty($numero) || empty($indirizzo)){
        panic(2);
    }

    if (!Database::connect($config)){
        panic(1);
    }

    try {
        Database::query("INSERT INTO clienti (nome, cognome, email, numero, indirizzo) VALUES (:nome, :cognome, :email, :numero, :indirizzo)", [
            ':nome' => $nome,
            ':cognome' => $cognome,
            ':email' => $email,
            ':numero' => $numero,
            ':indirizzo' => $indirizzo
        ]);
    } catch (Exception $e) {
        Log::errlog($e);
        panic(1);
    }
    header('Location: ../personale/clienti.php');
} else {
    panic(1);
}