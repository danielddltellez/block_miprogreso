<?php 
require_once ('../../config.php');



global $USER, $DB, $COURSE, $OUTPUT, $CFG;
if ($CFG->forcelogin) {
   require_login();
}
$idcolaborador=$_GET["idcolaborador"];
$site = get_site();
//$PAGE->set_context($context);
//$PAGE->set_url('/my/index.php', $params);
$PAGE->set_pagelayout('admin');
$PAGE->set_title($title);
$PAGE->set_heading($fullname);
echo $OUTPUT->header();
//echo $idcolaborador;


$btnregresa = new single_button(new moodle_url('/my/'),'Regresar', $buttonadd, 'get');
$btnregresa->class = 'regresar';
$btnregresa->formid = 'regresar';
$btncolaboradores = new single_button(new moodle_url('/blocks/miprogreso/team.php/'),'Colaboradores', $buttonadd, 'get');
$btncolaboradores->class = 'Colaboradores';
$btncolaboradores->formid = 'Colaboradores';


if(!empty($idcolaborador)){

   $sqlvalidacion="select u.id,  concat(u.firstname,' ',u.lastname) as nombre, u.email as correo
   from mdl_user u  join mdl_user_info_data d on d.userid=u.id join mdl_user_info_field f on f.id=d.fieldid
   where u.deleted=0 and u.suspended=0 and f.shortname='ApBoos' and d.data = (select 
   MAX(IF(f.shortname='noEmpleado', d.data, NULL)) as numeroempjefediecto
   from mdl_user u  join mdl_user_info_data d on d.userid=u.id join mdl_user_info_field f on f.id=d.fieldid where u.deleted=0 and u.suspended=0 and u.id=?) 
   and u.id=? GROUP BY u.id, nombre order by nombre asc";
   $validacionjefe = $DB->get_records_sql($sqlvalidacion, array($USER->id,$idcolaborador));
   if(empty($validacionjefe)){

      $my = new moodle_url('/blocks/miprogreso/team.php');
      redirect($my);
      exit();

   }

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

   $records = $DB->get_records_sql($sql, array($idcolaborador));
   // Aqui inicia En proceso
   echo $OUTPUT->render($btncolaboradores);
   require_once ('vistas.php');
   echo $estilos;
   echo $cabeceracolaborador;
   echo $espacio_responsivo;

}else{
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
   // Aqui inicia En proceso
   echo $OUTPUT->render($btnregresa);
   require_once ('vistas.php');
   echo $estilos;
   echo $cabeceraprincipal;
   echo $espacio_responsivo;

}

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

// Aqui inicia En proceso
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
foreach($nofinalizados as $values3){
   
   $idc3=$values3['id'];
   //echo '<br>';
   $idcat3=$values3['categoria'];
   //echo '<br>';
   $namecourse3=$values3['nombrecurso'];
   //echo '<br>';
   if($idcat3=='65'){
      $categoria1e[$ae]['idcurso']=$idc3;
      $categoria1e[$ae]['nombrecurso']=$namecourse3;
      $ae++;

   }else if($idcat3=='66'){
      $categoria2e[$be]['idcurso']=$idc3;
      $categoria2e[$be]['nombrecurso']=$namecourse3;
      $be++;
   }else if($idcat3=='67'){
      $categoria3e[$ce]['idcurso']=$idc3;
      $categoria3e[$ce]['nombrecurso']=$namecourse3;
      $ce++;
   }else if($idcat3=='68'){
      $categoria4e[$de]['idcurso']=$idc3;
      $categoria4e[$de]['nombrecurso']=$namecourse3;
      $de++;
   }else if($idcat3=='69'){
      $categoria5e[$ee]['idcurso']=$idc3;
      $categoria5e[$ee]['nombrecurso']=$namecourse3;
      $ee++;
   }else if($idcat3=='70'){
      $categoria6e[$fe]['idcurso']=$idc3;
      $categoria6e[$fe]['nombrecurso']=$namecourse3;
      $fe++;
   }else{

   }

}
echo "<div class='w3-container'>
      <div class='w3-row ajustado'>";
if(!empty($categoria1e)){
   
   echo "<div class='w3-col s12 m4 w3-center content-block punteado'>
            <img src='img/carpeta_-66.png' class='imgprogreso'>
               <div class='cursos-contenedor w3-center'>";
   $contador1=count($categoria1e);
   echo '<div class="caja1">
            <p>'.$contador1.'</p>
         </div>';
   echo '<div class="caja2">';
   $salida1e = array_slice($categoria1e, 0, 4); 

   foreach($salida1e as $valores1e){

      $idcursofe=$valores1e['idcurso'];
      $namecoursefe=$valores1e['nombrecurso'];
      echo '<p style="font-size: 12px;"><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursofe.'">'.$namecoursefe.'</a><p>';
      
   } 
   echo"</div><br style='clear:both'/>";
   echo  '<div class="popup" onclick="popuniversidad(1)">Ver mas..
            <span class="popuptext" id="myPopup_1">';
   foreach($categoria1e as $valorpop1e){

      $idcursofe=$valorpop1e['idcurso'];
      $namecoursefe=$valorpop1e['nombrecurso'];
      echo '<a class="texto-marca" target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursofe.'">'.$namecoursefe.'</a><br>';
      
   } 

   echo '</span></div>';
   echo"</div></div>";
   echo $espacio_responsivo;
   
}else{
   echo "<div class='w3-col s12 m4 w3-center content-block punteado'>
         <img src='img/carpeta_-66.png' class='imgprogreso'>
            <div class='cursos-contenedor w3-center'>";
   echo '<div class="caja1">
            <p>0</p>
         </div>';
   echo '<div class="caja2"><p style="font-size: 12px;">&nbsp;</p>';
   echo "</div><br style='clear:both'/>";
   echo"</div></div>";
   echo $espacio_responsivo;


}

