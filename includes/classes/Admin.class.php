<?php
/* Av Petra Ingemarsson */

class Admin
{
    // Property
    private $db;

    // Konstruerare med anslutning mot databas
    public function __construct()
    {
        // Ansluter till databas
        $this->db = new mysqli(DBHOST, DBUSER, DBPASS, DBDATABASE);

        // Kontroll om fel vid anslutning
        if ($this->db->connect_errno > 0) {

            // Felmeddelande
            die("Fel vid anslutning: " . $this->db->connect_error);
        }
    }

    // Get-metod för att hämta admin-uppgifter från databasen
    public function getAdmin(): array
    {
        // SQL-fråga för att läsa ut data från databasen
        $sql = "SELECT * FROM admin;";

        // Skickar SQL-fråga till servern och lagrar resultat av utläst data i en variabel
        $result = mysqli_query($this->db, $sql);

        // Returnerar data som en associativ array
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // Destruerare för att stänga anslutning till databas
    public function __destruct()
    {
        // Stänger anslutning
        mysqli_close($this->db);
    }
}
