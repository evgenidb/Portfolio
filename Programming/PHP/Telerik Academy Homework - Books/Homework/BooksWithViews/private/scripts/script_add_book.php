<?php
require 'base_path.php';
require $base_path.'private'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'file_locations.php';
require $base_path.$file_loc['config']['main'];

if ($is_logged) {
    $is_post_set = (isset($_POST) and isset($_POST['book_title']) and isset($_POST['book_authors']));
    if ($is_post_set) {
        $book_title = trim($_POST['book_title']);
        $authors = [];
        foreach ($_POST['book_authors'] as $author_id) {
            $authors[] = trim($author_id);
        }

        // Add book
        $data['title'] = $book_title;
        $data['author_ids'] = $authors;
        $error = NULL;
        
        $success = add_book($error, $db_conn, $data);
        if (isset($error)) {
            $send_to = $page_router['error'].'&error='.$error;
            redirect('public', $send_to);
        }
        if (!$success) {
            $error = 'ERROR! No or partial input.';
            $send_to = $page_router['error'].'&error='.$error;
            redirect('public', $send_to);
        }
    }
}
 else {
    $send_to = $page_router['login'];
    redirect('public', $send_to);
}

$send_to = $page_router['add_book'].'&added=yes';
redirect('public', $send_to);