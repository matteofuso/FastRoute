<?php

$password = $_GET['password'] ?? null;

if (empty($password)) {
    echo "No password provided";
    die();
}

echo password_hash($password, PASSWORD_DEFAULT);