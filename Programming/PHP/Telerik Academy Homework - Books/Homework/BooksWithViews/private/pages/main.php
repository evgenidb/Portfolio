<?php
require 'base_path.php';

$data = [];
$data['title'] = 'Main';

if (IS_DEBUG_MODE) {
    $data['nav'] = $base_path . $file_loc['html_snippet']['nav']['main_logged'];
    
    $data['link']['add_book'] = $page_router['add_book'];
    $data['link']['add_author'] = $page_router['add_author'];
}

$error = NULL;
$order_by = 'asc';
$data['books'] = get_books_and_their_authors($error, $db_conn, $order_by);
if ($error) {
    require $base_path . $file_loc['page']['error'];
    exit;
}

$data['is_logged'] = $is_logged;
if ($is_logged) {
    $data['nav'] = $base_path . $file_loc['html_snippet']['nav_logged']['main'];
    $data['login'] = $base_path . $file_loc['html_snippet']['login']['logged'];
    
    $data['link']['logout'] = $page_router['logout'];
    
    $data['link']['add_book'] = $page_router['add_book'];
    $data['link']['add_author'] = $page_router['add_author'];
} else {
    $data['nav'] = $base_path . $file_loc['html_snippet']['nav']['main'];
    
    $data['login'] = $base_path . $file_loc['html_snippet']['login']['not_logged'];
    
    $data['link']['login'] = $page_router['login'];
    $data['link']['register'] = $page_router['register'];
}

if (count($data['books']) > 0) {
    $data['content'] = $base_path . $file_loc['html_snippet']['table']['books'];
} else {
    $data['content'] = $base_path . $file_loc['html_snippet']['table']['no_books'];
}
$data['link']['book'] = $page_router['book'];
$data['link']['author'] = $page_router['author'];

$template_path = $base_path . $file_loc['layout']['normal_no_footer'];

render($data, $template_path);