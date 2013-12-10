<?php
// Authors
function add_author(&$error, $db, $data) {
    // Clear the error container
    $error = NULL;
    
    // Is Data set
    $is_data_set = (isset($data) and isset($data['name']));
    if (!$is_data_set) {
        $error = 'Param Error. Data param not set properly!';
        return NULL;
    }
    
    // Set data to variables
    $name = $data['name'];
    
    // Data Validation
    $valid_author_name_range = [
        'min' => 1,
        'max' => 250,
    ];
    
    $is_author_name_in_range = 
            mb_strlen($name) >= $valid_author_name_range['min'] and
            mb_strlen($name) <= $valid_author_name_range['max'];
    
    if (!$is_author_name_in_range) {
        $error = 'Param Error. Name param out of range! Must be between '
                .$valid_author_name_range['min'].' and '
                .$valid_author_name_range['max'].'!';
        return NULL;
    }
    
    // Construct query
    $query = '
        INSERT INTO authors (author_name)
        VALUES (?)';
    
    $statement = mysqli_prepare($db, $query);
    if (!$statement) {
        $error = 'DB Error. Wrong query!';
        
        if (IS_DEBUG_MODE) {
            var_dump($query);
        }
        
        return NULL;
    }
    
    // Param Binding
    $bind_successful = mysqli_stmt_bind_param($statement, 's', $name);
    if (!$bind_successful) {
        $error = 'DB Error. Wrong param bind!';
        return NULL;
    }
    
    // Execute query
    $execution_successful = mysqli_stmt_execute($statement);
    if (!$execution_successful) {
        $error = 'DB Error. Failed query execution!';
        return NULL;
    }
    
    // Return result
    $result_count = mysqli_affected_rows($db);

    if ($result_count === 1) {
        // Author added
        return TRUE;
    }
    else {
        // Author NOT added (probably author already exists)
        return FALSE;
    }
}

function get_authors(&$error, $db, $order_by = NULL) {
    // Clear the error container
    $error = NULL;
    
    // Decide Sorting
    $sort = NULL;
    
    if (isset($order_by)) {
        $lower_order_by = mb_strtolower($order_by);
        switch ($lower_order_by) {
            case 'ascending':
            case 'asc':
                $sort = 'ASC';
                break;

            case 'descending':
            case 'desc':
                $sort = 'DESC';
                break;

            default:
                $sort = NULL;
                break;
        }
    }
    
    // Construct query
    $query = '
        SELECT author_id, author_name
        FROM authors';
    if (isset($sort)) {
        $query = $query.PHP_EOL.'ORDER BY author_name '.$sort;
    }
    
    $statement = mysqli_prepare($db, $query);
    if (!$statement) {
        $error = 'DB Error. Wrong query!';
        
        if (IS_DEBUG_MODE) {
            var_dump($query);
        }
        
        return NULL;
    }
    
    // Execute query
    $execution_successful = mysqli_stmt_execute($statement);
    if (!$execution_successful) {
        $error = 'DB Error. Failed query execution!';
        return NULL;
    }
    
    // Return result
    mysqli_stmt_bind_result($statement, $author_id, $author_name);
    $result = [];
    while ($row = mysqli_stmt_fetch($statement)) {
        $result[$author_id] = $author_name;
    }
    
    return $result;
}

