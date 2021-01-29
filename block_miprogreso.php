<?php
class block_miprogreso extends block_base {
    public function init() {
        $this->title = get_string('miprogreso', 'block_miprogreso');
    }
    // The PHP tag and the curly bracket for the class definition 
    // will only be closed after there is another function added in the next section.
    public function get_content() {
        if ($this->content !== null) {
        return $this->content;
        }   
 
        /*$this->content         =  new stdClass;
        $this->content->text   = 'The content of our SimpleHTML block!';
            $this->content->footer = 'Footer here...';*/
        GLOBAL $DB, $USER, $OUTPUT, $CFG;

 $sql="select c.id, c.category, c.sortorder,curdate() as diaactual, FROM_UNIXTIME(ue.timestart, '%Y-%m-%d') as 'diferencia',
 datediff(curdate() , FROM_UNIXTIME(ue.timestart, '%Y-%m-%d')) as dif, c.shortname, c.fullname, c.idnumber, c.startdate, c.visible, c.groupmode, c.groupmodeforce, c.cacherev, u.lastaccess,
 IFNULL((SELECT COUNT(cmm.id) FROM {course_modules_completion} cmm JOIN {course_modules} mcm ON mcm.id = cmm.coursemoduleid WHERE cmm.userid = u.id AND mcm.course = c.id AND mcm.visible = 1 AND cmm.completionstate in (1,2)), 0) AS 'activitiescompleted',
 IFNULL((SELECT COUNT(cmc.id) FROM {course_modules} cmc WHERE cmc.course = c.id AND cmc.completion IN (1 , 2) AND cmc.visible = 1 and cmc.deletioninprogress=0), 0) AS 'activitiesassigned',
 (SELECT IF(activitiesassigned != 0,(SELECT IF(activitiescompleted = activitiesassigned,'finalizado','nofinalizado')),'n/a')) AS 'estatus' ,c.daysobj
 FROM {user} u JOIN {user_enrolments} ue ON ue.userid = u.id JOIN {enrol} e ON e.id = ue.enrolid JOIN {course} c ON c.id = e.courseid JOIN {course_categories} cc ON cc.id = c.category
 JOIN {context} AS ctx ON ctx.instanceid = c.id JOIN {role_assignments} AS ra ON ra.contextid = ctx.id JOIN {role} AS r ON r.id = e.roleid
 WHERE ra.userid = u.id AND ctx.instanceid = c.id AND ra.roleid = 5 AND c.visible = 1  AND u.id = ? AND ue.status=0 AND UNIX_TIMESTAMP(curdate()) < ue.timeend
 GROUP BY u.id , c.id , u.lastname , u.firstname , c.fullname , ue.timestart";

$records = $DB->get_records_sql($sql, array($USER->id));

/*obtener total de cursos del ususario*/
/*Obtener total de cursos finalizados*/
$i=0;
$j=0;
$k=0;
foreach ($records as $value) {

    $curso=$value->courseid;

    if($value->estatus =='finalizado'){


      $i=$i+1;
      
    }else if($value->estatus =='nofinalizado'){

      $difdias=$value->dif;
      $diasestablecidos=$value->daysobj;

      if($diasestablecidos>=$difdias){
        $k=$k+1;
      }else{
        $j=$j+1;
      }

    }else{

    }

$totalfin = $i;
$totalnfi = $j;
$totalp=$k;
}

$finaltotal = count($records);
/*
$chart = new core\chart_pie();
$s = new \core\chart_series("Cursos", [$totalfin,$totalnfi]);
$s->set_labels([$totalfin,$totalnfi]);
$chart->add_series($s);
$chart->set_labels(["Cursos finalizados", "Cursos no finalizados"]);
$chart->set_doughnut(true);
$this->content->text .= $OUTPUT->render($chart);
*/

$this->content->text .='<div class="chart-container"><canvas id="pie-chart-daniel"></canvas></div>';
$this->content->text .='<p>Total cursos:  '.$finaltotal.'</p>';
$this->content->text .='<p>Cursos  En proceso:  '.$totalp.'</p>';
$this->content->text .='<p>Cursos  No Finalizados:  '.$totalnfi.'</p>';
$this->content->text .='<p>Cursos Finalizados:  '.$totalfin.'</p>';
//$this->content->text .='<div><div style="color: #000 !important"><a href="'.$CFG->wwwroot.'/blocks/miprogreso/detalles.php">Consultar detalles...</a></div><div>';
$this->content->text .='<link rel="stylesheet" href="'.$CFG->wwwroot.'/blocks/miprogreso/css/Chart.css"><script src="'.$CFG->wwwroot.'/blocks/miprogreso/js/Chart.js" type="text/javascript"></script><script>
new Chart(document.getElementById("pie-chart-daniel"), {
    type: \'doughnut\',
    data: {
      labels: ["En proceso           ","Cursos no finalizados","Cursos finalizados   "],
      datasets: [{
        label: "Cursos",
        backgroundColor: [ "#FFBC00","#DE2320","#6ECC12"],
        data: ['.$totalp.' ,'.$totalnfi.','.$totalfin.']
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      legend: {
        labels: {
            // This more specific font property overrides the global property
            fontColor: \'#666\',
            fontSize: 13,
            //fontFamily: "Gilroy-Light"
        }
     }
      
    }
});


  

</script>';

/*foreach ($records as $valueids) {

    $idcurso=$valueids->courseid;


     if($valueids->estatus =='nofinalizado'){
     $this->content->text .='<p><a target="_blank" href="https://e-learning.triplei.mx/2546-TripleI0419/course/view.php?id='.$idcurso.'">'.$valueids->coursename.'</a><p>';
      }
    }
  $this->content->text .='</div></div>';*/



return $this->content;



    }
}
