create table allergen
(
    code char(4)                        not null
        primary key,
    name varchar(300)                   not null comment 'Name des Allergens, wie „Glutenhaltiges Getreide“.',
    typ  varchar(20) default 'allergen' not null comment 'Gibt den Typ an. Standard: „allergen“'
);

create table benutzer
(
    id                bigint auto_increment
        primary key,
    name              varchar(200)         not null,
    email             varchar(100)         not null,
    passwort          varchar(200)         not null,
    admin             tinyint(1) default 0 not null,
    anzahlfehler      int        default 0 not null,
    anzahlanmeldungen int                  not null,
    letzteanmeldung   datetime             null,
    letzterfehler     datetime             null,
    constraint email
        unique (email)
);

create table besucher
(
    views int not null
);

create table gericht
(
    id           bigint auto_increment comment 'Primärschlüssel'
        primary key,
    name         varchar(80)          not null comment 'Name des Gerichts. Ein Name ist eindeutig.',
    beschreibung varchar(800)         not null comment 'Beschreibung des Gerichts.',
    erfasst_am   date                 not null comment 'Zeitpunkt der ersten Erfassung des Gerichts.',
    vegetarisch  tinyint(1) default 0 not null comment 'Markierung, ob das Gericht vegetarisch ist. Standard: Nein.',
    vegan        tinyint(1) default 0 not null comment 'Markierung, ob das Gericht vegan ist. Standard: Nein.',
    preis_intern double               not null comment 'Preis für interne Personen (wie Studierende). Es gilt immer preis_intern > 0.',
    preis_extern double               not null comment 'Preis für externe Personen (wie Gastdozent:innen).',
    bildname     varchar(200)         null comment 'Name der Bilddatei, die das Gericht darstellt.
Standard: null.',
    constraint `Preis_intern_bed.`
        check (`preis_intern` > 0),
    constraint `preis_extern_bed.`
        check (`preis_extern` >= `preis_intern`)
);

create table bewertung
(
    id                  int(20) auto_increment
        primary key,
    bemerkung           varchar(500)                           null,
    sternebewertung     varchar(20)                            null,
    bewertungszeitpunkt datetime   default current_timestamp() null,
    benutzer_id         bigint                                 not null,
    gericht_id          bigint                                 not null,
    hervorheben         tinyint(1) default 0                   null,
    constraint bewertung_ibfk_1
        foreign key (benutzer_id) references benutzer (id),
    constraint bewertung_ibfk_2
        foreign key (gericht_id) references gericht (id),
    check (`sternebewertung` = 'sehr gut' or `sternebewertung` = 'gut' or `sternebewertung` = 'schlecht' or
           `sternebewertung` = 'sehr schlecht'),
    check (octet_length(`bemerkung`) >= 5)
);

create index benutzer_id
    on bewertung (benutzer_id);

create index gericht_id
    on bewertung (gericht_id);

create index gericht_name_index
    on gericht (name);

create table gericht_hat_allergen
(
    code       char(4) null comment 'Referenz auf Allergen.',
    gericht_id bigint  not null comment 'Referenz auf das Gericht.',
    constraint allergen_fk
        foreign key (code) references allergen (code)
            on update cascade,
    constraint gericht_fk
        foreign key (gericht_id) references gericht (id)
            on delete cascade
);

create table kategorie
(
    id        bigint auto_increment comment 'Primärschlüssel'
        primary key,
    eltern_id bigint       null,
    name      varchar(80)  not null comment 'Name der Kategorie, z.B. „Hauptgericht“, „Vorspeise“, „Salat“, „Sauce“ oder „Käsegericht“.',
    bildname  varchar(200) null comment 'Name der Bilddatei, die eine Darstellung der Kategorie enthält.',
    constraint eltern_id_fk
        foreign key (eltern_id) references kategorie (id)
);

create table gericht_hat_kategorie
(
    gericht_id   bigint not null comment 'Referenz auf Gericht.',
    kategorie_id bigint not null comment 'Referenz auf Kategorie.',
    primary key (kategorie_id, gericht_id),
    constraint UC_Gericht_Kategorie
        unique (gericht_id, kategorie_id),
    constraint gericht_id_fk
        foreign key (gericht_id) references gericht (id)
            on delete cascade,
    constraint kategorie_id_fk
        foreign key (kategorie_id) references kategorie (id)
);

create table newsletter
(
    id      int auto_increment
        primary key,
    name    varchar(10) not null,
    email   varchar(50) not null,
    sprache varchar(1)  not null
);