function get_author_and_his_books_by_id(&$error, $db, $author_id, $order_by = NULL) {
    // Clear the error container
    $error = NULL;
    
    // Make sure the id is int
    $author_id = intval($author_id, 10);
    
    // Decide Sorting
    $sort = NULL;
    
    if (isset($order_by)) {
        $lower_order_by = mb_strtolower($order_by);
        switch ($lower_order_by) {
            case 'ascending':
            case 'asc':
                $sort = 'ASC';
                break;

            case 'descending':
            case 'desc':
                $sort = 'DESC';
                break;

            default:
                $sort = NULL;
                break;
        }
    }
    
    // Construct query
    $query = '
        SELECT b.book_id, a.author_id, b.book_title, a.author_name
        FROM books_authors as ba
            INNER JOIN books as b
                ON ba.book_id = b.book_id
            INNER JOIN books_authors as bba
                ON ba.book_id = bba.book_id
            INNER JOIN authors as a
                ON bba.author_id = a.author_id
        WHERE ba.author_id = ?';
    if (isset($sort)) {
        $query = $query.PHP_EOL.'ORDER BY b.book_title '.$sort;
    }
    
    $statement = mysqli_prepare($db, $query);
    if (!$statement) {
        $error = 'DB Error. Wrong query!';
        
        if (IS_DEBUG_MODE) {
            var_dump($query);
        }
        
        return NULL;
    }
    
    // Param Binding
    $bind_successful = mysqli_stmt_bind_param($statement, 'i', $author_id);
    if (!$bind_successful) {
        $error = 'DB Error. Wrong param bind!';
        return NULL;
    }
    
    // Execute query
    $execution_successful = mysqli_stmt_execute($statement);
    if (!$execution_successful) {
        $error = 'DB Error. Failed query execution!';
        return NULL;
    }
    
    // Return result
    mysqli_stmt_bind_result($statement, $book_id, $auth_id, $book_title, $author_name);
    $result = [
        $author_id => [
            'name' => '',
            'books' => [],
        ],
    ];
//    $result = [
//        $author_id => [
//            'name' => $author_name,
//            'books' => [
//                $book_id => [
//                    'title' => $book_title,
//                    'authors' => [
//                        $auth_id => $author_name,
//                    ],
//                ],
//            ],
//        ],
//    ];
    while ($row = mysqli_stmt_fetch($statement)) {
        if ($author_id === $auth_id) {
            $result[$author_id]['name'] = $author_name;
        }
        
        if (!key_exists($book_id, $result[$author_id]['books'])) {
            $result[$author_id]['books'][$book_id]['title'] = $book_title;
        }
        
        $result[$author_id]['books'][$book_id]['authors'][$auth_id] = $author_name;
    }
    
    return $result;
}

function is_all_author_ids_exist(&$error, $db, $author_ids) {
    // Clear the error container
    $error = NULL;
    
    // Validate data (NOTE: doesn't check the contents of $ids)
    $is_author_ids_valid = (isset($author_ids) and gettype($author_ids) === 'array' and !empty($author_ids));
    if (!$is_author_ids_valid) {
        $error = 'Param Error. The array of IDs not passed, empty or not an array!';
        return NULL;
    }
    
    // Construct query
    $placeholder = '?';
    $ids_count = count($author_ids);
    $placeholder_array = array_fill(0,  $ids_count, $placeholder);  // returns array: [?, ?, ?, ?, ?]
    $placeholder_ids_str = implode(', ', $placeholder_array);       // returns string: ?, ?, ?, ?, ?
    
//    $query = '
//        SELECT author_id
//        FROM authors
//        WHERE author_id IN ('.$placeholder_ids_str.')';
    
    $query = '
            SELECT author_id
            FROM authors
            WHERE author_id IN ('.implode(', ', $author_ids).')';
    
    $statement = mysqli_prepare($db, $query);
    if (!$statement) {
        $error = 'DB Error. Wrong query!';
        
        if (IS_DEBUG_MODE) {
            var_dump($query);
        }
        
        return NULL;
    }
    
//    // Param Binding
//    $id_type = 'i';
//    $bind_types = str_repeat($id_type, $ids_count);
//    $bind_successful = mysqli_stmt_bind_param($statement, $bind_types, $author_ids);
//    if (!$bind_successful) {
//        $error = 'DB Error. Wrong param bind!';
//        return NULL;
//    }
    
    // Execute query
    $execution_successful = mysqli_stmt_execute($statement);
    if (!$execution_successful) {
        $error = 'DB Error. Failed query execution!';
        return NULL;
    }
    
    // Return result
    mysqli_stmt_store_result($statement);
    $result_count = mysqli_stmt_num_rows($statement);

    if ($result_count === $ids_count) {
        return TRUE;
    }
    else {
        return FALSE;
    }
}

