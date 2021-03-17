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
 WHERE ra.userid = u.id AND ctx.instanceid = c.id AND ra.roleid = 5 AND c.visible = 1  AND u.id = ? AND ue.status=0 AND UNIX_TIMESTAMP(curdate()) < ue.timeend AND UNIX_TIMESTAMP(now()) > ue.timestart
 GROUP BY u.id , c.id , u.lastname , u.firstname , c.fullname , ue.timestart";

$records = $DB->get_records_sql($sql, array($USER->id));

$esql="select u.id, 
MAX(IF(f.shortname='Perfil', d.data, NULL)) as perfil
from {user} u 
join {user_info_data} d on d.userid=u.id
join {user_info_field} f on f.id=d.fieldid
where  u.id=?";
$inforol = $DB->get_records_sql($esql, array($USER->id));
foreach($inforol as $valrol){

  $rolusuario=$valrol->perfil;
}



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

$sumatotal=$totalp+$totalnfi+$totalfin;

/*$this->content->text .='<div class="chart-container"><canvas id="pie-chart-daniel"></canvas></div>';
$this->content->text .='<p>Total cursos:  '.$finaltotal.'</p>';
$this->content->text .='<p>Cursos  En proceso:  '.$totalp.'</p>';
$this->content->text .='<p>Cursos  No Finalizados:  '.$totalnfi.'</p>';
$this->content->text .='<p>Cursos Finalizados:  '.$totalfin.'</p>';
*/
$promedio1 = ($totalp * 100) /  $sumatotal;
$promedio2 = ($totalnfi * 100) /  $sumatotal;
$promedio3 = ($totalfin * 100) /  $sumatotal;

$promedio1=round($promedio1, 2);
$promedio2=round($promedio2, 2);
$promedio3=round($promedio3, 2);



$this->content->text .='<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"><div><div class="progress" style="height: 30px; background-color: #E6E6E6 !important;">
  <div
    class="progress-bar"
    role="progressbar"';
if(!empty($promedio1)){
  $this->content->text .='style="width: '.$promedio1.'%; background-color: #ffb624; color:#fff; font-weight: bold;"';
}else{
  $this->content->text .='style="width: '.$promedio1.'%; background-color: #ffb624; color:#9a9a9a; font-weight: bold;"';
}

$this->content->text .='aria-valuenow="'.$totalp.'"
    aria-valuemin="0"
    aria-valuemax="'.$sumatotal.'"
  >
  <p class="text-progreso">'.$totalp.'</p>
  </div>
</div>
<div class="titulo-progress"><a  href="'.$CFG->wwwroot.'/blocks/miprogreso/viewproceso.php"><i class="fa fa-search"></i> En proceso</a></div>
</div>';
$this->content->text .='<div><div class="progress" style="height: 30px; background-color: #E6E6E6 !important;">
  <div
    class="progress-bar"
    role="progressbar"';
    if(!empty($promedio2)){
      $this->content->text .='style="width: '.$promedio2.'%; background-color: #f42727; color:#fff; font-weight: bold;"';
    }else{
      $this->content->text .='style="width: '.$promedio2.'%; background-color: #f42727; color:#9a9a9a; font-weight: bold;"';
    }
    
$this->content->text .='aria-valuenow="'.$totalnfi.'"
    aria-valuemin="0"
    aria-valuemax="'.$sumatotal.'"
  >
  <p class="text-progreso">'.$totalnfi.'</p>
  </div>
</div>
<div class="titulo-progress"><a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewnofinalizado.php"><i class="fa fa-search"></i> No finalizado</a></div>
</div>';
$this->content->text .='<div><div class="progress" style="height: 30px; background-color: #E6E6E6  !important;">
  <div
    class="progress-bar"
    role="progressbar"';
    if(!empty($promedio3)){
      $this->content->text .='style="width: '.$promedio3.'%; background-color: #5cc600; color:#fff; font-weight: bold;"';
    }else{
      $this->content->text .='style="width: '.$promedio3.'%; background-color: #5cc600; color:#9a9a9a; font-weight: bold;"';
    }
    
$this->content->text .='aria-valuenow="'.$totalfin.'"
    aria-valuemin="0"
    aria-valuemax="'.$sumatotal.'"
  >
  <p class="text-progreso">'.$totalfin.'</p>
  </div>
</div>
<div class="titulo-progress"><a  href="'.$CFG->wwwroot.'/blocks/miprogreso/viewfinalizado.php"><i class="fa fa-search"></i> Finalizado</a></div>
</div>
<br><center><p>Total de cursos: '.$finaltotal.'</p></center><br>';
if($rolusuario=='JEFE INMEDIATO'){
$this->content->text .='<div style="top: -19px; position: relative; text-align: center; font-size: 17px;">
<a style="color: #636363" href="'.$CFG->wwwroot.'/blocks/miprogreso/team.php"><i class="glyphicon glyphicon-user" style="color:#e69987"></i>  Seguimiento a mi equipo</a></div>
</div>';
}

$this->content->text .='<style>
.titulo-progress{
  top: -19px;
  position: relative; 
  text-align: center; 
  font-size: 17px;
}
.titulo-progress > a{
  color: #636363;
}
.titulo-progress > a:hover{
  color: #e69987 !important;
}
.text-progreso{

  width: 100% !important;
  position: absolute;
   margin-left: 5%;
   margin-top: 2%;

}

.progress-bar {

  text-align: left !important;

}

.block_miprogreso > .content{
  border: solid 10px #e6e6e6 !important;
  border-bottom: solid 27px #e6e6e6 !important;
}


</style>';


/*
$this->content->text .='<div><div><a href="'.$CFG->wwwroot.'/blocks/miprogreso/detalles.php">Consultar detalles...</a></div><div>';
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
            fontFamily: "Gilroy-Light"
        }
     }
      
    }
});


  

</script>';
*/
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
