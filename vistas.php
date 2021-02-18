<?php 
$estilos="<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8'>
<title>Mi progreso</title>
<link rel='stylesheet' href='css/style.css' type='text/css'/>
<link rel='stylesheet' href='css/w3.css' type='text/css'/>
<script src='js/miprogreso.js'></script>
</head>";

$cabeceraprincipal='<div class="w3-container w3-center">
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewfinalizado.php" class="flecha"><i class="fa fa-caret-left fa-2x pos" aria-hidden="true"></i></a>
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewfinalizado.php" class="w3-btn w3-green">Finalizado</a>
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewproceso.php" class="w3-btn w3-yellow bodersolido">En proceso</a>
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewnofinalizado.php" class="w3-btn w3-red">No finalizado</a>
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewnofinalizado.php" class="flecha"><i class="fa fa-caret-right fa-2x pos" aria-hidden="true"></i></a>
</div>';
$cabeceraprincipal1='<div class="w3-container w3-center">
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewnofinalizado.php" class="flecha"><i class="fa fa-caret-left fa-2x pos" aria-hidden="true"></i></a>
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewfinalizado.php" class="w3-btn w3-green bodersolido">Finalizado</a>
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewproceso.php" class="w3-btn w3-yellow">En proceso</a>
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewnofinalizado.php" class="w3-btn w3-red">No finalizado</a>
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewproceso.php" class="flecha"><i class="fa fa-caret-right fa-2x pos" aria-hidden="true"></i></a>
</div>';
$cabeceraprincipal2='<div class="w3-container w3-center">
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewproceso.php" class="flecha"><i class="fa fa-caret-left fa-2x pos" aria-hidden="true"></i></a>
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewfinalizado.php" class="w3-btn w3-green">Finalizado</a>
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewproceso.php" class="w3-btn w3-yellow">En proceso</a>
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewnofinalizado.php" class="w3-btn w3-red bodersolido">No finalizado</a>
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewfinalizado.php" class="flecha"><i class="fa fa-caret-right fa-2x pos" aria-hidden="true"></i></a>
</div>';
$cabeceracolaborador='<div class="w3-container w3-center">
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewfinalizado.php?idcolaborador='.$idcolaborador.'"  class="flecha"><i class="fa fa-caret-left fa-2x pos" aria-hidden="true"></i></a>
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewfinalizado.php?idcolaborador='.$idcolaborador.'" class="w3-btn w3-green">Finalizado</a>
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewproceso.php?idcolaborador='.$idcolaborador.'" class="w3-btn w3-yellow bodersolido">En proceso</a>
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewnofinalizado.php?idcolaborador='.$idcolaborador.'" class="w3-btn w3-red">No finalizado</a>
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewnofinalizado.php?idcolaborador='.$idcolaborador.'" class="flecha"><i class="fa fa-caret-right fa-2x pos" aria-hidden="true"></i></a>
</div>';
$cabeceracolaborador1='<div class="w3-container w3-center">
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewnofinalizado.php?idcolaborador='.$idcolaborador.'"  class="flecha"><i class="fa fa-caret-left fa-2x pos" aria-hidden="true"></i></a>
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewfinalizado.php?idcolaborador='.$idcolaborador.'" class="w3-btn w3-green bodersolido">Finalizado</a>
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewproceso.php?idcolaborador='.$idcolaborador.'" class="w3-btn w3-yellow">En proceso</a>
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewnofinalizado.php?idcolaborador='.$idcolaborador.'" class="w3-btn w3-red">No finalizado</a>
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewproceso.php?idcolaborador='.$idcolaborador.'" class="flecha"><i class="fa fa-caret-right fa-2x pos" aria-hidden="true"></i></a>
</div>';
$cabeceracolaborador2='<div class="w3-container w3-center">
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewproceso.php?idcolaborador='.$idcolaborador.'"  class="flecha"><i class="fa fa-caret-left fa-2x pos" aria-hidden="true"></i></a>
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewfinalizado.php?idcolaborador='.$idcolaborador.'" class="w3-btn w3-green">Finalizado</a>
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewproceso.php?idcolaborador='.$idcolaborador.'" class="w3-btn w3-yellow">En proceso</a>
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewnofinalizado.php?idcolaborador='.$idcolaborador.'" class="w3-btn w3-red bodersolido">No finalizado</a>
<a href="'.$CFG->wwwroot.'/blocks/miprogreso/viewfinalizado.php?idcolaborador='.$idcolaborador.'" class="flecha"><i class="fa fa-caret-right fa-2x pos" aria-hidden="true"></i></a>
</div>';


$cabezerafinalizados="<div class='w3-row'>
						<div class='w3-col s12 w3-center'>
      						<p>&nbsp;</p>
      						<p>&nbsp;</p>
						</div>
						<div class='w3-col s1 w3-green w3-center w3-border w3-round'>
   							<p>&nbsp;</p>
					  	</div>
					  	<div class='w3-col s11 w3-container'>
   							<h4>Cursos finalizados</h4>
					  	</div>
					   </div>
					   <div class='w3-col s12 w3-center'>
    						<p>&nbsp;</p>
    						<p>&nbsp;</p>
					   </div>";
					   

$cabeceranofinalizados="<div class='w3-row'>
							<!--<div class='w3-col s12 w3-center'>
								<p>&nbsp;</p>
								<p>&nbsp;</p>
							</div>-->
							<div class='w3-col s1 w3-red w3-center w3-border w3-round'>
							<p>&nbsp;</p>
							</div>
							<div class='w3-col s11 w3-container'>
							<h4>Cursos no finalizados</h4>
							</div>
							</div>
							<div class='w3-col s12 w3-center'>
									<p>&nbsp;</p>
									<p>&nbsp;</p>
							</div>
							";

$cabeceraenproceso="<div class='w3-row'>
						<!--<div class='w3-col s12 w3-center'>
							<p>&nbsp;</p>
							<p>&nbsp;</p>
						</div>-->
						<div class='w3-col s1 w3-yellow w3-center w3-border w3-round'>
						<p>&nbsp;</p>
						</div>
						<div class='w3-col s11 w3-container'>
						<h4>Cursos en proceso</h4>
						</div>
						</div>
						<div class='w3-col s12 w3-center'>
								<p>&nbsp;</p>
								<p>&nbsp;</p>
						</div>
						";

$espacio_responsivo="<div class='resposivo-espacio'></div>";
		
$teamtitulo='<div class="w3-container w3-center">
<h2>Seguimiento a mi equipo</h2>
</div>';