// Authors - Optimazed version for checking if just one author id exists
function is_author_id_exists(&$error, $db, $author_id) {
    // Clear the error container
    $error = NULL;
    
    // Make sure the id is int
    $author_id = intval($author_id, 10);
    
    // Construct query
    $query = '
        SELECT author_id
        FROM authors
        WHERE author_id = ?';
    
    $statement = mysqli_prepare($db, $query);
    if (!$statement) {
        $error = 'DB Error. Wrong query!';
        
        if (IS_DEBUG_MODE) {
            var_dump($query);
        }
        
        return NULL;
    }
    
    // Param Binding
    $bind_successful = mysqli_stmt_bind_param($statement, 'i', $author_id);
    if (!$bind_successful) {
        $error = 'DB Error. Wrong param bind!';
        return NULL;
    }
    
    // Execute query
    $execution_successful = mysqli_stmt_execute($statement);
    if (!$execution_successful) {
        $error = 'DB Error. Failed query execution!';
        return NULL;
    }
    
    // Return result
    mysqli_stmt_store_result($statement);
    $result_count = mysqli_stmt_num_rows($statement);

    if ($result_count === 1) {
        return TRUE;
    }
    else {
        return FALSE;
    }
}



// Books
function add_book (&$error, $db, $data) {
    // Clear the error container
    $error = NULL;
    
    // Is Data set
    $is_data_set = (isset($data) and isset($data['title']) and isset($data['author_ids']));
    if (!$is_data_set) {
        $error = 'Param Error. Data param not set properly!';
        return NULL;
    }
    
    // Set Data to Variables
    $title = $data['title'];
    $author_ids = $data['author_ids'];
    
    // Data Validation
    $valid_book_title_range = [
        'min' => 1,
        'max' => 250,
    ];
    
    $is_book_title_in_range = 
            mb_strlen($title) >= $valid_book_title_range['min'] and
            mb_strlen($title) <= $valid_book_title_range['max'];
    
    if (!$is_book_title_in_range) {
        $error = 'Param Error. Title param out of range! Must be between '
                .$valid_book_title_range['min'].' and '
                .$valid_book_title_range['max'].'!';
        return NULL;
    }
    
    if (gettype($author_ids) !== 'array') {
        $error = 'Param Error. Authors param is not array!';
        return NULL;
    }
    
    $is_authors_empty = empty($author_ids);
    if ($is_authors_empty) {
        $error = 'Param Error. Authors param is empty!';
        return NULL;
    }
    
    $is_authors_exist = is_all_author_ids_exist($error, $db, $author_ids);
    if (isset($error)) {
        // No need to set $error - it's already set by previous function!
        return NULL;
    }
    if (!$is_authors_exist) {
        $error = 'Param Error. At least one Author Id does not exist!';
        return NULL;
    }
    
    // Construct query
    $query = '
        INSERT INTO books (book_title)
        VALUES (?)';
    
    $statement = mysqli_prepare($db, $query);
    if (!$statement) {
        $error = 'DB Error. Wrong query!';
        
        if (IS_DEBUG_MODE) {
            var_dump($query);
        }
        
        return NULL;
    }
    
    // Param Binding
    $bind_successful = mysqli_stmt_bind_param($statement, 's', $title);
    if (!$bind_successful) {
        $error = 'DB Error. Wrong param bind!';
        return NULL;
    }
    
    // Execute query
    $execution_successful = mysqli_stmt_execute($statement);
    if (!$execution_successful) {
        $error = 'DB Error. Failed query execution!';
        return NULL;
    }
    
    // Return result
    $result_count = mysqli_affected_rows($db);

    if ($result_count !== 1) {
        // Book NOT added (probably book already exists)
        return FALSE;
    }
    
    // Add authors
    // Get the id of the added Book
    $added_book_id = mysqli_stmt_insert_id($statement);

    // Connect the book with its authors
    // 
    // Create query
    $query = '
            INSERT INTO books_authors (book_id, author_id)
            VALUES (?, ?)';

    $statement = mysqli_prepare($db, $query);
    if (!$statement) {
        $error = 'DB Error. Wrong query!';
        
        if (IS_DEBUG_MODE) {
            var_dump($query);
        }
        
        return NULL;
    }
    
    // Binding
    $bind_successful = mysqli_stmt_bind_param($statement, 'ii', $added_book_id, $author_id);
    if (!$bind_successful) {
        $error = 'DB Error. Wrong param bind!';
        return NULL;
    }
    
    // Execute for every Author ID
    foreach ($author_ids as $author_id) {
        $execution_successful = mysqli_stmt_execute($statement);
        if (!$execution_successful) {
            $error = 'DB Error. Failed query execution!';
            return NULL;
        }
    }
    
    return TRUE;
}

