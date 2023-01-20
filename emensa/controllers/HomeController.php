<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/gericht.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/zahlen.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/newsletter.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/authentification.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/benutzer.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/bewertung.php');


/* Datei: controllers/HomeController.php */
class HomeController
{
    public function index(RequestData $request) {
        return view('home', ['rd' => $request ]);
    }
    
    public function debug(RequestData $request) {
        return view('debug');
    }

    public function emensa(RequestData  $rd){
        update_besucher();
        $gerichte = zufaellige_gerichte();
        $allerge_codes = codes_from_zufaellige_gerichte($gerichte);

        $zahlen_gerichte = db_zahlen_gerichte();
        $zahlen_anmeldungen = db_zahlen_anmeldungen();
        $zahlen_besucher = db_zahlen_besucher();


        return view('emensa.index', [
            'rd' => $rd,
            'gerichte' => $gerichte,
            'allerge_codes' => $allerge_codes,
            'zahlen_gerichte' => $zahlen_gerichte,
            'zahlen_anmeldungen' => $zahlen_anmeldungen,
            'zahlen_besucher' => $zahlen_besucher
        ]);
    }
    public function newsletter(RequestData $rd){

        $msgs = anmelden($rd);
        $erfolgreich = false;
        if(count($msgs) == 0){
            $erfolgreich =true;
        }

        $gerichte = zufaellige_gerichte();
        $allerge_codes = codes_from_zufaellige_gerichte($gerichte);

        $zahlen_gerichte = db_zahlen_gerichte();
        $zahlen_anmeldungen = db_zahlen_anmeldungen();
        $zahlen_besucher = db_zahlen_besucher();


        return view('emensa.index', [
            'rd' => $rd,
            'erfolgreich' => $erfolgreich,
            'msgs' => $msgs,
            'gerichte' => $gerichte,
            'allerge_codes' => $allerge_codes,
            'zahlen_gerichte' => $zahlen_gerichte,
            'zahlen_anmeldungen' => $zahlen_anmeldungen,
            'zahlen_besucher' => $zahlen_besucher
        ]);
    }

    function home(RequestData $rd, $logger) {

        // Monolog logger neuer Zugriff
        $logger->info("neuer Zugriff auf Homepage");


        $gerichte = zufaellige_gerichte();
        $allerge_codes = codes_from_zufaellige_gerichte($gerichte);

        $zahlen_gerichte = db_zahlen_gerichte();
        $zahlen_anmeldungen = db_zahlen_anmeldungen();
        $zahlen_besucher = db_zahlen_besucher();

        $bewertungen = bewertungenHome();
        return view('emensa.index', [
            'rd' => $rd,
            'gerichte' => $gerichte,
            'allerge_codes' => $allerge_codes,
            'zahlen_gerichte' => $zahlen_gerichte,
            'zahlen_anmeldungen' => $zahlen_anmeldungen,
            'zahlen_besucher' => $zahlen_besucher,
            'bewertungen' => $bewertungen
        ]);

    }



    public function profile(){

        if(isset($_SESSION["cookie"])){
            $benutzer = benutzer_select($_SESSION["cookie"]);

            return view("emensa.profile", ['benutzer' => $benutzer]);
        } else{
            header('Location: /home');
        }
    }

    function bewertung() {
        if (!isset($_SESSION['login_ok']) || !$_SESSION['login_ok']) {
            $_SESSION['target'] = '/bewertung?gerichtid='.$_GET["gerichtid"];
            header('Location: /anmeldung');
        }
        else {
            $id = $_GET['gerichtid'];
            $data = gericht_bewertung($id);
            $_SESSION['gerichtid'] = $id;
            $bewertungen = bewertungen_von_einem_gericht($id);

            return view('emensa.bewertung',[
                'gerichtid' => $id,
                'name' => $data['name'],
                'bildname' => $data['bildname'],
                'bewertungen' => $bewertungen
            ]);
        }
    }

    function bewertung_verarbeitung(RequestData $rd) {
        $bemerkung = $rd->query['bemerkung'];
        $sternebewertung = $rd->query['sternebewertung'];

        if (strlen($bemerkung) < 5) {
            $_SESSION['error_message'] =
                'Die Bemerkung muss mindestens 5 Zeichen lang sein.';
            header('Location: /bewertung?gerichtid='.$_GET['gerichtid']);
        }
        else {
            safe_bewertung($sternebewertung,$bemerkung,$_SESSION['cookie'],$_SESSION['gerichtid']);
            header('Location: /bewertung?gerichtid='.$_GET["gerichtid"]);
        }
    }

    function meinebewertungen() {
        $benutzer_id = $_SESSION['benutzer_id'];
        $_SESSION["target"] = '/meinebewertungen';
        $filter = $_GET["gerichtsfilter"] ?? "";
        $data = bewertungen_benutzer($benutzer_id, $filter);
        return view('emensa.meinebewertungen',[
            'bewertungen' => $data
        ]);
    }

    function bewertungloeschen() {
        $id = $_GET['berwertungsid'];
        echo $id;
        bewertung_loeschen($id);
        header("Location: " . $_SESSION["target"]);
    }

    function bewertungen() {
        $_SESSION["target"] = '/bewertungen';
        $benutzer_id = $_SESSION['benutzer_id'];
        echo $benutzer_id;
        $filter = null;
        if(isset($_GET["gerichtsfilter"])){
            $filter = $_GET["gerichtsfilter"];
        }else{
            $filter = "";

        }


        $data = letzte_30($filter);
        return view('emensa.meinebewertungen',[
            'bewertungen' => $data
        ]);
    }


    function hervorheben(RequestData $rd){

        $bewertungsid = $rd->query["berwertungsid"];
        $wert = $rd->query["wert"];

        $link = connectdb();
        mysqli_query($link, "Update bewertung set hervorheben = '$wert' where id = '$bewertungsid'");

        $data = letzte_30("");
        return view('emensa.meinebewertungen',[
            'bewertungen' => $data
        ]);

    }


}