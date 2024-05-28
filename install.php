<?php
/* Av Petra Ingemarsson */

// Inkluderar config-fil
include("includes/config.php");

// Om utvecklarläge
if ($devmode) {

    // Ansluter till localhost
    $db = new mysqli("localhost", "root", "", "mysql");

    // Kontroll om fel vid anslutning
    if ($db->connect_errno > 0) {

        // Skriver ut felmeddelande
        die("Fel vid anslutning: " . $db->connect_error);
    }

    // Skapar databas om den inte redan existerar
    $sql = "CREATE DATABASE IF NOT EXISTS projekt_dt173g;";

    // Skriver ut SQL-fråga till skärmen
    echo "<pre>$sql</pre>";

    // Skickar SQL-fråga till servern
    if ($db->multi_query($sql)) {
        echo "Databas installerad!";
    } else {
        echo "Fel vid installation av databas!";
    }
    // Stänger anslutning
    $db->close();
}

// Ansluter till databas
$db = new mysqli(DBHOST, DBUSER, DBPASS, DBDATABASE);

// Kontroll om fel vid anslutning
if ($db->connect_errno > 0) {

    // Skriver ut felmeddelande
    die("Fel vid anslutning: " . $db->connect_error);
}

// Tar bort tabeller om de existerar
$sql = "DROP TABLE IF EXISTS menu, reservation, presentation, admin;";

// Skapar tabellen menu
$sql .= "CREATE TABLE menu(
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
    descript TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
    price INT(11) NOT NULL,
    category VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL
);";

// Skapar tabellen reservation
$sql .= "CREATE TABLE reservation(
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    resname VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
    resphone VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
    resdate VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
    restime VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
    resquantity INT(11) NOT NULL
);";

// Skapar tabellen presentation
$sql .= "CREATE TABLE presentation(
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    paragraph INT(11) NOT NULL,
    content TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL
);";

// Skapar tabellen admin
$sql .= "CREATE TABLE admin(
    username VARCHAR(30) NOT NULL PRIMARY KEY,
    password VARCHAR(256) NOT NULL
);";

// Hashar adminlösenord
$hashedPassword = password_hash('cykelställ159', PASSWORD_DEFAULT);

// SQL-fråga för att skapa adminkonto
$sql .= "INSERT INTO admin(username, password) VALUES('admin', '$hashedPassword');";

// Skriver ut SQL-frågor till skärmen
echo "<pre>$sql</pre>";

// Skickar SQL-frågor till servern
if ($db->multi_query($sql)) {
    echo "Installation av tabeller och insättning av admin har lyckats!";
} else {
    echo "Fel vid installation av tabeller och/eller insättning av admin!";
}