function get_book (&$error, $db, $book_id) {
    // Clear the error container
    $error = NULL;
    
    // Make sure the id is int
    $book_id = intval($book_id, 10);
    
    // Validate
    $is_book_exists = is_book_id_exists($error, $db, $book_id);
    if (isset($error)) {
        // No need to set $error - it's already set by previous function!
        return NULL;
    }
    if (!$is_book_exists) {
        $error = 'Param Error. Book Id does NOT exist in the DB!';
        return NULL;
    }
    
    // Decide Sorting
    $sort = NULL;
    
    if (isset($order_by)) {
        $lower_order_by = mb_strtolower($order_by);
        switch ($lower_order_by) {
            case 'ascending':
            case 'asc':
                $sort = 'ASC';
                break;

            case 'descending':
            case 'desc':
                $sort = 'DESC';
                break;

            default:
                $sort = NULL;
                break;
        }
    }
    
    // Construct query
    $query = '
        SELECT b.book_id, a.author_id, b.book_title, a.author_name
        FROM books as b
            INNER JOIN books_authors as ba
                ON b.book_id = ba.book_id
            INNER JOIN authors as a
                ON ba.author_id = a.author_id
        WHERE b.book_id = ?';
    
    $statement = mysqli_prepare($db, $query);
    if (!$statement) {
        $error = 'DB Error. Wrong query!';
        
        if (IS_DEBUG_MODE) {
            var_dump($query);
        }
        
        return NULL;
    }
    
    // Param Binding
    $bind_successful = mysqli_stmt_bind_param($statement, 'i', $book_id);
    if (!$bind_successful) {
        $error = 'DB Error. Wrong param bind!';
        return NULL;
    }
    
    // Execute query
    $execution_successful = mysqli_stmt_execute($statement);
    if (!$execution_successful) {
        $error = 'DB Error. Failed query execution!';
        return NULL;
    }
    
    // Return result
    mysqli_stmt_bind_result($statement,
            $book_id, $author_id, $book_title, $author_name);
    $result = [
        'book_id' => $book_id,
        'title' => $book_title,
        'authors' => [],
    ];
    while ($row = mysqli_stmt_fetch($statement)) {
        $result['book_id'] = $book_id;
        $result['title'] = $book_title;
        $result['authors'][$author_id] = $author_name;
    }
    
    return $result;
}

