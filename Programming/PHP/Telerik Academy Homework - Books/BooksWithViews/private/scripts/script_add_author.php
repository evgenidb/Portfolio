<?php
require 'base_path.php';
require $base_path.'private'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'file_locations.php';
require $base_path.$file_loc['config']['main'];

if ($is_logged) {
    $is_post_set = isset($_POST) and isset($_POST['author_name']);
    if ($is_post_set) {
        $author_name = trim($_POST['author_name']);

        // Add author
        $data['name'] = $author_name;
        $error = NULL;

        add_author($error, $db_conn, $data);
        if (isset($error)) {
            $send_to = $page_router['error'].'&error='.$error;
            redirect('public', $send_to);
        }
    }
}
 else {
    $send_to = $page_router['login'];
    redirect('public', $send_to);
}

$send_to = $page_router['add_author'].'&added=yes';
redirect('public', $send_to);