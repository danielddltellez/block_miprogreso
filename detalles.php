<?php 
require_once ('../../config.php');

global $USER, $DB, $COURSE, $OUTPUT, $CFG;
if ($CFG->forcelogin) {
   require_login();
}

$site = get_site();
$PAGE->set_context($context);
$PAGE->set_pagelayout('admin');
$PAGE->set_title($title);
$PAGE->set_heading($fullname);
$PAGE->blocks->add_region('content');
echo $OUTPUT->header();


$sql="select 
    c.id AS 'courseid',
    c.fullname AS 'coursename',
    From_unixtime(ue.timestart) AS 'tiempoini',
    From_unixtime(ue.timeend) AS 'tiempofin',
    NOW() AS 'fechaact',
    IFNULL((SELECT 
                    COUNT(cmm.id)
                FROM
                     {course_modules_completion} cmm
                        JOIN
                     {course_modules} mcm ON mcm.id = cmm.coursemoduleid
                WHERE
                    cmm.userid = u.id AND mcm.course = c.id
                        AND mcm.visible = 1),
            0) AS 'activitiescompleted',
    IFNULL((SELECT 
                    COUNT(cmc.id)
                FROM
                     {course_modules} cmc
                WHERE
                    cmc.course = c.id
                        AND cmc.completion IN (1 , 2)
                        AND cmc.visible = 1),
            0) AS 'activitiesassigned',
    (SELECT 
            IF(activitiesassigned != 0,
                    (SELECT 
                            IF(activitiescompleted = activitiesassigned,
                                    'finalizado',
                                    'nofinalizado')
                        ),
                    'n/a')
        ) AS 'estatus',
    (SELECT
           IF(tiempofin != '1969-12-31 18:00:00',
                   (SELECT
                           IF(tiempofin > fechaact,
                                   'vigente',
                                   'novigente')
                       ),
                   'sinfin')
       ) AS 'vigencia'
FROM
     {user} u
		JOIN
     {user_enrolments} ue ON ue.userid = u.id
        JOIN
     {enrol} e ON e.id = ue.enrolid
        JOIN
     {course} c ON c.id = e.courseid
        JOIN
     {course_categories} cc ON cc.id = c.category
        JOIN
     {context} AS ctx ON ctx.instanceid = c.id
        JOIN
     {role_assignments} AS ra ON ra.contextid = ctx.id
        JOIN
     {role} AS r ON r.id = e.roleid
WHERE
    ra.userid = u.id
        AND ctx.instanceid = c.id
        AND ra.roleid = 5
        AND c.visible = 1 
        AND u.id = ?
GROUP BY u.id , c.id , u.lastname , u.firstname , c.fullname , ue.timestart, tiempofin";

$records = $DB->get_records_sql($sql, array($USER->id));
//echo "hola".count($records);
$k=$j=$i=0;
foreach ($records as $value) {

    $curso=$value->courseid;

    if($value->estatus =='finalizado'){


      $i=$i+1;
      
    } elseif($value->estatus =='nofinalizado')
    {
      $j=$j+1;
    }else{}
}

$totalfin = $i;
$totalnfi = $j;

 
$finalizados=$sinfin=$nofinalizados=' ';
$vigente=$novigente=' ';

foreach ($records as $valueids) {

    $idcurso=$valueids->courseid;
 if($valueids->estatus =='nofinalizado'){
      //$nofinalizados=$nofinalizados.'<p><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcurso.'">'.$valueids->coursename.'</a><p>';
       if($valueids->vigencia =='sinfin')//SIn fecha de expiración, y vigente
        {
          $sinfin=$sinfin.'<li><p><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcurso.'">'.$valueids->coursename.'</a><p></li>';
        }elseif($valueids->vigencia =='novigente')//No finalizado y ya no vigente
          {
            $novigente=$novigente.'<li><p><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcurso.'">'.$valueids->coursename.'</a><p></li>';
          }
        else
          {//No lo finalizo y esta vigente
            $vigente=$vigente.'<li><p><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcurso.'">'.$valueids->coursename.'</a><p></li>';
          }
      }elseif($valueids->estatus =='finalizado')
          {
            $finalizados=$finalizados.'<li><p><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcurso.'">'.$valueids->coursename.'</a><p></li>';//Cursos finalizados
          }else{

          }

}

  echo "<style>
  #grafico {
	//height:; 
	width:33%;
	float: right;
  }
 
  #reporteCursos{
    display: inline-block; /* the default for span */
  	width: 33%;
  	float: left;
  }	
	#encurso{
	//display: inline; /* the default for span */
  	width: 33%;
  	/*float: right;*/
    /*height:100%;*/
    display: inline-block;
}
@media (max-width: 768px) {
    #encurso, #reporteCursos{
        display: block;
    }
    #encurso, #reporteCursos{
      flex: unset;
    }
    #encurso{
      float: left;
  }
}
/*@media (min-height: 450px){
	.chartjs-render-monitor{
		width:720px;
		height:360px;
	}
}*/

</style>";  
 
  echo "<div class='chart-area'>";
  	echo "<div id='reporteCursos'>";
  		echo "<div id='vista1'><h2 style='font-size:15px; color:#000!important;'>Cursos No Finalizados</h2><p style='padding-right: 15px;'>Al completar los cursos Pendientes Fuera de Tiempo, no acumularás monedas. <br>Sin embargo, es importante que concluyas debido a que será considerado para tu evaluación de desempeño.</p>";

  		echo "<div id='vista2'><h2 style='font-size:15px;'>En curso</h2>";
  		echo "<ul>$vigente</ul>";
  		echo "<ul>$sinfin</ul>";


  		echo "<br><h2 style='font-size:15px;'>Pendientes por finalizar</h2><ul>$novigente</ul></div>";
  //echo "</div>";
  //echo "<div class='reporteCursos'>"; 

  	echo "</div></div>";
  	echo "<div id='encurso'>";
  		echo "<div id='vista3'><h2 style='font-size:15px; color:#000!important;'>Cursos Finalizados</h2>";
  		echo "<ul>$finalizados</ul>";
  		echo "</div><div id='vista4'>";

$finaltotal = count($records);
//echo"<div class='chart-container' style='position: relative; height:40vh; width:80vw'>";
//echo"<canvas id='chart'></canvas>";

/*echo "<div id='row-fluid'>";*/
 
echo "</div></div>";
$chart = new core\chart_pie();
echo "<div id='grafico'>";
$s = new \core\chart_series("Cursos", [$totalfin,$totalnfi]);
$s->set_labels([$totalfin,$totalnfi]);
$chart->add_series($s);
$chart->set_labels(["Cursos finalizados", "Cursos no finalizados"]);
$chart->set_doughnut(true);
print($OUTPUT->render($chart));
echo "</div></div>";
/*
$chart = new core\chart_pie();

$s = new \core\chart_series("Cursos", [$totalfin,$totalnfi]);
$s->set_labels([$totalfin,$totalnfi]);
$chart->add_series($s);
$chart->set_labels(["Cursos finalizados", "Cursos no finalizados"]);
$chart->set_doughnut(true);
print($OUTPUT->render($chart)); 

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

 
