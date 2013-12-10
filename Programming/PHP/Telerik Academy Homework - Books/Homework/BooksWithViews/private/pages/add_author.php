<?php
require 'base_path.php';

if (!$is_logged) {
    $send_to = $page_router['main'];
    redirect('public', $send_to);
}

$error = NULL;
if (isset($_GET['error'])) {
    $error = trim($_GET['error']);
}

$data = [];
$data['title'] = 'Add Author';

$data['is_logged'] = $is_logged;

$data['nav'] = $base_path . $file_loc['html_snippet']['nav_logged']['add_author'];
$data['login'] = $base_path . $file_loc['html_snippet']['login']['logged'];

$data['link']['logout'] = $page_router['logout'];

$data['link']['add_book'] = $page_router['add_book'];
$data['link']['add_author'] = $page_router['add_author'];

$data['link']['main'] = $page_router['main'];

$data['content'] = $base_path . $file_loc['html_snippet']['form']['add_author'];
$data['send_form_to'] = $base_path . $file_loc['script']['add_author'];


if (!$error) {
    $template_path = $base_path . $file_loc['layout']['normal_no_footer'];
    
    if (isset($_GET['added'])) {
        $is_author_added = $_GET['added'];
        if ($is_author_added === 'yes') {
            $template_path = $base_path . $file_loc['layout']['normal'];
            $data['footer'] = $base_path . $file_loc['html_snippet']['notice']['message'];
            $data['message'] = 'A new author has been added!';
        }
    }
}
 else {
    $template_path = $base_path . $file_loc['layout']['normal'];
    $data['footer'] = $base_path . $file_loc['html_snippet']['notice']['warning'];
    $data['warning'] = $error;
}

render($data, $template_path);