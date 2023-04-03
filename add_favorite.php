<?php
// Retrieve the current array from the cookie
$my_array = [];
$remove_id = 10;

if (isset($_COOKIE[ 'favorite' ])) {
    $my_array = unserialize($_COOKIE[ 'favorite' ]);
}

// Add an item to the array
$my_array[] = $_POST[ 'movieID' ];

$existing_cookie = unserialize($_COOKIE[ 'favorite' ]);
if (!in_array($_POST[ 'movieID' ], $existing_cookie)) {
    setcookie('favorite', serialize($my_array), time() + 3600, '/');
}
// Remove an item from the array
if (null !== $remove_id) {
    if (in_array($remove_id, $my_array)) {
        $index = array_search($remove_id, $my_array);
        unset($my_array[ $index ]);
        // Serialize the updated array and store it in the cookie
        setcookie('favorite', serialize($my_array), time() + 3600, '/');
    }
}
