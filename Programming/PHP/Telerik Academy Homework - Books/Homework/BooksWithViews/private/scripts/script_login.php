<?php
require 'base_path.php';
require $base_path.'private'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'file_locations.php';
require $base_path.$file_loc['config']['main'];

if (!$is_logged) {
    $is_post_set = (isset($_POST) and isset($_POST['user_username']) and isset($_POST['user_password']));
    if ($is_post_set) {
        $username = trim($_POST['user_username']);
        $password = trim($_POST['user_password']);
        
        // Check for user
        $data['username'] = $username;
        $data['password'] = $password;
        $error = NULL;

        $user = get_user_id_by_data($error, $db_conn, $data);
        if (isset($error)) {
            $send_to = $page_router['error'].'&error='.$error;
            redirect('public', $send_to);
        }
        
        if (count($user) === 1) {
            // Login (begin session!)
            begin_session($username);
        }
        else {
            $error = 'Wrong username or password';
            $send_to = $page_router['login'].'&error='.$error;
            redirect('public', $send_to);
        }
    }
}

$send_to = $page_router['main'];
redirect('public', $send_to);