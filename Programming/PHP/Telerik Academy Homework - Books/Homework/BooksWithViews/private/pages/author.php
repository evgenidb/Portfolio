<?php
require 'base_path.php';

if (!isset($_GET['id'])) {
    $send_to = $page_router['main'];
    redirect('public', $send_to);
}

$error = NULL;

$data = [];
$author_id = trim($_GET['id']);
$author_id = intval($author_id, 10);

$order_by = 'asc';
$data['author'] = get_author_and_his_books_by_id($error, $db_conn, $author_id, $order_by);
if ($error) {
    require $base_path . $file_loc['page']['error'];
    exit;
}
if (IS_DEBUG_MODE) {
    var_dump($data);
    exit;
}

$data['title'] = 'Автор - '.$data['author'][$author_id]['name'];

$data['is_logged'] = $is_logged;
if ($is_logged) {
    $data['nav'] = $base_path . $file_loc['html_snippet']['nav_logged']['author'];
    $data['login'] = $base_path . $file_loc['html_snippet']['login']['logged'];
    
    $data['link']['logout'] = $page_router['logout'];
    
    $data['link']['add_book'] = $page_router['add_book'];
    $data['link']['add_author'] = $page_router['add_author'];
} else {
    $data['nav'] = $base_path . $file_loc['html_snippet']['nav']['author'];
    
    $data['login'] = $base_path . $file_loc['html_snippet']['login']['not_logged'];
    
    $data['link']['login'] = $page_router['login'];
    $data['link']['register'] = $page_router['register'];
}

$data['books'] = $data['author'][$author_id]['books'];
if (count($data['books']) > 0) {
    $data['content'] = $base_path . $file_loc['html_snippet']['table']['book'];
} else {
    $data['content'] = $base_path . $file_loc['html_snippet']['table']['no_books'];
}
$data['link']['book'] = $page_router['book'];
$data['link']['author'] = $page_router['author'];

$template_path = $base_path . $file_loc['layout']['normal_no_footer'];

render($data, $template_path);