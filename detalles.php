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

$sql="select c.id as idcurso, c.fullname AS 'coursename', c.category, c.sortorder,curdate() as diaactual, FROM_UNIXTIME(ue.timestart, '%Y-%m-%d') as 'diferencia',
datediff(curdate() , FROM_UNIXTIME(ue.timestart, '%Y-%m-%d')) as dif, c.shortname, c.fullname, c.idnumber, c.startdate, c.visible, c.groupmode, c.groupmodeforce, c.cacherev, u.lastaccess,
IFNULL((SELECT COUNT(cmm.id) FROM {course_modules_completion} cmm JOIN {course_modules} mcm ON mcm.id = cmm.coursemoduleid WHERE cmm.userid = u.id AND mcm.course = c.id AND mcm.visible = 1 AND cmm.completionstate in (1,2)), 0) AS 'activitiescompleted',
IFNULL((SELECT COUNT(cmc.id) FROM {course_modules} cmc WHERE cmc.course = c.id AND cmc.completion IN (1 , 2) AND cmc.visible = 1 and cmc.deletioninprogress=0), 0) AS 'activitiesassigned',
(SELECT IF(activitiesassigned != 0,(SELECT IF(activitiescompleted = activitiesassigned,'finalizado',(SELECT IF(dif <= c.daysobj,'enproceso','nofinalizado')))),'n/a')) 
  AS 'estatus' ,c.daysobj
FROM {user} u JOIN {user_enrolments} ue ON ue.userid = u.id JOIN {enrol} e ON e.id = ue.enrolid JOIN {course} c ON c.id = e.courseid JOIN {course_categories} cc ON cc.id = c.category
JOIN {context} AS ctx ON ctx.instanceid = c.id JOIN {role_assignments} AS ra ON ra.contextid = ctx.id JOIN {role} AS r ON r.id = e.roleid
WHERE ra.userid = u.id AND ctx.instanceid = c.id AND ra.roleid = 5 AND c.visible = 1  AND u.id = ? AND ue.status=0 AND UNIX_TIMESTAMP(curdate()) < ue.timeend
GROUP BY u.id , c.id , u.lastname , u.firstname , c.fullname , ue.timestart order by cc.id ASC";

$records = $DB->get_records_sql($sql, array($USER->id));

$finalizados=[];
$enproceso=[];
$nofinalizados=[];
$i=0;
$j=0;
$k=0;

foreach ($records as $value) {
$idcurso=$value->idcurso;
$estatus=$value->estatus;
$categoria=$value->category;
$nombrecurso=$value->coursename;
   if($estatus=='finalizado'){
      $finalizados[$i]['id']=$idcurso;
      $finalizados[$i]['categoria']=$categoria;
      $finalizados[$i]['nombrecurso']=$nombrecurso;
      $i++;
   }
   if($estatus=='enproceso'){
      $enproceso[$j]['id']=$idcurso;
      $enproceso[$j]['categoria']=$categoria;
      $enproceso[$j]['nombrecurso']=$nombrecurso;
      $j++;

   }
   if($estatus=='nofinalizado'){
      $nofinalizados[$k]['id']=$idcurso;
      $nofinalizados[$k]['categoria']=$categoria;
      $nofinalizados[$k]['nombrecurso']=$nombrecurso;
      $k++;

   }

}
/*
print_r($finalizados);
echo '<br>';
print_r($enproceso);
echo '<br>';
print_r($nofinalizados);
echo '<br>';
exit();*/
//aqui empieza finalizados
echo $cabezerafinalizados;
$categoria1=[];
//agrupar();
$categoria2=[];
//agrupar();
$categoria3=[];
//agrupar();
$categoria4=[];
//agrupar();
$categoria5=[];
//agrupar();
$categoria6=[];
//agrupar();


$a=0;
$b=0;
$c=0;
$d=0;
$e=0;
$f=0;

