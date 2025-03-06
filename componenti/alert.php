<?php

if (!Database::isConnected())
{
    $_GET['err'] = '0';
}

if (isset($_GET['err'])) {
    $errors = [
        '-1' => 'Errore generico',
        '0' => 'Impossibile connettersi al database',
    ];
    $err = $_GET['err'];
    if (!array_key_exists($err, $errors)) {
        $err = '-1';
    }
    echo "<div class='alert alert-danger alert-dismissible' role='alert'>
          <p class='flex-grow-1 my-0 align-baseline'><i class='bi bi-exclamation-triangle me-2'></i>$errors[$err]</p>
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
}

if (isset($_GET['succ'])){
    $successes = [
        '-1' => 'Operazione completata con successo',
    ];
    $succ = $_GET['succ'];
    if (!array_key_exists($succ, $successes)) {
        $succ = '-1';
    }
    echo "<div class='alert alert-success alert-dismissible' role='alert'>
          <p class='flex-grow-1 my-0 align-baseline'><i class='bi bi-check-circle me-2'></i>$successes[$succ]</p>
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
}