if (!empty($categoria2e)) {
   echo "<div class='w3-col l4 s12 m4 w3-center content-block punteado'>
            <img src='img/carpeta_-67.png' class='imgprogreso'>
                  <div class='cursos-contenedor w3-center'>";
   $contador2=count($categoria2e);
   echo '<div class="caja1">
            <p>'.$contador2.'</p>
         </div>';
   echo '<div class="caja2">';
   $salida2e = array_slice($categoria2e, 0, 4); 
   foreach($salida2e as $valores2e){

      $idcursof2e=$valores2e['idcurso'];
      $namecoursef2e=$valores2e['nombrecurso'];

      echo '<p style="font-size: 12px;"><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursof2e.'">'.$namecoursef2e.'</a></p>';

   }
   echo"</div><br style='clear:both'/>";
   echo  '<div class="popup" onclick="popuniversidad(2)">Ver mas..
            <span class="popuptext" id="myPopup_2">';
   foreach($categoria2e as $valorpop2e){

      $idcursofe=$valorpop2e['idcurso'];
      $namecoursefe=$valorpop2e['nombrecurso'];
      echo '<a class="texto-marca" target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursofe.'">'.$namecoursefe.'</a><br>';
      
   } 

   echo '</span></div>';

   echo"</div></div>";
   echo $espacio_responsivo;
}else{
   echo "<div class='w3-col s12 m4 w3-center content-block punteado'>
         <img src='img/carpeta_-67.png' class='imgprogreso'>
            <div class='cursos-contenedor w3-center'>";
   echo '<div class="caja1">
            <p>0</p>
         </div>';
   echo '<div class="caja2"><p style="font-size: 12px;">&nbsp;</p>';
   echo "</div><br style='clear:both'/>";
   echo"</div></div>";
   echo $espacio_responsivo;


}

if (!empty($categoria3e)) {
   echo "<div class='w3-col l4 s12 m4 w3-center content-block punteado'>
         <img src='img/carpeta_-68.png' class='imgprogreso'>
            <div class='cursos-contenedor w3-center'>";
   $contador3=count($categoria3e);
   echo '<div class="caja1">
            <p>'.$contador3.'</p>
         </div>';
   echo '<div class="caja2">';
   $salida3e = array_slice($categoria3e, 0, 4); 
   foreach($salida3e as $valores3e){

      $idcursof3e=$valores3e['idcurso'];
      $namecoursef3e=$valores3e['nombrecurso'];

      echo '<p style="font-size: 12px;"><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursof3e.'">'.$namecoursef3e.'</a><p>';

   }
   echo"</div><br style='clear:both'/>";
   echo  '<div class="popup" onclick="popuniversidad(3)">Ver mas..
            <span class="popuptext" id="myPopup_3">';
   foreach($categoria3e as $valorpop3e){

      $idcursofe=$valorpop3e['idcurso'];
      $namecoursefe=$valorpop3e['nombrecurso'];
      echo '<a class="texto-marca" target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursofe.'">'.$namecoursefe.'</a><br>';
      
   } 

   echo '</span></div>';
   echo"</div></div>";
   echo $espacio_responsivo;
}else{
   echo "<div class='w3-col s12 m4 w3-center content-block punteado'>
         <img src='img/carpeta_-68.png' class='imgprogreso'>
            <div class='cursos-contenedor w3-center'>";
   echo '<div class="caja1">
            <p>0</p>
         </div>';
   echo '<div class="caja2"><p style="font-size: 12px;">&nbsp;</p>';
   echo "</div><br style='clear:both'/>";
   echo"</div></div>";
   echo $espacio_responsivo;


}