//print_r($finalizados);
foreach($finalizados as $values){
   
   $idc=$values['id'];
   //echo '<br>';
   $idcat=$values['categoria'];
   //echo '<br>';
   $namecourse=$values['nombrecurso'];
   //echo '<br>';
   if($idcat=='66'){
      $categoria1[$a]['idcurso']=$idc;
      $categoria1[$a]['nombrecurso']=$namecourse;
      $a++;

   }else if($idcat=='67'){
      $categoria2[$b]['idcurso']=$idc;
      $categoria2[$b]['nombrecurso']=$namecourse;
      $b++;
   }else if($idcat=='68'){
      $categoria3[$c]['idcurso']=$idc;
      $categoria3[$c]['nombrecurso']=$namecourse;
      $c++;
   }else if($idcat=='69'){
      $categoria4[$d]['idcurso']=$idc;
      $categoria4[$d]['nombrecurso']=$namecourse;
      $d++;
   }else if($idcat=='70'){
      $categoria5[$e]['idcurso']=$idc;
      $categoria5[$e]['nombrecurso']=$namecourse;
      $e++;
   }else if($idcat=='71'){
      $categoria6[$f]['idcurso']=$idc;
      $categoria6[$f]['nombrecurso']=$namecourse;
      $f++;
   }else{

   }

}
echo "<div class='w3-container'>
      <div class='w3-row ajustado'>";
if(!empty($categoria1)){
   echo "<div class='w3-col l4 s12 m4 w3-center content-block punteado'>
   <img src='img/carpeta_-66.png' class='imgprogreso'><div class='cursoselearning'>";
   foreach($categoria1 as $valores1){

      $idcursof=$valores1['idcurso'];
      $namecoursef=$valores1['nombrecurso'];

      echo '<p><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursof.'">'.$namecoursef.'</a><p>';

   } 
   echo"</div></div>";
   echo $espacio_responsivo;
   
}

if (!empty($categoria2)) {
   echo "<div class='w3-col l4 s12 m4 w3-center content-block punteado'>
   <img src='img/carpeta_-67.png' class='imgprogreso'><div class='cursoselearning'>";
   foreach($categoria2 as $valores2){

      $idcursof2=$valores2['idcurso'];
      $namecoursef2=$valores2['nombrecurso'];

      echo '<p><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursof2.'">'.$namecoursef2.'</a><p>';

   }
   echo"</div></div>";
   echo $espacio_responsivo;
} 

if (!empty($categoria3)) {
   echo "<div class='w3-col l4 s12 m4 w3-center content-block punteado'>
   <img src='img/carpeta_-68.png' class='imgprogreso'><div class='cursoselearning'>";
   foreach($categoria3 as $valores3){

      $idcursof3=$valores3['idcurso'];
      $namecoursef3=$valores3['nombrecurso'];

      echo '<p><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursof3.'">'.$namecoursef3.'</a><p>';

   }
   echo"</div></div>";
   echo $espacio_responsivo;
} 
   
//echo'</div><div class="w3-row ajustado">';//

if (!empty($categoria4)) {
   echo "<div class='w3-col l4 s12 m4 w3-center content-block punteado'>
   <img src='img/carpeta_-69.png' class='imgprogreso'><div class='cursoselearning'>";
   foreach($categoria4 as $valores4){

      $idcursof4=$valores4['idcurso'];
      $namecoursef4=$valores4['nombrecurso'];

      echo '<p><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursof4.'">'.$namecoursef4.'</a><p>';

   }
   echo"</div></div>";
   echo $espacio_responsivo;
}

if (!empty($categoria5)) {
   echo "<div class='w3-col l4 s12 m4 w3-center content-block punteado'>
   <img src='img/carpeta_-70.png' class='imgprogreso'><div class='cursoselearning'>";
   foreach($categoria5 as $valores5){

      $idcursof5=$valores5['idcurso'];
      $namecoursef5=$valores5['nombrecurso'];

      echo '<p><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursof5.'">'.$namecoursef5.'</a><p>';

   }
   echo"</div></div>";
   echo $espacio_responsivo;

}