create table wunschgericht
(
    id               bigint auto_increment
        primary key,
    name             varchar(20)                           not null,
    Beschreibung     varchar(400)                          null,
    erstellungsdatum timestamp default current_timestamp() not null on update current_timestamp(),
    ersteller_name   varchar(20)                           not null,
    ersteller_email  varchar(300)                          null
);

create definer = root@localhost view view_anmeldungen as
select `emensawerbeseite`.`benutzer`.`anzahlanmeldungen` AS `anzahlanmeldungen`
from `emensawerbeseite`.`benutzer`
order by `emensawerbeseite`.`benutzer`.`anzahlanmeldungen` desc;

create definer = root@localhost view view_kategoriegerichte_vegetarisch as
select `g`.`id`           AS `gericht_id`,
       `g`.`name`         AS `gericht_name`,
       `g`.`beschreibung` AS `gericht_beschreibung`,
       `g`.`vegetarisch`  AS `vegetarisch`,
       `k`.`id`           AS `kategorie_id`,
       `k`.`name`         AS `kategorie_name`,
       `k`.`eltern_id`    AS `eltern_id`
from (`emensawerbeseite`.`kategorie` `k` left join (`emensawerbeseite`.`gericht_hat_kategorie` `ghk` left join `emensawerbeseite`.`gericht` `g`
                                                    on (`g`.`id` = `ghk`.`gericht_id`))
      on (`k`.`id` = `ghk`.`kategorie_id`))
where `g`.`vegetarisch` = 1
   or `g`.`vegetarisch` is null;

-- comment on column view_kategoriegerichte_vegetarisch.gericht_id not supported: Primärschlüssel

-- comment on column view_kategoriegerichte_vegetarisch.gericht_name not supported: Name des Gerichts. Ein Name ist eindeutig.

-- comment on column view_kategoriegerichte_vegetarisch.gericht_beschreibung not supported: Beschreibung des Gerichts.

-- comment on column view_kategoriegerichte_vegetarisch.vegetarisch not supported: Markierung, ob das Gericht vegetarisch ist. Standard: Nein.

-- comment on column view_kategoriegerichte_vegetarisch.kategorie_id not supported: Primärschlüssel

-- comment on column view_kategoriegerichte_vegetarisch.kategorie_name not supported: Name der Kategorie, z.B. „Hauptgericht“, „Vorspeise“, „Salat“, „Sauce“ oder „Käsegericht“.

create definer = root@localhost view view_suppengerichte as
select `emensawerbeseite`.`gericht`.`id`           AS `id`,
       `emensawerbeseite`.`gericht`.`name`         AS `name`,
       `emensawerbeseite`.`gericht`.`beschreibung` AS `beschreibung`,
       `emensawerbeseite`.`gericht`.`erfasst_am`   AS `erfasst_am`,
       `emensawerbeseite`.`gericht`.`vegetarisch`  AS `vegetarisch`,
       `emensawerbeseite`.`gericht`.`vegan`        AS `vegan`,
       `emensawerbeseite`.`gericht`.`preis_intern` AS `preis_intern`,
       `emensawerbeseite`.`gericht`.`preis_extern` AS `preis_extern`,
       `emensawerbeseite`.`gericht`.`bildname`     AS `bildname`
from `emensawerbeseite`.`gericht`
where `emensawerbeseite`.`gericht`.`name` like '%suppe%';

-- comment on column view_suppengerichte.id not supported: Primärschlüssel

-- comment on column view_suppengerichte.name not supported: Name des Gerichts. Ein Name ist eindeutig.

-- comment on column view_suppengerichte.beschreibung not supported: Beschreibung des Gerichts.

-- comment on column view_suppengerichte.erfasst_am not supported: Zeitpunkt der ersten Erfassung des Gerichts.

-- comment on column view_suppengerichte.vegetarisch not supported: Markierung, ob das Gericht vegetarisch ist. Standard: Nein.

-- comment on column view_suppengerichte.vegan not supported: Markierung, ob das Gericht vegan ist. Standard: Nein.

-- comment on column view_suppengerichte.preis_intern not supported: Preis für interne Personen (wie Studierende). Es gilt immer preis_intern > 0.

-- comment on column view_suppengerichte.preis_extern not supported: Preis für externe Personen (wie Gastdozent:innen).

-- comment on column view_suppengerichte.bildname not supported: Name der Bilddatei, die das Gericht darstellt.
Standard
:
null.

create
    definer = root@localhost procedure inkrement_anzahl_anmeldungen(IN benutzer_id int)
BEGIN
    UPDATE benutzer SET anzahlanmeldungen = anzahlanmeldungen +1 WHERE id = benutzer_id;
END;

