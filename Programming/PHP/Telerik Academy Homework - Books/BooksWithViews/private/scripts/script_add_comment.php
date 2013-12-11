<?php
require 'base_path.php';
require $base_path.'private'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'file_locations.php';
require $base_path.$file_loc['config']['main'];

if ($is_logged) {
    $is_post_set = (isset($_POST) and isset($_POST['book_id']) and isset($_POST['comment_text']));
    if ($is_post_set) {
        $error = NULL;
        
        // Get user_id
        $username = trim($_SESSION['username']);
        $user_data = [
            'username' => $username,
        ];
        
        // Add comment
        $data['user_id'] = get_user_id_by_username($error, $db_conn, $user_data);
        $data['text'] = trim($_POST['comment_text']);
        $data['book_id'] = trim($_POST['book_id']);
        
        $success = add_comment($error, $db_conn, $data);
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

if (isset($_POST['book_id'])) {
    $send_to = $page_router['book'].'&id='.$_POST['book_id'];
}
else {
    $send_to = $page_router['main'];
}
redirect('public', $send_to);