<?php
require 'base_path.php';

if (!isset($_GET['id'])) {
    $send_to = $page_router['main'];
    redirect('public', $send_to);
}

$error = NULL;

$data = [];
$book_id = trim($_GET['id']);
$book_id = intval($book_id, 10);

$order_by = 'asc';
$data['book'] = get_book($error, $db_conn, $book_id);

if ($error) {
    $send_to = $page_router['error'].'&error='.$error;
    redirect('public', $send_to);
}

$order_by = 'desc';
$data['book']['comments'] = get_comments_for_book($error, $db_conn, $book_id, $order_by);

if ($error) {
    $send_to = $page_router['error'].'&error='.$error;
    redirect('public', $send_to);
}

if (IS_DEBUG_MODE) {
    var_dump($data);
    exit;
}

$data['title'] = 'Книга - '.$data['book']['title'];

$data['is_logged'] = $is_logged;
if ($is_logged) {
    $data['nav'] = $base_path . $file_loc['html_snippet']['nav_logged']['book'];
    $data['login'] = $base_path . $file_loc['html_snippet']['login']['logged'];
    
    $data['link']['logout'] = $page_router['logout'];
    
    $data['link']['add_book'] = $page_router['add_book'];
    $data['link']['add_author'] = $page_router['add_author'];
    
    $data['send_form_to'] = $base_path . $file_loc['script']['add_comment'];
    $data['add_comment'] = $base_path . $file_loc['html_snippet']['form']['add_comment'];
} else {
    $data['nav'] = $base_path . $file_loc['html_snippet']['nav']['book'];
    
    $data['login'] = $base_path . $file_loc['html_snippet']['login']['not_logged'];
    
    $data['link']['login'] = $page_router['login'];
    $data['link']['register'] = $page_router['register'];
    
    $data['message'] = 'To post comments, you must login first!';
    $data['add_comment'] = $base_path . $file_loc['html_snippet']['notice']['message'];
}

$data['content'] = $base_path . $file_loc['html_snippet']['display']['single_book'];
if (count($data['book']['comments']) > 0) {
    $data['comments'] = $base_path . $file_loc['html_snippet']['table']['comments'];
}
else {
    $data['comments'] = $base_path . $file_loc['html_snippet']['table']['no_comments'];
}

$data['link']['book'] = $page_router['book'];
$data['link']['author'] = $page_router['author'];

$template_path = $base_path . $file_loc['layout']['comments_no_footer'];

render($data, $template_path);