if (!empty($categoria6)) {
   echo "<div class='w3-col l4 s12 m4 w3-center content-block punteado'>
   <img src='img/carpeta_-71.png' class='imgprogreso'><div class='cursoselearning'>";
   foreach($categoria6 as $valores6){

      $idcursof6=$valores6['idcurso'];
      $namecoursef6=$valores6['nombrecurso'];

      echo '<p><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursof6.'">'.$namecoursef6.'</a><p>';

   }
   echo"</div></div>";
   echo $espacio_responsivo;
}
echo'</div></div>';//finalizaf

//aqui termina finalizados

// cursoselearning no finalizados 
// 
//echo "<div class='w3-col s12 w3-center'>
  //    <p>&nbsp;</p>
    //  <p>&nbsp;</p>
//</div>";
echo $cabeceranofinalizados;
$categoria1n=[];
//agrupar();
$categoria2n=[];
//agrupar();
$categoria3n=[];
//agrupar();
$categoria4n=[];
//agrupar();
$categoria5n=[];
//agrupar();
$categoria6n=[];
//agrupar();


$an=0;
$bn=0;
$cn=0;
$dn=0;
$en=0;
$fn=0;

//print_r($nofinalizados);// Inicia no finalizados
foreach($nofinalizados as $values1){
   
   $idc1=$values1['id'];
   //echo '<br>';
   $idcat1=$values1['categoria'];
   //echo '<br>';
   $namecourse1=$values1['nombrecurso'];
   //echo '<br>';
   if($idcat1=='66'){
      $categoria1n[$an]['idcurso']=$idc1;
      $categoria1n[$an]['nombrecurso']=$namecourse1;
      $an++;

   }else if($idcat1=='67'){
      $categoria2n[$bn]['idcurso']=$idc1;
      $categoria2n[$bn]['nombrecurso']=$namecourse1;
      $bn++;
   }else if($idcat1=='68'){
      $categoria3n[$cn]['idcurso']=$idc1;
      $categoria3n[$cn]['nombrecurso']=$namecourse1;
      $cn++;
   }else if($idcat1=='69'){
      $categoria4n[$dn]['idcurso']=$idc1;
      $categoria4n[$dn]['nombrecurso']=$namecourse1;
      $dn++;
   }else if($idcat1=='70'){
      $categoria5n[$en]['idcurso']=$idc1;
      $categoria5n[$en]['nombrecurso']=$namecourse1;
      $en++;
   }else if($idcat1=='71'){
      $categoria6n[$fn]['idcurso']=$idc1;
      $categoria6n[$fn]['nombrecurso']=$namecourse1;
      $fn++;
   }else{

   }

}
echo "<div class='w3-container'>
      <div class='w3-row ajustado'>";
if(!empty($categoria1n)){
   echo "<div class='w3-col l4 s12 m4 w3-center content-block punteado'>
   <img src='img/carpeta_-66.png' class='imgprogreso'><div class='cursoselearning'>";
   foreach($categoria1n as $valores1n){

      $idcursofn=$valores1n['idcurso'];
      $namecoursefn=$valores1n['nombrecurso'];

      echo '<p><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursofn.'">'.$namecoursefn.'</a><p>';

   } 
   echo"</div></div>";
   echo $espacio_responsivo;
   
}

if (!empty($categoria2n)) {
   echo "<div class='w3-col l4 s12 m4 w3-center content-block punteado'>
   <img src='img/carpeta_-67.png' class='imgprogreso'><div class='cursoselearning'>";
   foreach($categoria2n as $valores2n){

      $idcursof2n=$valores2n['idcurso'];
      $namecoursef2n=$valores2n['nombrecurso'];

      echo '<p><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursof2n.'">'.$namecoursef2n.'</a><p>';

   }
   echo"</div></div>";
   echo $espacio_responsivo;
} 