function get_books(&$error, $db, $order_by = NULL) {
    // Clear the error container
    $error = NULL;
    
    // Decide Sorting
    $sort = NULL;
    
    if (isset($order_by)) {
        $lower_order_by = mb_strtolower($order_by);
        switch ($lower_order_by) {
            case 'ascending':
            case 'asc':
                $sort = 'ASC';
                break;

            case 'descending':
            case 'desc':
                $sort = 'DESC';
                break;

            default:
                $sort = NULL;
                break;
        }
    }
    
    // Construct query
    $query = '
        SELECT book_id, book_title
        FROM books';
    if (isset($sort)) {
        $query = $query.PHP_EOL.'ORDER BY book_title '.$sort;
    }
    
    $statement = mysqli_prepare($db, $query);
    if (!$statement) {
        $error = 'DB Error. Wrong query!';
        
        if (IS_DEBUG_MODE) {
            var_dump($query);
        }
        
        return NULL;
    }
    
    // Execute query
    $execution_successful = mysqli_stmt_execute($statement);
    if (!$execution_successful) {
        $error = 'DB Error. Failed query execution!';
        return NULL;
    }
    
    // Return result
    mysqli_stmt_bind_result($statement,
            $book_id, $book_title);
    $result = [];
    while ($row = mysqli_stmt_fetch($statement)) {
        $result[$book_id] = $book_title;
    }
    
    return $result;
}

// Books - Gets Books and their Authors
function get_books_and_their_authors(&$error, $db, $order_by = NULL) {
    // Clear the error container
    $error = NULL;
    
    // Decide Sorting
    $sort = NULL;
    
    if (isset($order_by)) {
        $lower_order_by = mb_strtolower($order_by);
        switch ($lower_order_by) {
            case 'ascending':
            case 'asc':
                $sort = 'ASC';
                break;

            case 'descending':
            case 'desc':
                $sort = 'DESC';
                break;

            default:
                $sort = NULL;
                break;
        }
    }
    
    // Construct query
    $query = '
        SELECT b.book_id, a.author_id, b.book_title, a.author_name
        FROM books_authors as ba
            INNER JOIN books as b
                ON ba.book_id = b.book_id
            INNER JOIN books_authors as bba
                ON ba.book_id = bba.book_id
            INNER JOIN authors as a
                ON bba.author_id = a.author_id
            ';
    if (isset($sort)) {
        $query = $query.PHP_EOL.'ORDER BY b.book_title '.$sort;
    }
    
    $statement = mysqli_prepare($db, $query);
    if (!$statement) {
        $error = 'DB Error. Wrong query!';
        
        if (IS_DEBUG_MODE) {
            var_dump($query);
        }
        
        return NULL;
    }
    
    // Execute query
    $execution_successful = mysqli_stmt_execute($statement);
    if (!$execution_successful) {
        $error = 'DB Error. Failed query execution!';
        return NULL;
    }
    
    // Return result
    mysqli_stmt_bind_result($statement, $book_id, $author_id, $book_title, $author_name);
    $result = [];
    while ($row = mysqli_stmt_fetch($statement)) {
        if (!key_exists($book_id, $result)) {
            $result[$book_id] = [
                'title' => $book_title,
                'authors' => [],
            ];
        }
        $result[$book_id]['authors'][$author_id] = $author_name;
    }
    
    return $result;
}

function is_book_id_exists(&$error, $db, $book_id) {
    // Clear the error container
    $error = NULL;
    
    // Make sure the id is int
    $book_id = intval($book_id, 10);
    
    // Construct query
    $query = '
        SELECT book_id
        FROM books
        WHERE book_id = ?';
    
    $statement = mysqli_prepare($db, $query);
    if (!$statement) {
        $error = 'DB Error. Wrong query!';
        
        if (IS_DEBUG_MODE) {
            var_dump($query);
        }
        
        return NULL;
    }
    
    // Param Binding
    $bind_successful = mysqli_stmt_bind_param($statement, 'i', $book_id);
    if (!$bind_successful) {
        $error = 'DB Error. Wrong param bind!';
        return NULL;
    }
    
    // Execute query
    $execution_successful = mysqli_stmt_execute($statement);
    if (!$execution_successful) {
        $error = 'DB Error. Failed query execution!';
        return NULL;
    }
    
    // Return result
    mysqli_stmt_store_result($statement);
    $result_count = mysqli_stmt_num_rows($statement);

    if ($result_count === 1) {
        return TRUE;
    }
    else {
        return FALSE;
    }
}



