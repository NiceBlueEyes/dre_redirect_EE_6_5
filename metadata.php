<?php

$sMetadataVersion = '2.0';
/**
 * Module information
 */
$aModule = array(
    'id' => 'dre_redirect',
    'title' => '<img src="../modules/bender/dre_redirect/out/img/favicon.ico" 
height="20px" title="Bender Gutschein Modul">Redirect (301)',
    'description' => 'mit Hilfe diese Modul können einfach 301 Redirects im Backend hinzugefügt werden.',
    'thumbnail' => 'out/img/logo_bodynova.png',
    'version' => '0.6.5',
    'author' => 'André Bender',
    'email' => 'support@bodynova.de',
    'url' => 'https://bodynova.de',
    'controllers' => array(
        'dre_redirect_controller' =>
            \Bender\dre_redirect\Application\Controller\Admin\dre_redirect_controller::class
    ),
    'templates' => array(
        'dre_redirect.tpl' => 'bender/dre_redirect/Application/views/admin/tpl/dre_redirect.tpl'
    ),
    'extend'      => [],
    'blocks'      => [],
    'settings'    => []
);
