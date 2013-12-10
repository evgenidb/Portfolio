<?php
$base_path = '../';
require $base_path.'private/data/file_locations.php';
require $base_path.$file_loc['config']['main'];

$error = NULL;
$page = '';
if (isset($_GET['page'])) {
    switch ($_GET['page']) {
        case 'main':
            $page = 'main';
            break;
        case 'add_author':
            $page = 'add_author';
            break;
        case 'add_book':
            $page = 'add_book';
            break;
        case 'author':
            $page = 'author';
            break;
        case 'book':
            $page = 'book';
            break;
        case 'login':
            $page = 'login';
            break;
        case 'logout':
            $page = 'logout';
            break;
        case 'register':
            $page = 'register';
            break;
        case 'error':
            $error = 'Unknown error!';
            $page = 'error';
            break;
        default:
            $error = 'Page does not exist!';
            $page = 'error';
            break;
    }
} else {
    $page = 'main';
}


require $base_path.$file_loc['page'][$page];