// Users
function add_user(&$error, $db, $data) {
    // Clear the error container
    $error = NULL;
    
    // Is Data set
    $is_data_set = (isset($data) and isset($data['username']) and isset($data['password']));
    if (!$is_data_set) {
        $error = 'Param Error. Data param not set properly!';
        return NULL;
    }
    
    // Set Data to Variables
    $username = $data['username'];
    $password = $data['password'];
    
    // Data Validation
    $valid_user_username_range = [
        'min' => 6,
        'max' => 50,
    ];
    
    $is_user_username_in_range = 
            mb_strlen($username) >= $valid_user_username_range['min'] and
            mb_strlen($username) <= $valid_user_username_range['max'];
    
    if (!$is_user_username_in_range) {
        $error = 'Param Error. Username param out of range! Must be between '
                .$valid_user_username_range['min'].' and '
                .$valid_user_username_range['max'].'!';
        return NULL;
    }
    
    $valid_user_password_range = [
        'min' => 6,
        'max' => 50,
    ];
    
    $is_user_password_in_range = 
            mb_strlen($password) >= $valid_user_password_range['min'] and
            mb_strlen($password) <= $valid_user_password_range['max'];
    
    if (!$is_user_password_in_range) {
        $error = 'Param Error. Password param out of range! Must be between '
                .$valid_user_password_range['min'].' and '
                .$valid_user_password_range['max'].'!';
        return NULL;
    }
    
    // Check if user already exists
    $is_user_exist = get_user_id_by_data($error, $db, $data);
    if ($error) {
        return NULL;
    }
    if (!empty($is_user_exist)) {
        // User already exists. Nothing added
        return FALSE;
    }
    
    // Construct query
    $query = '
        INSERT INTO users (user_username, user_password)
        VALUES (?, ?)';
    
    $statement = mysqli_prepare($db, $query);
    if (!$statement) {
        $error = 'DB Error. Wrong query!';
        
        if (IS_DEBUG_MODE) {
            var_dump($query);
        }
        
        return NULL;
    }
    
    // Param Binding
    $bind_successful = mysqli_stmt_bind_param($statement, 'ss', $username, $password);
    if (!$bind_successful) {
        $error = 'DB Error. Wrong param bind!';
        return NULL;
    }
    
    // Execute query
    $execution_successful = mysqli_stmt_execute($statement);
    if (!$execution_successful) {
        $error = 'DB Error. Failed query execution!';
        return NULL;
    }
    
    // Return result
    $result_count = mysqli_affected_rows($db);

    if ($result_count === 1) {
        // User added
        return TRUE;
    }
    else {
        // User NOT added (probably author already exists)
        return FALSE;
    }
}

function get_user_id_by_data (&$error, $db, $data) {
    // Clear the error container
    $error = NULL;
    
    // Is Data set
    $is_data_set = (isset($data) and isset($data['username']) and isset($data['password']));
    if (!$is_data_set) {
        $error = 'Param Error. Data param not set properly!';
        return NULL;
    }
    
    // Set Data to Variables
    $username = $data['username'];
    $password = $data['password'];
    
    // Construct query
    $query = '
        SELECT user_id
        FROM users
        WHERE user_username = ?
            AND user_password = ?';
    
    $statement = mysqli_prepare($db, $query);
    if (!$statement) {
        $error = 'DB Error. Wrong query!';
        
        if (IS_DEBUG_MODE) {
            var_dump($query);
        }
        
        return NULL;
    }
    
    // Param Binding
    $bind_successful = mysqli_stmt_bind_param($statement, 'ss', $username, $password);
    if (!$bind_successful) {
        $error = 'DB Error. Wrong param bind!';
        return NULL;
    }
    
    // Execute query
    $execution_successful = mysqli_stmt_execute($statement);
    if (!$execution_successful) {
        $error = 'DB Error. Failed query execution!';
        return NULL;
    }
    
    // Return result
    mysqli_stmt_bind_result($statement, $user_id);
    $result = [];
    while ($row = mysqli_stmt_fetch($statement)) {
        $result[] = $user_id;
    }
    
    return $result;
}

