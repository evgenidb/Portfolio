<?php
require 'base_path.php';

if ($is_logged) {
    redirect(NULL, $page_router['main']);
}

$error = NULL;
if (isset($_GET['error'])) {
    $error = trim($_GET['error']);
}

$data = [];
$data['title'] = 'Register';

$data['nav'] = $base_path . $file_loc['html_snippet']['nav']['register'];
$data['link']['main'] = $page_router['main'];

$data['login'] = $base_path . $file_loc['html_snippet']['login']['not_logged'];
$data['link']['login'] = $page_router['login'];
$data['link']['register'] = $page_router['register'];

$data['content'] = $base_path . $file_loc['html_snippet']['form']['register'];
$data['send_form_to'] = $base_path . $file_loc['script']['register'];

if (!$error) {
    $template_path = $base_path . $file_loc['layout']['normal_no_footer'];
}
 else {
    $template_path = $base_path . $file_loc['layout']['normal'];
    $data['footer'] = $base_path . $file_loc['html_snippet']['notice']['warning'];
    $data['warning'] = $error;
}

render($data, $template_path);