if (!empty($categoria3n)) {
   echo "<div class='w3-col l4 s12 m4 w3-center content-block punteado'>
   <img src='img/carpeta_-68.png' class='imgprogreso'><div class='cursoselearning'>";
   foreach($categoria3n as $valores3n){

      $idcursof3n=$valores3n['idcurso'];
      $namecoursef3n=$valores3n['nombrecurso'];

      echo '<p><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursof3n.'">'.$namecoursef3n.'</a><p>';

   }
   echo"</div></div>";
   echo $espacio_responsivo;
} 
   
//echo'</div><div class="w3-row ajustado">';//

if (!empty($categoria4n)) {
   echo "<div class='w3-col l4 s12 m4 w3-center content-block punteado'>
   <img src='img/carpeta_-69.png' class='imgprogreso'><div class='cursoselearning'>";
   foreach($categoria4n as $valores4n){

      $idcursof4n=$valores4n['idcurso'];
      $namecoursef4n=$valores4n['nombrecurso'];

      echo '<p><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursof4n.'">'.$namecoursef4n.'</a><p>';

   }
   echo"</div></div>";
   echo $espacio_responsivo;
}

if (!empty($categoria5n)) {
   echo "<div class='w3-col l4 s12 m4 w3-center content-block punteado'>
   <img src='img/carpeta_-70.png' class='imgprogreso'><div class='cursoselearning'>";
   foreach($categoria5n as $valores5n){

      $idcursof5n=$valores5n['idcurso'];
      $namecoursef5n=$valores5n['nombrecurso'];

      echo '<p><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursof5n.'">'.$namecoursef5n.'</a><p>';

   }
   echo"</div></div>";
   echo $espacio_responsivo;

}

if (!empty($categoria6n)) {
   echo "<div class='w3-col l4 s12 m4 w3-center content-block punteado'>
   <img src='img/carpeta_-71.png' class='imgprogreso'><div class='cursoselearning'>";
   foreach($categoria6n as $valores6n){

      $idcursof6n=$valores6n['idcurso'];
      $namecoursef6n=$valores6n['nombrecurso'];

      echo '<p><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursof6n.'">'.$namecoursef6n.'</a><p>';

   }
   echo"</div></div>";
   echo $espacio_responsivo;
}

echo'</div></div>';//finalizaf
// Aqui inicia En proceso

echo $cabeceraenproceso;
$categoria1e=[];
//agrupar();
$categoria2e=[];
//agrupar();
$categoria3e=[];
//agrupar();
$categoria4e=[];
//agrupar();
$categoria5e=[];
//agrupar();
$categoria6e=[];
//agrupar();


$en=0;
$be=0;
$ce=0;
$de=0;
$ee=0;
$fe=0;

//print_r($nofinalizados);// Inicia no finalizados
foreach($enproceso as $values3){
   
   $idc3=$values3['id'];
   //echo '<br>';
   $idcat3=$values3['categoria'];
   //echo '<br>';
   $namecourse3=$values3['nombrecurso'];
   //echo '<br>';
   if($idcat3=='66'){
      $categoria1e[$ae]['idcurso']=$idc3;
      $categoria1e[$ae]['nombrecurso']=$namecourse3;
      $ae++;

   }else if($idcat3=='67'){
      $categoria2e[$be]['idcurso']=$idc3;
      $categoria2e[$be]['nombrecurso']=$namecourse3;
      $be++;
   }else if($idcat3=='68'){
      $categoria3e[$ce]['idcurso']=$idc3;
      $categoria3e[$ce]['nombrecurso']=$namecourse3;
      $ce++;
   }else if($idcat3=='69'){
      $categoria4e[$de]['idcurso']=$idc3;
      $categoria4e[$de]['nombrecurso']=$namecourse3;
      $de++;
   }else if($idcat3=='70'){
      $categoria5e[$ee]['idcurso']=$idc3;
      $categoria5e[$ee]['nombrecurso']=$namecourse3;
      $ee++;
   }else if($idcat3=='71'){
      $categoria6e[$fe]['idcurso']=$idc3;
      $categoria6e[$fe]['nombrecurso']=$namecourse3;
      $fe++;
   }else{

   }

}
echo "<div class='w3-container'>
      <div class='w3-row ajustado'>";
