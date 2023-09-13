<?php
require_once('../../config.php');
$PAGE->requires->css('/local/certificateblockchain/styles.css');

// Ensure the user is logged in.
require_login();

// Fetch the course ID from the URL parameters (optional).
$courseid = optional_param('courseid', null, PARAM_INT);

// Define the number of records per page.
$perpage = 10;

// Get the current page number.
$page = optional_param('page', 0, PARAM_INT);

// Page setup
$PAGE->set_url('/local/certificateblockchain/view.php');
$PAGE->set_title(get_string('viewcertificates', 'local_certificateblockchain'));
$PAGE->set_heading(get_string('viewcertificates', 'local_certificateblockchain'));
$PAGE->set_context(context_system::instance());

echo $OUTPUT->header();

// Fetch the certificates for the user.
global $DB, $USER;
$conditions = ['userid' => $USER->id];
if ($courseid) {
    $conditions['courseid'] = $courseid;
}
$startfrom = $page * $perpage;
$certificates = $DB->get_records('local_certificateblockchain', $conditions, '', '*', $startfrom, $perpage);

// Display the certificates.
if ($certificates) {
    echo html_writer::start_tag('table', ['class' => 'generaltable boxaligncenter']);
    echo html_writer::start_tag('tr');
    echo html_writer::tag('th', get_string('course'));
    echo html_writer::tag('th', 'IPFS Link');
    echo html_writer::tag('th', 'Blockchain Reference');
    echo html_writer::tag('th', 'Details');
    echo html_writer::end_tag('tr');

    foreach ($certificates as $cert) {
        $course = $DB->get_record('course', ['id' => $cert->courseid]);
        echo html_writer::start_tag('tr');
        echo html_writer::tag('td', $course->fullname);
        echo html_writer::tag('td', html_writer::link("https://ipfs.io/ipfs/{$cert->ipfshash}", 'View on IPFS', ['target' => '_blank']));
        echo html_writer::tag('td', html_writer::link("https://etherscan.io/tx/{$cert->blockchainref}", 'View on Etherscan', ['target' => '_blank']));
        echo html_writer::tag('td', html_writer::link(new moodle_url('/local/certificateblockchain/detail.php', ['id' => $cert->id]), 'View Details'));
        echo html_writer::end_tag('tr');
    }

    echo html_writer::end_tag('table');

    $totalcertificates = $DB->count_records('local_certificateblockchain', $conditions);
    echo $OUTPUT->paging_bar($totalcertificates, $page, $perpage, new moodle_url('/local/certificateblockchain/view.php'));
} else {
    echo html_writer::tag('p', 'No certificates issued yet.');
}

echo $OUTPUT->footer();
?>
