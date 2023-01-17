<?php
/**
 * Diese Datei enthält alle SQL Statements für die Tabelle "bewertung"
 */
function safe_bewertung($sternebewertung, $bemerkung, $benutzer_id,$gericht_id) {
        $link = connectdb();

        $sql = "INSERT INTO bewertung (bemerkung, sternebewertung, benutzer_id, gericht_id) VALUES ('$bemerkung','$sternebewertung','$benutzer_id','$gericht_id')";
        $result = mysqli_query($link, $sql);

        mysqli_close($link);
    }

    function bewertungen_von_einem_gericht($gericht_id){
        $link = connectdb();

        $sql = "SELECT bewertung.bewertungszeitpunkt, bewertung.bemerkung, bewertung.sternebewertung, bewertung.id, bewertung.benutzer_id, gericht.name 
                FROM bewertung 
                INNER JOIN gericht ON bewertung.gericht_id = gericht.id
                WHERE gericht_id = '$gericht_id'
                ORDER BY bewertungszeitpunkt DESC 
                LIMIT 30";
        $result = mysqli_query($link, $sql);
        $data = mysqli_fetch_all($result, MYSQLI_BOTH);

        mysqli_close($link);

        return $data;
    }


    function letzte_30($filter) {
        $link = connectdb();

        $sql = "SELECT bewertung.hervorheben, bewertung.bewertungszeitpunkt, bewertung.bemerkung, bewertung.sternebewertung, bewertung.id, bewertung.benutzer_id, gericht.name 
                FROM bewertung 
                INNER JOIN gericht ON bewertung.gericht_id = gericht.id
                where gericht.name like '%$filter%'
                ORDER BY bewertungszeitpunkt DESC 
                LIMIT 30";
        $result = mysqli_query($link, $sql);
        $data = mysqli_fetch_all($result, MYSQLI_BOTH);

        mysqli_close($link);

        return $data;
    }

    function bewertungen_benutzer($benutzerid, $filter) {
        $link = connectdb();

        $sql = "SELECT bewertung.benutzer_id, bewertung.bewertungszeitpunkt, bewertung.bemerkung, bewertung.sternebewertung, gericht.name 
                FROM bewertung 
                INNER JOIN gericht ON bewertung.gericht_id = gericht.id
                WHERE benutzer_id = '$benutzerid' and name LIKE '%$filter%'
                ORDER BY bewertungszeitpunkt DESC";

        $result = mysqli_query($link, $sql);
        $data = mysqli_fetch_all($result, MYSQLI_BOTH);

        mysqli_close($link);

        return $data;
    }

    function bewertung_loeschen($id) {
        $link = connectdb();

        $sql = "DELETE FROM bewertung WHERE id = '$id'";

        mysqli_query($link, $sql);

        mysqli_close($link);
    }

    function bewertungenHome(){
        $link = connectdb();

        $sql = "SELECT gericht.name,  bewertung.bemerkung, bewertung.sternebewertung 
                FROM bewertung 
                INNER JOIN gericht ON bewertung.gericht_id = gericht.id
                where hervorheben = 1
                ORDER BY bewertungszeitpunkt DESC";

        $result = mysqli_query($link, $sql);
        $data = mysqli_fetch_all($result, MYSQLI_NUM);

        mysqli_close($link);

        return $data;
    }

