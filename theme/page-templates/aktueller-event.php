<?php
/*
Template Name: Aktueller Event
*/

setlocale(LC_TIME, 'de_DE.UTF-8', 'deu_deu');
define( 'TWOHOURS', 7200 );

$args = [
    'headers' => [
        'method' => 'GET',
    ]
];

get_header();



get_footer();
?>
