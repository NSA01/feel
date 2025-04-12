<?php
require_once 'includes/functions.php';

// Logout user
logoutUser();

// Redirect to home page
header('Location: index.php');
exit;
?> 