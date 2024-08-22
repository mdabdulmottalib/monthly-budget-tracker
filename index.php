<?php
// URL to redirect to
$url = 'https://nurulitpoint.xyz/public/?page=dashboard';

// Perform the redirection
header('Location: ' . $url);

// Ensure that no further code is executed after the redirection
exit;
?>
