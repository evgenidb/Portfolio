<?php
$host = 'localhost';
$dbUser = 'root';
$dbPassword = '';
$database = 'books_extended';

$db_conn = mysqli_connect($host, $dbUser, $dbPassword, $database);

if (!$db_conn) {
    display_fatal_error('Cannot connect to the database!');
    exit();
}

mysqli_set_charset($db_conn, 'utf8');