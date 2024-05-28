<?php
/* Av Petra Ingemarsson */

// Inkluderar config-fil
include("includes/config.php");

// Headers med inställningar för REST-webbtjänsten
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; utf-8;');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Lagrar API-nyckel i variabel
$apikey = "QOat53BjU09GgFLx3h11kJBdYQP84Fc4";

// Om API-nyckel saknas
if (!isset($_SERVER['HTTP_APIKEY'])) {

    // Lagrar ett meddelande som sedan skickas tillbaka till anroparen
    $response = array("message" => "API-nyckel, tack");

    // Skickar med statuskod "Unauthorized"
    http_response_code(401);

    // Om API-nyckel är felaktig
} elseif (isset($_SERVER['HTTP_APIKEY']) and $_SERVER['HTTP_APIKEY'] != $apikey) {

    // Lagrar ett meddelande som sedan skickas tillbaka till anroparen
    $response = array("message" => "Felaktig API-nyckel");

    // Skickar med statuskod "Unauthorized"
    http_response_code(401);

    // Om API-nyckel är korrekt
} else {

    // Skapar instans av klass
    $admin = new Admin();

    // Lagrar respons från get-metod i variabel
    $response = $admin->getAdmin();

    // Om responsen är utan innehåll
    if (count($response) === 0) {

        // Lagrar ett meddelande som sedan skickas tillbaka till anroparen
        $response = array("message" => "Användaren kunde inte hittas i databasen");

        // Skickar med statuskod "Not found"
        http_response_code(404);
    } else {
        // Om responsen har innehåll skickas statuskod "OK"
        http_response_code(200);
    }
}

// Skickar tillbaka respons till anroparen
echo json_encode($response);
