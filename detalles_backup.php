<?php 
require_once ('../../config.php');

global $USER, $DB, $COURSE, $OUTPUT, $CFG;
if ($CFG->forcelogin) {
   require_login();
}

$site = get_site();
$PAGE->set_pagelayout('admin');
$PAGE->set_title($title);
$PAGE->set_heading($fullname);
echo $OUTPUT->header();
?>
<head><link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"></head>
<?php


$sql="select c.id, CONCAT(cc.name,' - ',c.fullname) AS 'coursename', c.category, c.sortorder,curdate() as diaactual, FROM_UNIXTIME(ue.timestart, '%Y-%m-%d') as 'diferencia',
datediff(curdate() , FROM_UNIXTIME(ue.timestart, '%Y-%m-%d')) as dif, c.shortname, c.fullname, c.idnumber, c.startdate, c.visible, c.groupmode, c.groupmodeforce, c.cacherev, u.lastaccess,
IFNULL((SELECT COUNT(cmm.id) FROM mdl_course_modules_completion cmm JOIN mdl_course_modules mcm ON mcm.id = cmm.coursemoduleid WHERE cmm.userid = u.id AND mcm.course = c.id AND mcm.visible = 1 AND cmm.completionstate in (1,2)), 0) AS 'activitiescompleted',
IFNULL((SELECT COUNT(cmc.id) FROM mdl_course_modules cmc WHERE cmc.course = c.id AND cmc.completion IN (1 , 2) AND cmc.visible = 1 and cmc.deletioninprogress=0), 0) AS 'activitiesassigned',
(SELECT IF(activitiesassigned != 0,(SELECT IF(activitiescompleted = activitiesassigned,'finalizado','nofinalizado')),'n/a')) AS 'estatus' ,c.daysobj
FROM mdl_user u JOIN mdl_user_enrolments ue ON ue.userid = u.id JOIN mdl_enrol e ON e.id = ue.enrolid JOIN mdl_course c ON c.id = e.courseid JOIN mdl_course_categories cc ON cc.id = c.category
JOIN mdl_context AS ctx ON ctx.instanceid = c.id JOIN mdl_role_assignments AS ra ON ra.contextid = ctx.id JOIN mdl_role AS r ON r.id = e.roleid
WHERE ra.userid = u.id AND ctx.instanceid = c.id AND ra.roleid = 5 AND c.visible = 1  AND u.id = ? AND ue.status=0 AND UNIX_TIMESTAMP(curdate()) < ue.timeend AND UNIX_TIMESTAMP(now()) > ue.timestart
GROUP BY u.id , c.id , u.lastname , u.firstname , c.fullname , ue.timestart order by cc.id ASC";

$records = $DB->get_records_sql($sql, array($USER->id));

/*obtener total de cursos del ususario*/
/*Obtener total de cursos finalizados*/
$i=0;
$j=0;
$k=0;
foreach ($records as $value) {

   $curso=$value->id;

   if($value->estatus =='finalizado'){

    $finalizados=$finalizados.'<p style="font-size:12px;"><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$curso.'">'.$value->coursename.'</a><p>';
     $i=$i+1;
     
   }else if($value->estatus =='nofinalizado'){

     $difdias=$value->dif;
     $diasestablecidos=$value->daysobj;

     if($diasestablecidos>=$difdias){
       $k=$k+1;
       $enproceso=$enproceso.'<p style="font-size:12px;"><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$curso.'">'.$value->coursename.'</a><p>';
     }else{
       $j=$j+1;
       $nofinalizados=$nofinalizados.'<p style="font-size:12px;"><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$curso.'">'.$value->coursename.'</a><p>';

     }

   }else{

   }

$totalfin = $i;
$totalnfi = $j;
$totalp=$k;
}

  echo '<div class="w3-container w3-center" style="background-color:#e79888;">
            <h2 style="color: #fff;">Mi progreso</h2>
        </div>
        <div class="w3-row">
          <div class="w3-col s4">
          <h3 class="w3-center" style="background-color:#de2320; color: #fff;">Cursos no finalizados</h3>
            <p>'.$nofinalizados.'</p>
          </div>
          <div class="w3-col s4">
            <h3 class="w3-center" style="background-color:#ffbc00; color: #fff;">En proceso</h3>
            <p>'.$enproceso.'</p>
          </div>
          <div class="w3-col s4">
            <h3 class="w3-center" style="background-color:#6ecc12; color: #fff;">Cursos finalizados</h3>
            <p>'.$finalizados.'</p>
          </div>
        </div>';
  
/*
$finaltotal = count($records);
echo "<div class='grafico' >";
$chart = new core\chart_pie();
$s = new \core\chart_series("Cursos", [$totalfin,$totalnfi]);
$s->set_labels([$totalfin,$totalnfi]);
$chart->add_series($s);
$chart->set_labels(["Cursos finalizados", "Cursos no finalizados"]);
$chart->set_doughnut(true);
print($OUTPUT->render($chart)); 
echo "</div>";
*/


/*$PAGE->set_context($context);
$PAGE->set_url('/my/index.php', $params);
$PAGE->set_pagelayout('mydashboard');
$PAGE->set_pagetype('my-index');
$PAGE->blocks->add_region('content');
$PAGE->set_subpage($currentpage->id);
$PAGE->set_title($pagetitle);
$PAGE->set_heading($header);
*/


 echo $OUTPUT->footer();

 