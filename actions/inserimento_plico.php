<?php

include "../utils/Database.php";
include_once "../utils/Log.php";
$config = require "../config.php";

function panic($id, $e = null){
    header("Location: ../personale/inserimento_plico.php?err=$id");
    if ($e) {
        Log::errlog($e);
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $dimensione = $_POST['dimensione'] ?? null;
    $peso = $_POST['peso'] ?? null;
    $partenza = $_POST['partenza'] ?? null;
    $destinazione = $_POST['destinazione'] ?? null;
    $cliente = $_POST['cliente'] ?? null;
    $nome_destinatario = $_POST['nome_destinatario'] ?? null;
    $cognome_destinatario = $_POST['cognome_destinatario'] ?? null;
    $indirizzo_destinatario = $_POST['indirizzo_destinatario'] ?? null;
    $data_consegna = date('Y-m-d H:i:s');

    if (empty($dimensione) || empty($peso) || empty($partenza) || empty($destinazione) || empty($cliente) || empty($nome_destinatario) || empty($cognome_destinatario) || empty($indirizzo_destinatario)){
        panic(2);
    }

    if (!Database::connect($config)){
        panic(1);
    }

    try {
        Database::query("INSERT INTO plichi (dimensione, peso, destinazione, origine, cliente, consegna) values (:dimensione, :peso, :destinazione, :partenza, :cliente, :consegna)", [
            ':dimensione' => $dimensione,
            ':peso' => $peso,
            ':destinazione' => $destinazione,
            ':partenza' => $partenza,
            ':cliente' => $cliente,
            ':consegna' => $data_consegna
        ]);

        $id = Database::connect($config)->lastInsertId();

        Database::query("INSERT INTO destinatari (plico, nome, cognome, indirizzo) values (:plico, :nome, :cognome, :indirizzo)", [
            ':nome' => $nome_destinatario,
            ':cognome' => $cognome_destinatario,
            ':indirizzo' => $indirizzo_destinatario,
            ':plico' => $id
        ]);

        header('Location: ../tracking/?trackid=' . $id);
    } catch (Exception $e) {
        Log::errlog($e);
        panic(1);
    }
} else {
    panic(1);
}