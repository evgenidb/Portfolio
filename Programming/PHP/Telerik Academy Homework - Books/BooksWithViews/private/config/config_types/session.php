<?php
$is_logged = continue_session();


// Session Functions
function begin_session($username) {
    $is_in_session = FALSE;
    
    if (isset($username)) {
        session_start();
        $_SESSION['username'] = $username;

        $is_in_session = TRUE;
    }
    
    return $is_in_session;
}

function continue_session() {
    $is_in_session = FALSE;
    session_start();
    
    if (isset($_SESSION['username'])) {
        $is_in_session = TRUE;
    }
    
    return $is_in_session;
}

function end_session() {
    $is_in_session = continue_session();
    
    if ($is_in_session) {
        // Unset all of the session variables.
        $_SESSION = array();

        session_destroy();
        
        $is_in_session = FALSE;
    }
    
    return $is_in_session;
}