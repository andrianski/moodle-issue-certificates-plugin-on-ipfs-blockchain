<?php
require_once('../../config.php');
require_once('lib.php');  // Where we'll put our custom functions.

// Ensure the user is logged in.
require_login();

// TODO:
// 1. Check if the user has completed the course.
// 2. Generate a certificate PDF.
// 3. Upload the PDF to IPFS and get the hash.
// 4. Store the certificate data on the Ethereum blockchain and get the reference.
// 5. Insert the IPFS hash and blockchain reference into the Moodle database.


// Fetch the course ID from the URL parameters.
$courseid = required_param('courseid', PARAM_INT);

// Check if the user has completed the course.
if (!certificateblockchain_has_completed_course($USER->id, $courseid)) {
    print_error('Course not completed');
    exit;
}

// Generate a certificate PDF.
$pdf_path = certificateblockchain_generate_pdf($USER->id, $courseid);

// Upload the PDF to IPFS and get the hash.
$ipfs_hash = certificateblockchain_upload_to_ipfs($pdf_path);

// Store the certificate data on the Ethereum blockchain and get the reference.
$blockchain_ref = certificateblockchain_store_on_blockchain([
    'studentName' => $USER->firstname . ' ' . $USER->lastname,
    'courseName' => get_course($courseid)->fullname,
    'date' => time(),
    'grade' => certificateblockchain_get_grade($USER->id, $courseid)
]);

// Insert the IPFS hash and blockchain reference into the Moodle database.
global $DB;
$record = new stdClass();
$record->userid = $USER->id;
$record->courseid = $courseid;
$record->ipfshash = $ipfs_hash;
$record->blockchainref = $blockchain_ref;
$record->timecreated = time();

$DB->insert_record('local_certificateblockchain', $record);

// Redirect the user to the view page or show a success message.
redirect(new moodle_url('/local/certificateblockchain/view.php', ['courseid' => $courseid]));

?>