if (!empty($categoria4e)) {
   echo "<div class='w3-col l4 s12 m4 w3-center content-block punteado'>
            <img src='img/carpeta_-69.png' class='imgprogreso'>
               <div class='cursos-contenedor w3-center'>";
   $contador4=count($categoria4e);
   echo '<div class="caja1">
            <p>'.$contador4.'</p>
         </div>';
   echo '<div class="caja2">';
   $salida4e = array_slice($categoria4e, 0, 4); 
   foreach($salida4e as $valores4e){

      $idcursof4e=$valores4e['idcurso'];
      $namecoursef4e=$valores4e['nombrecurso'];

      echo '<p style="font-size: 12px;"><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursof4e.'">'.$namecoursef4e.'</a><p>';

   }
   echo"</div><br style='clear:both'/>";
   echo  '<div class="popup" onclick="popuniversidad(4)">Ver mas..
            <span class="popuptext" id="myPopup_4">';
   foreach($categoria4e as $valorpop4e){

      $idcursofe=$valorpop4e['idcurso'];
      $namecoursefe=$valorpop4e['nombrecurso'];
      echo '<a class="texto-marca" target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursofe.'">'.$namecoursefe.'</a><br>';
      
   } 
   echo '</span></div>';
   echo"</div></div>";
   echo $espacio_responsivo;
}else{
   echo "<div class='w3-col s12 m4 w3-center content-block punteado'>
         <img src='img/carpeta_-69.png' class='imgprogreso'>
            <div class='cursos-contenedor w3-center'>";
   echo '<div class="caja1">
            <p>0</p>
         </div>';
   echo '<div class="caja2"><p style="font-size: 12px;">&nbsp;</p>';
   echo "</div><br style='clear:both'/>";
   echo"</div></div>";
   echo $espacio_responsivo;


}

if (!empty($categoria5e)) {
   echo "<div class='w3-col l4 s12 m4 w3-center content-block punteado'>
            <img src='img/carpeta_-70.png' class='imgprogreso'>
               <div class='cursos-contenedor w3-center'>";
   $contador5=count($categoria5e);
   echo '<div class="caja1">
            <p>'.$contador5.'</p>
         </div>';
   echo '<div class="caja2">';
   $salida5e = array_slice($categoria5e, 0, 4); 
   foreach($salida5e as $valores5e){

      $idcursof5e=$valores5e['idcurso'];
      $namecoursef5e=$valores5e['nombrecurso'];

      echo '<p style="font-size: 12px;"><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursof5e.'">'.$namecoursef5e.'</a><p>';

   }
   echo"</div><br style='clear:both'/>";
   echo  '<div class="popup" onclick="popuniversidad(5)">Ver mas..
            <span class="popuptext" id="myPopup_5">';
   foreach($categoria5e as $valorpop5e){

      $idcursofe=$valorpop5e['idcurso'];
      $namecoursefe=$valorpop5e['nombrecurso'];
      echo '<a class="texto-marca" target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursofe.'">'.$namecoursefe.'</a><br>';
      
   } 
   echo '</span></div>';
   echo"</div></div>";
   echo $espacio_responsivo;

}else{
   echo "<div class='w3-col s12 m4 w3-center content-block punteado'>
         <img src='img/carpeta_-70.png' class='imgprogreso'>
            <div class='cursos-contenedor w3-center'>";
   echo '<div class="caja1">
            <p>0</p>
         </div>';
   echo '<div class="caja2"><p style="font-size: 12px;">&nbsp;</p>';
   echo "</div><br style='clear:both'/>";
   echo"</div></div>";
   echo $espacio_responsivo;


}

if (!empty($categoria6e)) {
   echo "<div class='w3-col l4 s12 m4 w3-center content-block punteado'>
            <img src='img/carpeta_-71.png' class='imgprogreso'>
               <div class='cursos-contenedor w3-center'>";
   $contador6=count($categoria6e);
   echo '<div class="caja1">
            <p>'.$contador6.'</p>
         </div>';
   echo '<div class="caja2">';
   $salida6e = array_slice($categoria6e, 0, 4); 
   foreach($salida6e as $valores6e){

      $idcursof6e=$valores6e['idcurso'];
      $namecoursef6e=$valores6e['nombrecurso'];

      echo '<p style="font-size: 12px;"><a target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursof6e.'">'.$namecoursef6e.'</a><p>';

   }
   echo"</div><br style='clear:both'/>";
   echo  '<div class="popup" onclick="popuniversidad(6)">Ver mas..
            <span class="popuptext" id="myPopup_6">';
   foreach($categoria6e as $valorpop6e){

      $idcursofe=$valorpop6e['idcurso'];
      $namecoursefe=$valorpop6e['nombrecurso'];
      echo '<a class="texto-marca" target="_blank" href="'.$CFG->wwwroot.'/course/view.php?id='.$idcursofe.'">'.$namecoursefe.'</a><br>';
      
   } 
   echo '</span></div>';
   echo"</div></div>";
   echo $espacio_responsivo;
}else{
   echo "<div class='w3-col s12 m4 w3-center content-block punteado'>
         <img src='img/carpeta_-71.png' class='imgprogreso'>
            <div class='cursos-contenedor w3-center'>";
   echo '<div class="caja1">
            <p>0</p>
         </div>';
   echo '<div class="caja2"><p style="font-size: 12px;">&nbsp;</p>';
   echo "</div><br style='clear:both'/>";
   echo"</div></div>";
   echo $espacio_responsivo;


}

// Aqui finaliza en proceso
// echo $OUTPUT->footer();

 