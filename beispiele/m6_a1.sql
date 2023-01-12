USE emensawerbeseite;
CREATE TABLE bewertung (
                           id int(20) not null auto_increment primary key,
                           bemerkung varchar(500),
                           sternebewertung varchar(20),
                           bewertungszeitpunkt datetime DEFAULT current_timestamp,
                           benutzer_id bigint(20) not null,
                           CHECK ( sternebewertung = 'sehr gut' OR 'gut' OR 'schlecht' OR 'sehr schlecht'),
                           CHECK ( LENGTH(bemerkung) >= 5 )
);

ALTER TABLE bewertung
    ADD FOREIGN KEY (benutzer_id) REFERENCES benutzer(id);

