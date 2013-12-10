<?php
// Navigation
function redirect($path, $file) {
    $serverAddress = 'http://localhost'.DIR_SEP.PROJ_NAME;

    if (!isset($path)) {
        $path = 'public';
    }
    header('Location: '.$serverAddress.DIR_SEP.$path.DIR_SEP.$file);
    exit;
}


// HTML Render
function render($data, $template_file){
    require $template_file;
}