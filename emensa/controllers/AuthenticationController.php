<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/authentification.php');

class AuthenticationController
{
    function anmeldung() {
        if($_SESSION['login_ok']) {
            header('Location: /home');
        }
        $msg = $_SESSION['login_result_message'] ?? null;
        return view('emensa.anmeldung_werbeseite', ['msg' => $msg]);
    }
    function abmelden(RequestData $rd, $logger) {

        // Monolog logger benutzer logged out
        $logger->info('User '.$_SESSION['benutzer_name'].' logged out.');
        session_unset();
        header('Location: /home');
    }

    function verifizierung(RequestData $rd, $logger) {
        $email = $rd->query['email'] ?? false;
        $password = $rd->query['password'] ?? false;
        // Überprüfung Eingabedaten

        $data = auth($password,$email);
        $_SESSION['login_result_message'] = "";
        if ( $data != null) {
            $_SESSION['login_ok'] = true;
            $_SESSION['cookie'] = $data['id'];
            $_SESSION['benutzer_name'] = $data['name'];

            // Monolog logger benutzer logged in
            $logger->info('User '.$data['name'].' logged in.');

            header('Location: /home');
        } else {
            // Monolog logger benutzer logged in
            $logger->warning('Email '.$email.' konnte nicht einloggen.');

            $_SESSION['login_result_message'] =
                'Name oder Passwort falsch';
            header('Location: /anmeldung');
        }
    }


}