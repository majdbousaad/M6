<?php
/**
 * Mapping of paths to controllers.
 * Note, that the path only supports one level of directory depth:
 *     /demo is ok,
 *     /demo/subpage will not work as expected
 */

return array(
    '/'             => "HomeController@index",
    "/demo"         => "DemoController@demo",
    '/dbconnect'    => 'DemoController@dbconnect',
    '/debug'        => 'HomeController@debug',
    '/error'        => 'DemoController@error',
    '/requestdata'   => 'DemoController@requestdata',

    // Erstes Beispiel:
    '/m4_7a_queryparameter' => 'ExampleController@m4_7a_queryparameter',
    '/m4' => 'ExampleController@m4_6a_queryparameter',
    '/m4_7b_kategorie' => 'ExampleController@m4_7b_kategorie',
    '/m4_7c_gerichte' => 'ExampleController@m4_7c_gerichte',
    '/m4_7d_layout' => 'ExampleController@m4_7d_layout',
    '/emensa' => 'HomeController@emensa',
    '/newsletter' => 'HomeController@newsletter',
    '/home' => 'HomeController@home',
    '/anmeldung' => 'AuthenticationController@anmeldung',
    '/verifizierung' => 'AuthenticationController@verifizierung',
    '/profile' => 'HomeController@profile',
    '/abmelden' => 'AuthenticationController@abmelden'
);