<?php

error_reporting(0);

$server = mysql_connect('localhost', 'root', 'mangaps');

if ($server) {
    $serverNote = 'Sambungan Server Ok dan ';

    $database = mysql_select_db('jny_archive');

    if ($database) {

        $databaseNote = 'Terkoneksi dengan Database';
    } else {
        $databaseNote = 'Tidak Terkoneksi dengan Database';
    }
} else {
    $serverNote = 'Sambungan Server Tidak Terhubung dan ';
}
?>