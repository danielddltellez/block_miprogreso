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
$idusuario=$USER->id;
$PAGE->set_pagelayout('admin');
$PAGE->set_title($title);
$PAGE->set_heading($fullname);
echo $OUTPUT->header();

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
if($rolusuario != 'JEFE INMEDIATO'){

	
	$my = new moodle_url('/my/');
	redirect($my);
	exit();


}
$btnregresa = new single_button(new moodle_url('/my/'),'Regresar', $buttonadd, 'get');
$btnregresa->class = 'regresar';
$btnregresa->formid = 'regresar';
echo $estilos;
echo $teamtitulo;
echo $OUTPUT->render($btnregresa);
echo $espacio_responsivo;
$sql="select u.id, 
concat(u.firstname,' ',u.lastname) as nombre,
u.email as correo
from {user} u 
join {user_info_data} d on d.userid=u.id
join {user_info_field} f on f.id=d.fieldid
where u.deleted=0 and u.suspended=0
and f.shortname='ApBoos' and d.data = (select 
MAX(IF(f.shortname='noEmpleado', d.data, NULL)) as numeroempjefediecto
from {user} u 
join {user_info_data} d on d.userid=u.id
join {user_info_field} f on f.id=d.fieldid
where u.deleted=0 and u.suspended=0
and u.id=?) 
GROUP BY u.id, nombre
order by nombre asc";
$listacolaboradores = $DB->get_records_sql($sql, array($idusuario));
echo '<div class="w3-container w3-responsive" style="width: 100%;">
<table class="w3-table-all">
  <thead>
    <tr class="w3-light-grey">
      <th>Colaborador</th>
      <th><div class="oval-proceso"></div><p>En proceso</p></th>
      <th><div class="oval-nfinalizado"></div><p>No finalizado</p></th>
      <th><div class="oval-finalizado"></div><p>Finalizado</p></th>
    </tr>
  </thead>';
foreach($listacolaboradores as $values){

   $idcolaborador=$values->id;
   $nombrecolaborado=$values->nombre;
   $esql="select
	c.id,
	c.category,
	c.sortorder,
	curdate() AS diaactual,
	datediff(curdate() , FROM_UNIXTIME(ue.timestart, '%Y-%m-%d')) as diferencia,
	datediff(
		curdate(),
		FROM_UNIXTIME(ue.timestart, '%Y-%m-%d')
	) AS dif,
	c.shortname,
	c.fullname,
	c.idnumber,
	c.startdate,
	c.visible,
	c.groupmode,
	c.groupmodeforce,
	c.cacherev,
	u.lastaccess,
c.daysobj,
	IFNULL(
		(
			SELECT
				COUNT(cmm.id)
			FROM
				{course_modules_completion} cmm
			JOIN {course_modules} mcm ON mcm.id = cmm.coursemoduleid
			WHERE
				cmm.userid = u.id
			AND mcm.course = c.id
			AND mcm.visible = 1
			AND cmm.completionstate IN (1, 2)
		),
		0
	) AS 'activitiescompleted',
	IFNULL(
		(
			SELECT
				COUNT(cmc.id)
			FROM
				{course_modules} cmc
			WHERE
				cmc.course = c.id
			AND cmc. COMPLETION IN (1, 2)
			AND cmc.visible = 1
			AND cmc.deletioninprogress = 0
		),
		0
	) AS 'activitiesassigned',
	(
		SELECT

		IF (
			activitiesassigned != 0,
			(
				SELECT

				IF (
					activitiescompleted = activitiesassigned,
					'finalizado',
					(SELECT IF(diferencia <= c.daysobj,'enproceso','nofinalizado'))
				)
			),
			'n/a'
		)
	) AS 'estatus'
FROM
	{user} u
JOIN {user_enrolments} ue ON ue.userid = u.id
JOIN {enrol} e ON e.id = ue.enrolid
JOIN {course} c ON c.id = e.courseid
JOIN {course_categories} cc ON cc.id = c.category
JOIN {context} AS ctx ON ctx.instanceid = c.id
JOIN {role_assignments} AS ra ON ra.contextid = ctx.id
JOIN {role} AS r ON r.id = e.roleid
WHERE
	ra.userid = u.id
AND ctx.instanceid = c.id
AND ra.roleid = 5
AND c.visible = 1
AND u.id = ?
AND ue. STATUS = 0
AND UNIX_TIMESTAMP(curdate()) < ue.timeend
AND UNIX_TIMESTAMP(now()) > ue.timestart
GROUP BY
	u.id,
	c.id,
	u.lastname,
	u.firstname,
	c.fullname,
	ue.timestart";
  
   $records = $DB->get_records_sql($esql, array($idcolaborador));
   $i=0;
   $j=0;
   $k=0;
   if(!empty($records)){
      foreach ($records as $value) {

         $curso=$value->courseid;

         if($value->estatus =='finalizado'){


            $i=$i+1;
            
         }else if($value->estatus =='nofinalizado'){


            $j=$j+1;
         

         }else if($value->estatus =='enproceso'){

            $k=$k+1;

         }else{

         }

         $totalfin = $i;
         $totalnfi = $j;
         $totalp=$k;
      }
   }else{
      
      $totalfin = 0;
      $totalnfi = 0;
      $totalp=0;

   }
  echo '<tr class="w3-hover-light-gray">
            <td><a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewproceso.php?idcolaborador='.$idcolaborador.'">'.$nombrecolaborado.'</a></td>
            <td><a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewproceso.php?idcolaborador='.$idcolaborador.'">'.$totalp.'</a></td>
            <td><a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewnofinalizado.php?idcolaborador='.$idcolaborador.'">'.$totalnfi.'</a></td>
            <td><a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewfinalizado.php?idcolaborador='.$idcolaborador.'">'.$totalfin.'</a></td>
         </tr>';


}
echo'</table>
</div>';



// Aqui finaliza en proceso
echo $OUTPUT->footer();



