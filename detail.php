<?php

require_once('../../config.php');

// Ensure the user is logged in.
require_login();

// Fetch the certificate ID from the URL parameters.
$certid = required_param('id', PARAM_INT);

// Fetch the certificate details.
global $DB;
$certificate = $DB->get_record('local_certificateblockchain', ['id' => $certid]);

// Check if the certificate exists and belongs to the user.
if (!$certificate || $certificate->userid != $USER->id) {
    print_error('Invalid certificate or access denied.');
    exit;
}

// Fetch associated course and user details.
$course = $DB->get_record('course', ['id' => $certificate->courseid]);
$user = $DB->get_record('user', ['id' => $certificate->userid]);

// Page setup.
$PAGE->set_url('/local/certificateblockchain/detail.php', ['id' => $certid]);
$PAGE->set_title(get_string('viewcertificates', 'local_certificateblockchain') . ' - ' . $course->fullname);
$PAGE->set_heading(get_string('viewcertificates', 'local_certificateblockchain') . ' - ' . $course->fullname);
$PAGE->set_context(context_system::instance());

echo $OUTPUT->header();

// Display certificate details.
echo html_writer::tag('h2', 'Certificate Details');
echo html_writer::tag('p', 'Student: ' . $user->firstname . ' ' . $user->lastname);
echo html_writer::tag('p', 'Course: ' . $course->fullname);
echo html_writer::tag('p', 'Date of Issuance: ' . userdate($certificate->timecreated));
echo html_writer::tag('p', html_writer::link("https://ipfs.io/ipfs/{$certificate->ipfshash}", 'View Certificate on IPFS', ['target' => '_blank']));
echo html_writer::tag('p', html_writer::link("https://etherscan.io/tx/{$certificate->blockchainref}", 'View Transaction on Etherscan', ['target' => '_blank']));

echo $OUTPUT->footer();

?>
