<?php
require 'base_path.php';
require $base_path.'private'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'file_locations.php';
require $base_path.$file_loc['config']['main'];

if (!$is_logged) {
    $is_post_set = (isset($_POST) and isset($_POST['user_username']) and isset($_POST['user_password']));
    if ($is_post_set) {
        $username = trim($_POST['user_username']);
        $password = trim($_POST['user_password']);

        // Add user
        $data['username'] = $username;
        $data['password'] = $password;
        $error = NULL;

        $is_added = add_user($error, $db_conn, $data);
        if (isset($error)) {
            $send_to = $page_router["register"].'&error='.$error;
            redirect('public', $send_to);
        }
        
        if (!$is_added) {
            $error = 
                    'User not added. Probably already exists.
                    Might be yours, so try to login first, or type a new username.';
            $send_to = $page_router["register"].'&error='.$error;
            redirect('public', $send_to);
        }
        
        // Redirect for Login
        $send_to = $page_router["login"];
        redirect('public', $send_to);
    }
}

$send_to = $file_loc["page"]["main"];
redirect('public', $send_to);