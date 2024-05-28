<?php
/* Av Petra Ingemarsson */

// Inkluderar klassfil(er) i övriga PHP-filer
spl_autoload_register(function ($class_name) {
    include 'classes/' . $class_name . '.class.php';
});

// Utvecklarläge eller inte
$devmode = true;

// Om utvecklarläge
if ($devmode) {

    // Aktiverar felrapportering
    error_reporting(-1);
    ini_set("display_errors", 1);

    // Lokala databasinställningar för anslutning till databas
    define("DBHOST", "localhost");
    define("DBUSER", "root");
    define("DBPASS", "");
    define("DBDATABASE", "projekt_dt173g");
} else {
    // Databasinställningar för anslutning till databas externt (ändra "#" till rätt uppgifter)
    define("DBHOST", "studentmysql.miun.se");
    define("DBUSER", "pein2200");
    define("DBPASS", "#");
    define("DBDATABASE", "pein2200");
}
