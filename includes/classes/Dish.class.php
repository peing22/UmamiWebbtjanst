<?php
/* Av Petra Ingemarsson */

class Dish
{
    // Properties
    private $db;
    private $title;
    private $descript;
    private $price;
    private $category;

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

    // Get-metod för att hämta meny från databasen
    public function getMenu(): array
    {
        // SQL-fråga för att läsa ut data från databasen
        $sql = "SELECT * FROM menu ORDER BY category, title;";

        // Skickar SQL-fråga till servern och lagrar resultat av utläst data i en variabel
        $result = mysqli_query($this->db, $sql);

        // Returnerar data som en associativ array
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // Get-metod för att hämta specifik maträtt eller dryck från databasen
    public function getDish(int $id): array
    {
        // SQL-fråga för att läsa ut data från databasen
        $sql = "SELECT * FROM menu WHERE id=$id;";

        // Skickar SQL-fråga till servern och lagrar resultat av utläst data i en variabel
        $result = mysqli_query($this->db, $sql);

        // Returnerar data som en associativ array
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // Set-metod för att lägga till titel, beskrivning, pris och kategori
    public function setProperties(string $title, string $descript, int $price, string $category): bool
    {
        // Kontroll att värden är angivna
        if (mb_strlen($title) != 0 and mb_strlen($descript) != 0 and $price != 0 and mb_strlen($category) != 0) {

            // Saniterar angiven data och sätter värden för klassens properties
            $this->title = $this->db->real_escape_string($title);
            $this->descript = $this->db->real_escape_string($descript);
            $this->price = $price;
            $this->category = $this->db->real_escape_string($category);

            return true;
        } else {
            return false;
        }
    }

    // Metod för att lägga till maträtt eller dryck i databasen
    public function addDish(): bool
    {
        // SQL-fråga för att lägga till maträtt eller dryck
        $sql = "INSERT INTO menu(title, descript, price, category) VALUES('$this->title', '$this->descript', $this->price, '$this->category');";

        // Skickar SQL-fråga till servern och returnerar svaret
        return mysqli_query($this->db, $sql);
    }

    // Metod för att uppdatera maträtt eller dryck i databasen
    public function updateDish(int $id): bool
    {
        // SQL-fråga för att uppdatera maträtt eller dryck i databasen utifrån id
        $sql = "UPDATE menu SET title='" . $this->title . "', descript='" . $this->descript . "', price=$this->price, category='" . $this->category . "' WHERE id=$id;";

        // Skickar SQL-fråga till servern och returnerar svaret
        return mysqli_query($this->db, $sql);
    }

    // Metod för att radera maträtt eller dryck från databasen
    public function deleteDish(int $id): bool
    {
        // SQL-fråga för att radera maträtt eller dryck utifrån id
        $sql = "DELETE FROM menu WHERE id=$id;";

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
