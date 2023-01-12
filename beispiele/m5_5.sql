

CREATE PROCEDURE inkrement_anzahl_anmeldungen(IN benutzer_id INT)
BEGIN
    UPDATE benutzer SET anzahlanmeldungen = anzahlanmeldungen +1 WHERE id = benutzer_id;
END