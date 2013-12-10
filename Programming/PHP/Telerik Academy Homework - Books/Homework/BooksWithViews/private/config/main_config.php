<?php
require 'base_path.php';

// Functions
//require $base_path.$file_loc['functions']['functions'];
require $base_path.$file_loc['functions']['functions'];

// Encoding
require $base_path.$file_loc['config']['type']['encoding'];

// Session
require $base_path.$file_loc['config']['type']['session'];

// DB connection
require $base_path.$file_loc['config']['type']['db_conn'];

// DB Queries
require $base_path.$file_loc['functions']['db_queries'];

// Page Router
require $base_path.$file_loc['data']['page_router'];