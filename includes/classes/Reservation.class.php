<?php
/* Av Petra Ingemarsson */

class Reservation
{
    // Properties
    private $db;
    private $resname;
    private $resphone;
    private $resdate;
    private $restime;
    private $resquantity;

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

    // Get-metod för att hämta bokningar från databasen
    public function getReservations(): array
    {
        // SQL-fråga för att läsa ut data från databasen
        $sql = "SELECT * FROM reservation ORDER BY resdate, restime;";

        // Skickar SQL-fråga till servern och lagrar resultat av utläst data i en variabel
        $result = mysqli_query($this->db, $sql);

        // Returnerar data som en associativ array
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // Get-metod för att hämta specifik bokning från databasen
    public function getReservation(int $id): array
    {
        // SQL-fråga för att läsa ut data från databasen
        $sql = "SELECT * FROM reservation WHERE id=$id;";

        // Skickar SQL-fråga till servern och lagrar resultat av utläst data i en variabel
        $result = mysqli_query($this->db, $sql);

        // Returnerar data som en associativ array
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // Set-metod för att lägga till namn, telefonnummer, datum, tid och antal personer
    public function setProperties(string $resname, string $resphone, string $resdate, string $restime, int $resquantity): bool
    {
        // Kontroll att värden är angivna
        if (mb_strlen($resname) != 0 and mb_strlen($resphone) != 0 and mb_strlen($resdate) != 0 and mb_strlen($restime) != 0 and $resquantity != 0) {

            // Saniterar angiven data och sätter värden för klassens properties
            $this->resname = $this->db->real_escape_string($resname);
            $this->resphone = $this->db->real_escape_string($resphone);
            $this->resdate = $this->db->real_escape_string($resdate);
            $this->restime = $this->db->real_escape_string($restime);
            $this->resquantity = $resquantity;

            return true;
        } else {
            return false;
        }
    }

    // Metod för att lägga till bokning i databasen
    public function addReservation(): bool
    {
        // SQL-fråga för att lägga till bokning
        $sql = "INSERT INTO reservation(resname, resphone, resdate, restime, resquantity) VALUES('$this->resname', '$this->resphone', '$this->resdate', '$this->restime', $this->resquantity);";

        // Skickar SQL-fråga till servern och returnerar svaret
        return mysqli_query($this->db, $sql);
    }

    // Metod för att uppdatera bokning i databasen
    public function updateReservation(int $id): bool
    {
        // SQL-fråga för att uppdatera bokning i databasen utifrån id
        $sql = "UPDATE reservation SET resname='" . $this->resname . "', resphone='" . $this->resphone . "', resdate='" . $this->resdate . "', restime='" . $this->restime . "', resquantity=$this->resquantity WHERE id=$id;";

        // Skickar SQL-fråga till servern och returnerar svaret
        return mysqli_query($this->db, $sql);
    }

    // Metod för att radera bokning från databasen
    public function deleteReservation(int $id): bool
    {
        // SQL-fråga för att radera bokning utifrån id
        $sql = "DELETE FROM reservation WHERE id=$id;";

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
