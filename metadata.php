<?php

$sMetadataVersion = '2.0';
/**
 * Module information
 */
$aModule = array(
    'id' => 'dre_redirect',
    'title' => 'Redirect (301) Adder',
    'description' => 'Redirect (301) Adder - mit Hilfe diese Modul können einfach 301 Redirects im Backend hinzugefügt werden. Diese Modul kann bei URL Umstellungen (Artikel, Kategorien) in kleinem Umfang behilflich sein.',
    'thumbnail' => '',
    'version' => '0.6.5',
    'author' => 'André Bender',
    'email' => 'support@bodynova.de',
    'url' => 'https://bodynova.de',
    'extend' => array(),
    'controllers' => array(   
        'dre_redirect' => Bender\dre_Redirect\Admin\Controller\dre_redirect::class
    ), 
    'templates' => array(
        'dre_redirect.tpl' => 'Bender/dre_redirect/views/admin/tpl/dre_redirect.tpl'
    )
);
