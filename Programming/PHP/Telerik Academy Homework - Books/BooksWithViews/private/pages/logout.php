<?php
require 'base_path.php';

// Logout Procedures
require $base_path.$file_loc['script']['logout'];

// Redirect to Main
redirect('public', $page_router['main']);