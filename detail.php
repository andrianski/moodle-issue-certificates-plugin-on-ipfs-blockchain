<?php
require_once('../../config.php');

// Ensure the user is logged in.
require_login();

// Fetch the certificate ID from the URL parameters.
$certid = required_param('id', PARAM_INT);

// Fetch the certificate details.
global $DB;
$certificate = $DB->get_record('local_certificateblockchain', ['id' => $certid]);

// TODO: Display the certificate details.



?>