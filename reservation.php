<?php
/* Av Petra Ingemarsson */

// Inkluderar config-fil
include("includes/config.php");

// Headers med inställningar för REST-webbtjänsten
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; utf-8;');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');
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

    // Läser in vilken metod som skickats och lagrar den i en variabel
    $method = $_SERVER['REQUEST_METHOD'];

    // Om en parameter av id finns i URL:en lagras den i en variabel
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    }

    // Skapar instans av klass
    $reservation = new Reservation();

    // Exekverar olika kodblock beroende på metod
    switch ($method) {

        case 'GET':

            // Om id är medskickat
            if (isset($id)) {

                // Lagrar respons från get-metod i variabel
                $response = $reservation->getReservation($id);
            } else {
                // Lagrar respons från get-metod i variabel
                $response = $reservation->getReservations();
            }
            // Om responsen är utan innehåll
            if (count($response) === 0) {

                // Lagrar ett meddelande som sedan skickas tillbaka till anroparen
                $response = array("message" => "Bokning(ar) kunde inte hittas i databasen");

                // Skickar med statuskod "Not found"
                http_response_code(404);
            } else {
                // Om responsen har innehåll skickas statuskod "OK"
                http_response_code(200);
            }
            break;

        case 'POST':

            // Läser in JSON-data skickad med anropet och omvandlar till ett objekt
            $data = json_decode(file_get_contents("php://input"), true);

            // Om set-metod returnerar true
            if ($reservation->setProperties($data["resname"], $data["resphone"], $data["resdate"], $data["restime"], $data["resquantity"])) {

                // Lägg till bokning i databas
                if ($reservation->addReservation()) {

                    // Lagrar ett meddelande som sedan skickas tillbaka till anroparen
                    $response = array("message" => "Bokning tillagd i databasen");

                    // Skickar med statuskod "Created"
                    http_response_code(201);
                } else {
                    // Om fel vid tillägg av bokning lagras annat meddelande
                    $response = array("message" => "Fel vid lagring av bokning i databas");

                    // Skickar med statuskod "Internal Server Error"
                    http_response_code(500);
                }
            } else {
                // Om set-metoden returnerar false
                $response = array("message" => "Värden måste skickas med");

                // Skickar med statuskod "Bad request"
                http_response_code(400);
            }
            break;

        case 'PUT':

            // Läser in JSON-data skickad med anropet och omvandlar till ett objekt
            $data = json_decode(file_get_contents("php://input"), true);

            // Om set-metod returnerar true
            if ($reservation->setProperties($data["resname"], $data["resphone"], $data["resdate"], $data["restime"], $data["resquantity"])) {

                // Om id är medskickat
                if (isset($id)) {

                    // Om metod för att uppdatera bokning returnerar true
                    if ($reservation->updateReservation($id)) {

                        // Lagrar ett meddelande som sedan skickas tillbaka till anroparen
                        $response = array("message" => "Bokningen har uppdaterats i databasen");

                        // Skickar med statuskod "OK"
                        http_response_code(200);
                    } else {
                        // Om metod för att uppdatera bokning returnerar false
                        $response = array("message" => "Fel vid uppdatering av bokning i databas");

                        // Skickar med statuskod "Internal Server Error"
                        http_response_code(500);
                    }
                } else {
                    // Om id saknas
                    $response = array("message" => "Inget ID medskickat");

                    // Skickar med statuskod "Bad request"
                    http_response_code(400);
                }
            } else {
                // Om set-metoden returnerar false
                $response = array("message" => "Värden måste skickas med");

                // Skickar med statuskod "Bad request"
                http_response_code(400);
            }
            break;

        case 'DELETE':

            // Om id är medskickat
            if (isset($id)) {

                // Om metod för att radera en bokning returnerar true
                if ($reservation->deleteReservation($id)) {

                    // Lagrar ett meddelande som sedan skickas tillbaka till anroparen
                    $response = array("message" => "Bokningen har raderats från databasen");

                    // Skickar med statuskod "OK"
                    http_response_code(200);
                } else {
                    // Om metod för att radera en bokning returnerar false
                    $response = array("message" => "Fel vid radering av bokning i databas");

                    // Skickar med statuskod "Internal Server Error"
                    http_response_code(500);
                }
            } else {
                // Om id saknas
                $response = array("message" => "Inget ID medskickat");

                // Skickar med statuskod "Bad request"
                http_response_code(400);
            }
            break;
    }
}

// Skickar tillbaka respons till anroparen
echo json_encode($response);