if(!empty($categoria1e)){
   echo "<div class='w3-col l4 s12 m4 w3-center content-block punteado'>
   <img src='img/carpeta_-66.png' class='imgprogreso'><div class='cursoselearning'>";
   foreach($categoria1e as $valores1e){

      $idcursofe=$valores1e['idcurso'];
      $namecoursefe=$valores1e['nombrecurso'];

      echo '<p><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursofe.'">'.$namecoursefe.'</a><p>';

   } 
   echo"</div></div>";
   echo $espacio_responsivo;
   
}

if (!empty($categoria2e)) {
   echo "<div class='w3-col l4 s12 m4 w3-center content-block punteado'>
   <img src='img/carpeta_-67.png' class='imgprogreso'><div class='cursoselearning'>";
   foreach($categoria2e as $valores2e){

      $idcursof2e=$valores2e['idcurso'];
      $namecoursef2e=$valores2e['nombrecurso'];

      echo '<p><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursof2e.'">'.$namecoursef2e.'</a><p>';

   }
   echo"</div></div>";
   echo $espacio_responsivo;
} 

if (!empty($categoria3e)) {
   echo "<div class='w3-col l4 s12 m4 w3-center content-block punteado'>
   <img src='img/carpeta_-68.png' class='imgprogreso'><div class='cursoselearning'>";
   foreach($categoria3e as $valores3e){

      $idcursof3e=$valores3e['idcurso'];
      $namecoursef3e=$valores3e['nombrecurso'];

      echo '<p><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursof3e.'">'.$namecoursef3e.'</a><p>';

   }
   echo"</div></div>";
   echo $espacio_responsivo;
} 
   
//echo'</div><div class="w3-row ajustado">';//

if (!empty($categoria4e)) {
   echo "<div class='w3-col l4 s12 m4 w3-center content-block punteado'>
   <img src='img/carpeta_-69.png' class='imgprogreso'><div class='cursoselearning'>";
   foreach($categoria4e as $valores4e){

      $idcursof4e=$valores4e['idcurso'];
      $namecoursef4e=$valores4e['nombrecurso'];

      echo '<p><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursof4e.'">'.$namecoursef4e.'</a><p>';

   }
   echo"</div></div>";
   echo $espacio_responsivo;
}

if (!empty($categoria5e)) {
   echo "<div class='w3-col l4 s12 m4 w3-center content-block punteado'>
   <img src='img/carpeta_-70.png' class='imgprogreso'><div class='cursoselearning'>";
   foreach($categoria5e as $valores5e){

      $idcursof5e=$valores5e['idcurso'];
      $namecoursef5e=$valores5e['nombrecurso'];

      echo '<p><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursof5e.'">'.$namecoursef5e.'</a><p>';

   }
   echo"</div></div>";
   echo $espacio_responsivo;

}

if (!empty($categoria6e)) {
   echo "<div class='w3-col l4 s12 m4 w3-center content-block punteado'>
   <img src='img/carpeta_-71.png' class='imgprogreso'><div class='cursoselearning'>";
   foreach($categoria6e as $valores6e){

      $idcursof6e=$valores6e['idcurso'];
      $namecoursef6e=$valores6e['nombrecurso'];

      echo '<p><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursof6e.'">'.$namecoursef6e.'</a><p>';

   }
   echo"</div></div>";
   echo $espacio_responsivo;
}

echo'</div></div><style>.cursoselearning{padding: 20% 20px 20px 20px !important;} .imgprogreso{padding-bottom: 0px !important;}</style>'; 

// Aqui finaliza en proceso
 echo $OUTPUT->footer();

 