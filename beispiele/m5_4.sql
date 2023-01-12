

# a
CREATE VIEW IF NOT EXISTS view_suppengerichte AS
    SELECT * FROM gericht WHERE name LIKE '%suppe%';

SELECT * FROM view_suppengerichte;

# b
CREATE VIEW IF NOT EXISTS view_anmeldungen AS
    SELECT anzahlanmeldungen FROM benutzer
    ORDER BY anzahlanmeldungen DESC ;

SELECT * FROM view_anmeldungen;


# c
CREATE VIEW IF NOT EXISTS view_kategoriegerichte_vegetarisch AS
    SELECT G.id as gericht_id,
           G.name as gericht_name,
           g.beschreibung as gericht_beschreibung,
           g.vegetarisch,
           K.id as kategorie_id,
           K.name as kategorie_name,
           K.eltern_id
    FROM gericht as G

         right join gericht_hat_kategorie GHK on G.id = GHK.gericht_id
         right join kategorie K on K.id = ghk.kategorie_id
          WHERE G.vegetarisch = 1 OR  G.vegetarisch is null;


SELECT * FROM view_kategoriegerichte_vegetarisch;


#d

