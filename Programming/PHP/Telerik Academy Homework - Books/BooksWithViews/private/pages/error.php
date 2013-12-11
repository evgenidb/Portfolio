<?php
if (isset($_GET['error'])) {
    $error = trim($_GET['error']);
}

if (!isset($error)) {
    $error = 'Unknown error';
}

$data = [];
$data['title'] = 'Error';
$data['error_message'] = $error;

render($data, $file_loc['layout']['error']);