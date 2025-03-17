<?php

include "../utils/Database.php";
include "../utils/Mailer.php";
include_once "../utils/Log.php";
$config = require "../config.php";

function panic($id, $e = null){
    header("Location: ../tracking/?err=$id");
    if ($e) {
        Log::errlog($e);
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $action = $_POST['action'] ?? null;
    $id_plico = $_POST['id_plico'] ?? null;

    if (empty($action) || empty($id_plico)){
        panic(2);
    }

    if (!Database::connect($config)){
        panic(1);
    }

    if (!Mailer::init($config)){
        panic(1);
    }

    try {
        if ($action == 'partenza') {
            $partenza = $_POST['partenza'] ?? null;
            if (empty($partenza)){
                panic(2);
            }

            Database::query("UPDATE plichi SET partenza = :partenza WHERE id = :id", [
                ':partenza' => $partenza,
                ':id' => $id_plico
            ]);
        } else if ($action == 'arrivo') {
            $arrivo = $_POST['arrivo'] ?? null;
            if (empty($arrivo)){
                panic(2);
            }

            Database::query("UPDATE plichi SET arrivo = :arrivo WHERE id = :id", [
                ':arrivo' => $arrivo,
                ':id' => $id_plico
            ]);
        } else if ($action = 'ritiro') {
            $ritiro = $_POST['ritiro'] ?? null;
            if (empty($ritiro)){
                panic(2);
            }

            Database::query("UPDATE plichi SET ritiro = :ritiro WHERE id = :id", [
                ':ritiro' => $ritiro,
                ':id' => $id_plico
            ]);

            $plico = Database::select("SELECT * FROM plichi p
inner join clienti c on c.id = p.cliente 
where p.id = :id", [':id' => $id_plico])[0];

            $mail = Mailer::send($plico->email, 'Plico Ritirato', 'Il plico con id ' . $id_plico . ' è stato ritirato dal destinatario.<br>Grazie per aver scelto FastRoute!');

        } else {
            panic(2);
        }

        header('Location: ../tracking/?trackid=' . $id_plico);
    } catch (Exception $e) {
        Log::errlog($e);
        panic(1);
    }
} else {
    panic(1);
}