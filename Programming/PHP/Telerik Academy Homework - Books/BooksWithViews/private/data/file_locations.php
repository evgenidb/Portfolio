<?php
require 'constants.php';

$file_loc = [
    'page' => [
        'main' => 'private'.DIR_SEP.'pages'.DIR_SEP.'main.php',
        'add_author' => 'private'.DIR_SEP.'pages'.DIR_SEP.'add_author.php',
        'add_book' => 'private'.DIR_SEP.'pages'.DIR_SEP.'add_book.php',
        'author' => 'private'.DIR_SEP.'pages'.DIR_SEP.'author.php',
        'book' => 'private'.DIR_SEP.'pages'.DIR_SEP.'book.php',
        'login' => 'private'.DIR_SEP.'pages'.DIR_SEP.'login.php',
        'logout' => 'private'.DIR_SEP.'pages'.DIR_SEP.'logout.php',
        'register' => 'private'.DIR_SEP.'pages'.DIR_SEP.'register.php',
        'error' => 'private'.DIR_SEP.'pages'.DIR_SEP.'error.php',
    ],
    'layout' => [
        'normal' => 'private'.DIR_SEP.'templates'.DIR_SEP.'layouts'.DIR_SEP.'normal_layout.php',
        'normal_no_footer' => 'private'.DIR_SEP.'templates'.DIR_SEP.'layouts'.DIR_SEP.'normal_no_footer_layout.php',
        'comments' => 'private'.DIR_SEP.'templates'.DIR_SEP.'layouts'.DIR_SEP.'comments_layout.php',
        'comments_no_footer' => 'private'.DIR_SEP.'templates'.DIR_SEP.'layouts'.DIR_SEP.'comments_no_footer_layout.php',
        'error' => 'private'.DIR_SEP.'templates'.DIR_SEP.'layouts'.DIR_SEP.'error_layout.php',
    ],
    'html_snippet' => [
        'display' => [
            'single_book' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'display_data'.DIR_SEP.'display_single_book.php',
        ],
        'form' => [
            'add_author' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'forms'.DIR_SEP.'form_add_author.php',
            'add_book' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'forms'.DIR_SEP.'form_add_book.php',
            'add_comment' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'forms'.DIR_SEP.'form_add_comment.php',
            'login' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'forms'.DIR_SEP.'form_login.php',
            'register' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'forms'.DIR_SEP.'form_register.php',
        ],
        'nav' => [
            'main' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'nav'.DIR_SEP.'nav_main.php',
            'add_author' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'nav'.DIR_SEP.'nav_general.php',
            'add_book' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'nav'.DIR_SEP.'nav_general.php',
            'author' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'nav'.DIR_SEP.'nav_general.php',
            'book' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'nav'.DIR_SEP.'nav_general.php',
            'login' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'nav'.DIR_SEP.'nav_general.php',
            'register' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'nav'.DIR_SEP.'nav_general.php',
        ],
        
        'nav_logged' => [
            'main' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'nav'.DIR_SEP.'nav_main_logged.php',
            'add_author' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'nav'.DIR_SEP.'nav_general_logged.php',
            'add_book' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'nav'.DIR_SEP.'nav_general_logged.php',
            'author' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'nav'.DIR_SEP.'nav_general_logged.php',
            'book' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'nav'.DIR_SEP.'nav_general_logged.php',
            'login' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'nav'.DIR_SEP.'nav_general_logged.php',
            'register' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'nav'.DIR_SEP.'nav_general_logged.php',
        ],
        'login' => [
            'logged' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'login'.DIR_SEP.'user_logged.php',
            'not_logged' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'login'.DIR_SEP.'user_not_logged.php',
        ],
        'table' => [
            'books' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'tables'.DIR_SEP.'table_books.php',
            'no_books' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'tables'.DIR_SEP.'table_no_books.php',
            'comments' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'tables'.DIR_SEP.'table_comments.php',
            'no_comments' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'tables'.DIR_SEP.'table_no_comments.php',
        ],
        'notice' => [
            'message' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'notice'.DIR_SEP.'message.php',
            'warning' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'notice'.DIR_SEP.'warning.php',
            'error' => 'private'.DIR_SEP.'templates'.DIR_SEP.'html_snippets'.DIR_SEP.'notice'.DIR_SEP.'error.php',
        ]
    ],
    'config' => [
        'main' => 'private'.DIR_SEP.'config'.DIR_SEP.'main_config.php',
        'type' => [
            'db_conn' => 'private'.DIR_SEP.'config'.DIR_SEP.'config_types'.DIR_SEP.'db_connection.php',
            'encoding' => 'private'.DIR_SEP.'config'.DIR_SEP.'config_types'.DIR_SEP.'encoding.php',
            'session' => 'private'.DIR_SEP.'config'.DIR_SEP.'config_types'.DIR_SEP.'session.php',
        ],
    ],
    'data' => [
        'files' => 'private'.DIR_SEP.'data'.DIR_SEP.'file_locations.php',
        'page_router' => 'private'.DIR_SEP.'data'.DIR_SEP.'page_router.php',
        'constants' => 'private'.DIR_SEP.'data'.DIR_SEP.'constants.php',
    ],
    'functions' => [
        'functions' => 'private'.DIR_SEP.'functions'.DIR_SEP.'functions.php',
        'db_queries' => 'private'.DIR_SEP.'functions'.DIR_SEP.'db_queries.php',
    ],
    'script' => [
        'login' => 'private'.DIR_SEP.'scripts'.DIR_SEP.'script_login.php',
        'logout' => 'private'.DIR_SEP.'scripts'.DIR_SEP.'script_logout.php',
        'register' => 'private'.DIR_SEP.'scripts'.DIR_SEP.'script_register.php',
        'add_book' => 'private'.DIR_SEP.'scripts'.DIR_SEP.'script_add_book.php',
        'add_author' => 'private'.DIR_SEP.'scripts'.DIR_SEP.'script_add_author.php',
        'add_comment' => 'private'.DIR_SEP.'scripts'.DIR_SEP.'script_add_comment.php',
    ],
];