function get_user_id_by_username (&$error, $db, $data) {
    // Clear the error container
    $error = NULL;
    
    // Is Data set
    $is_data_set = (isset($data) and isset($data['username']));
    if (!$is_data_set) {
        $error = 'Param Error. Data param not set properly!';
        return NULL;
    }
    
    // Set Data to Variables
    $username = $data['username'];
    
    // Construct query
    $query = '
        SELECT user_id
        FROM users
        WHERE user_username = ?';
    
    $statement = mysqli_prepare($db, $query);
    if (!$statement) {
        $error = 'DB Error. Wrong query!';
        
        if (IS_DEBUG_MODE) {
            var_dump($query);
        }
        
        return NULL;
    }
    
    // Param Binding
    $bind_successful = mysqli_stmt_bind_param($statement, 's', $username);
    if (!$bind_successful) {
        $error = 'DB Error. Wrong param bind!';
        return NULL;
    }
    
    // Execute query
    $execution_successful = mysqli_stmt_execute($statement);
    if (!$execution_successful) {
        $error = 'DB Error. Failed query execution!';
        return NULL;
    }
    
    // Return result
    mysqli_stmt_bind_result($statement, $user_id);
    $result = [];
    while ($row = mysqli_stmt_fetch($statement)) {
        $result[] = $user_id;
    }
    
    return $result;
}

function is_user_id_exists (&$error, $db, $user_id) {
    // Clear the error container
    $error = NULL;
    
    // Make sure the id is int
    $user_id = intval($user_id, 10);
    
    // Construct query
    $query = '
        SELECT user_id
        FROM users
        WHERE user_id = ?';
    
    $statement = mysqli_prepare($db, $query);
    if (!$statement) {
        $error = 'DB Error. Wrong query!';
        
        if (IS_DEBUG_MODE) {
            var_dump($query);
        }
        
        return NULL;
    }
    
    // Param Binding
    $bind_successful = mysqli_stmt_bind_param($statement, 'i', $user_id);
    if (!$bind_successful) {
        $error = 'DB Error. Wrong param bind!';
        return NULL;
    }
    
    // Execute query
    $execution_successful = mysqli_stmt_execute($statement);
    if (!$execution_successful) {
        $error = 'DB Error. Failed query execution!';
        return NULL;
    }
    
    // Return result
    mysqli_stmt_store_result($statement);
    $result_count = mysqli_stmt_num_rows($statement);

    if ($result_count === 1) {
        return TRUE;
    }
    else {
        return FALSE;
    }
}



