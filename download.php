<?php
// Set the correct password for your bookmark folder
$correct_password = "test";

// Get the password entered by the user via AJAX request
$user_password = $_POST['password'];

// Initialize session variables for tracking incorrect attempts and lockouts
session_start();
if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
}
if (!isset($_SESSION['lockout'])) {
    $_SESSION['lockout'] = 0;
}

// Check if the password is correct
if ($user_password == $correct_password) {
    // Generate a download link to the bookmark folder HTML file
    $file_url = "https://raw.githubusercontent.com/SchoolIzBoring/My-Bookmarklet-Folder-OP-/main/bookmarks_2_26_23.html";
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary");
    header("Content-disposition: attachment; filename=\"bookmark-folder.html\"");
    readfile($file_url);
    exit;
} else {
    // Increment the incorrect attempts counter
    $_SESSION['attempts']++;

    // Check if the user has exceeded the maximum number of attempts
    if ($_SESSION['attempts'] >= 10 && $_SESSION['lockout'] == 0) {
        // Set a lockout period of 1 minute
        $_SESSION['lockout'] = time() + 60;
        $_SESSION['attempts'] = 0;
        echo "Incorrect password. You have exceeded the maximum number of attempts. Please try again in 1 minute.";
        exit;
    } elseif ($_SESSION['attempts'] >= 5 && $_SESSION['lockout'] < time()) {
        // Set a lockout period of 5 minutes
        $_SESSION['lockout'] = time() + 300;
        $_SESSION['attempts'] = 0;
        echo "Incorrect password. You have exceeded the maximum number of attempts. Please try again in 5 minutes.";
        exit;
    } elseif ($_SESSION['attempts'] >= 5 && $_SESSION['lockout'] > time()) {
        // Display a message indicating the remaining lockout time
        $remaining_lockout = $_SESSION['lockout'] - time();
        echo "Incorrect password. You are currently locked out for " . $remaining_lockout . " seconds.";
        exit;
    } elseif ($_SESSION['attempts'] >= 3 && $_SESSION['lockout'] > time()) {
        // Display a message indicating that the user is permanently locked out
        echo "You have exceeded the maximum number of attempts and are permanently locked out.";
        exit;
    } else {
        // Display a generic error message
        echo "Incorrect password. Please try again.";
        exit;
    }
}
?>
