<?php

$trackid = $_GET['trackid'] ?? null;

if ($trackid) {
    require 'show.php';
} else {
    require 'ask.php';
}