// Comments
function add_comment(&$error, $db, $data) {
    // Clear the error container
    $error = NULL;
    
    // Is Data set
    // NOTE: Timestamp - the Db should provide it
    if (!isset($data) or !isset($data['book_id']) or !isset($data['text']) or !isset($data['user_id'])) {
        $error = 'Param Error. Data param not set properly!';
        return NULL;
    }
    
    // Set Data to Variables
    $book_id = $data['book_id'];
    $user_id = $data['user_id'];
    $text = $data['text'];
    
    // Data Validation
    $valid_comment_text_range = [
        'min' => 1,
        'max' => 140,
    ];
    
    $is_comment_text_in_range = 
            mb_strlen($text) >= $valid_comment_text_range['min'] and
            mb_strlen($text) <= $valid_comment_text_range['max'];
    
    if (!$is_comment_text_in_range) {
        $error = 'Param Error. Text param out of range! Must be between '
                .$valid_comment_text_range['min'].' and '
                .$valid_comment_text_range['max'].'!';
        return NULL;
    }
    
    // Check if the ids exist in the db
    $is_book_exists = is_book_id_exists($error, $db, $book_id);
    if (isset($error)) {
        // No need to set $error - it's already set by previous function!
        return NULL;
    }
    if (!$is_book_exists) {
        $error = 'Param Error. Book Id does NOT exist in the DB! '.$book_id;
        return NULL;
    }
    
    $is_user_exists = is_user_id_exists($error, $db, $user_id);
    if (isset($error)) {
        // No need to set $error - it's already set by previous function!
        return NULL;
    }
    if (!$is_user_exists) {
        $error = 'Param Error. Author Id does NOT exist in the DB!';
        return NULL;
    }
    
    // Construct query
    $query = '
        INSERT INTO comments (comment_book_id, comment_user_id, comment_text)
        VALUES (?, ?, ?)';
    
    $statement = mysqli_prepare($db, $query);
    if (!$statement) {
        $error = 'DB Error. Wrong query!';
        
        if (IS_DEBUG_MODE) {
            var_dump($query);
        }
        
        return NULL;
    }
    
    // Param Binding
    $bind_successful = mysqli_stmt_bind_param($statement, 'iis', $book_id, $user_id, $text);
    if (!$bind_successful) {
        $error = 'DB Error. Wrong param bind!';
        return NULL;
    }
    
    // Execute query
    $execution_successful = mysqli_stmt_execute($statement);
    if (!$execution_successful) {
        $error = 'DB Error. Failed query execution!';
        return NULL;
    }
    
    // Return result
    $result_count = mysqli_affected_rows($db);

    if ($result_count === 1) {
        // User added
        return TRUE;
    }
    else {
        // User NOT added (probably author already exists)
        return FALSE;
    }
}

function get_comments_for_book(&$error, $db, $book_id, $order_by = NULL) {
    // Clear the error container
    $error = NULL;
    
    // Make sure the id is int
    $book_id = intval($book_id, 10);
    
    // Validate
    $is_book_exists = is_book_id_exists($error, $db, $book_id);
    if (isset($error)) {
        // No need to set $error - it's already set by previous function!
        return NULL;
    }
    if (!$is_book_exists) {
        $error = 'Param Error. Book Id does NOT exist in the DB!';
        return NULL;
    }
    
    // Decide Sorting
    $sort = NULL;
    
    if (isset($order_by)) {
        $lower_order_by = mb_strtolower($order_by);
        switch ($lower_order_by) {
            case 'ascending':
            case 'asc':
                $sort = 'ASC';
                break;

            case 'descending':
            case 'desc':
                $sort = 'DESC';
                break;

            default:
                $sort = NULL;
                break;
        }
    }
    
    // Construct query
    $query = '
        SELECT u.user_id, c.comment_id,
            u.user_username, c.comment_timestamp, c.comment_text
        FROM comments as c
            INNER JOIN users as u
                ON c.comment_user_id = u.user_id
        WHERE c.comment_book_id = ?';
    if (isset($sort)) {
        $query = $query.PHP_EOL.'ORDER BY c.comment_timestamp '.$sort;
    }
    
    $statement = mysqli_prepare($db, $query);
    if (!$statement) {
        $error = 'DB Error. Wrong query!';
        
        if (IS_DEBUG_MODE) {
            var_dump($query);
        }
        
        return NULL;
    }
    
    // Param Binding
    $bind_successful = mysqli_stmt_bind_param($statement, 'i', $book_id);
    if (!$bind_successful) {
        $error = 'DB Error. Wrong param bind!';
        return NULL;
    }
    
    // Execute query
    $execution_successful = mysqli_stmt_execute($statement);
    if (!$execution_successful) {
        $error = 'DB Error. Failed query execution!';
        return NULL;
    }
    
    // Return result
    mysqli_stmt_bind_result($statement, $user_id, $comment_id,
            $user_username, $comment_timestamp, $comment_text);
    $result = [];
    while ($row = mysqli_stmt_fetch($statement)) {
        $result[$comment_timestamp] = [
            'user_id' => $user_id,
            'comment_id' => $comment_id,
            'username' => $user_username,
            'timestamp' => $comment_timestamp,
            'text' => $comment_text,
        ];
    }
    
    return $result;
}