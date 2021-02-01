<?php 
require_once ('../../config.php');
require_once ('vistas.php');


global $USER, $DB, $COURSE, $OUTPUT, $CFG;
if ($CFG->forcelogin) {
   require_login();
}

$site = get_site();
//$PAGE->set_context($context);
//$PAGE->set_url('/my/index.php', $params);
$PAGE->set_pagelayout('admin');
$PAGE->set_title($title);
$PAGE->set_heading($fullname);
echo $OUTPUT->header();
echo $estilos;
echo $teamtitulo;

// Aqui finaliza en proceso
//echo $OUTPUT->footer();



