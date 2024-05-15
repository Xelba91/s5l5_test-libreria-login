<?php

$db = "ifoa_s5l5_test";
$config = [
    'mysql_host' => 'localhost',
    'mysql_user' => 'root',
    'mysql_password' => ''
];

$mysqli = new mysqli(
    $config['mysql_host'],
    $config['mysql_user'],
    $config['mysql_password']);

if($mysqli->connect_error) { die($mysqli->connect_error); } 

// Creo il database
$sql = 'CREATE DATABASE IF NOT EXISTS ' . $db;
if(!$mysqli->query($sql)) { die($mysqli->connect_error); }

// Seleziono il Database
$sql = 'USE ' . $db;
$mysqli->query($sql);

// creo la tabella

$sql = 'CREATE TABLE IF NOT EXISTS users ( 
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nomeutente VARCHAR(255) NOT NULL, 
    password VARCHAR(255) NOT NULL,
    ruolo VARCHAR(255) NOT NULL DEFAULT "utente"
)';
if(!$mysqli->query($sql)) { die($mysqli->connect_error); }

// Leggo dati da una tabella
$sql = 'SELECT * FROM users;';
$res = $mysqli->query($sql);
if($res->num_rows === 0) { 
    $password = password_hash('password', PASSWORD_DEFAULT);
    // Inserisco dati in una tabella
    $sql = 'INSERT INTO users (nomeutente, password, ruolo) 
        VALUES ("admin", "'.$password.'", "admin");';
    if(!$mysqli->query($sql)) { echo($mysqli->connect_error); }
    else { 
        echo '<h5 class="message">Database creato e aggiunto utente admin.<br>Per accedere come admin utilizzare le credenziali:<br> admin <br>    password</h5>';
    }
}