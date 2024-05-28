<?php
/* Av Petra Ingemarsson */

class Paragraph
{
    // Properties
    private $db;
    private $paragraph;
    private $content;

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

    // Get-metod för att hämta stycken från databasen
    public function getParagraphs(): array
    {
        // SQL-fråga för att läsa ut data från databasen
        $sql = "SELECT * FROM presentation ORDER BY paragraph;";

        // Skickar SQL-fråga till servern och lagrar resultat av utläst data i en variabel
        $result = mysqli_query($this->db, $sql);

        // Returnerar data som en associativ array
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // Set-metod för att lägga till stycke och textinnehåll
    public function setProperties(int $paragraph, string $content): bool
    {
        // Kontroll att värden är angivna
        if ($paragraph != 0 and mb_strlen($content) != 0) {

            // Saniterar angiven strängdata och sätter värden för klassens properties
            $this->paragraph = $paragraph;
            $this->content = $this->db->real_escape_string($content);

            return true;
        } else {
            return false;
        }
    }

    // Metod för att lägga till stycke i databasen
    public function addParagraph(): bool
    {
        // SQL-fråga för att lägga till stycke
        $sql = "INSERT INTO presentation(paragraph, content) VALUES($this->paragraph, '$this->content');";

        // Skickar SQL-fråga till servern och returnerar svaret
        return mysqli_query($this->db, $sql);
    }

    // Metod för att uppdatera stycke i databasen
    public function updateParagraph(int $id): bool
    {
        // SQL-fråga för att uppdatera stycke i databasen utifrån id
        $sql = "UPDATE presentation SET paragraph=$this->paragraph, content='" . $this->content . "' WHERE id=$id;";

        // Skickar SQL-fråga till servern och returnerar svaret
        return mysqli_query($this->db, $sql);
    }

    // Metod för att radera stycke från databasen
    public function deleteParagraph(int $id): bool
    {
        // SQL-fråga för att radera stycke utifrån id
        $sql = "DELETE FROM presentation WHERE id=$id;";

        // Skickar SQL-fråga till servern och returnerar svaret
        return mysqli_query($this->db, $sql);
    }

    // Destruerare för att stänga anslutning till databas
    public function __destruct()
    {
        // Stänger anslutning
        mysqli_close($this->db);
    }
}
