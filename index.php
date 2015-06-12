<?php session_start();
include ('sistema/configuracion.php');
$usuario->LoginCuentaConsulta();
$usuario->VerificacionCuenta();
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<title><?php echo TITULO ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<link rel="shortcut icon" href="<?php echo ESTATICO ?>img/favicon.ico">
		<link rel="stylesheet" href="<?php echo ESTATICO ?>css/bootstrap.css" media="screen">
		<link rel="stylesheet" href="<?php echo ESTATICO ?>css/bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo ESTATICO ?>css/qualtiva.css">
		<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css">
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		  <script src="<?php echo ESTATICO ?>html5shiv.js"></script>
		  <script src="<?php echo ESTATICO ?>respond.min.js"></script>
		<![endif]-->
	</head>
<body>
	<?php Menu(); ?>
    <div class="container">

		<div class="page-header" id="banner">
		</div>
		<div class="row">
			<div class="col-sm-12">

				<div class="row">
					<?php
					$estadisticasAbonoSql= $db->Conectar()->query("CALL ObtenerAbono()");
					$estadisticaAbono	= $estadisticasAbonoSql->fetch_array();
					?>
					<div class="col-md-3 text-success" align="center">
						<strong>Total Entrada</strong><br>
						<strong>&cent; <?php echo $cliente->FormatoSaldo($estadisticaAbono['abono']); ?></strong>
					</div>
					<?php
					$estadisticasCreditoSql= $db->Conectar()->query("CALL ObtenerSalidadDeudaGanancia()");
					$estadisticaCredito	= $estadisticasCreditoSql->fetch_array(); 
					?>
					<div class="col-md-3 text-primary" align="center">
						<strong>Total Salida</strong><br>
						<strong>&cent; <?php echo $cliente->FormatoSaldo($estadisticaCredito['deudaNeta']); ?>/(<small>&cent; <?php echo $cliente->FormatoSaldo($estadisticaCredito['deuda']); ?></small>)</strong>
					</div>

					<div class="col-md-3 text-danger" align="center">
						<strong>Total Deuda</strong><br>
						<strong>&cent; <?php echo $cliente->FormatoSaldo($estadisticaCredito['saldo']); ?></strong>
					</div>
					<div class="col-md-3 text-info" align="center">
						<strong>Total Ganancia Prevista</strong><br>
						<?php
						$deudaNeta	= $estadisticaCredito['deudaNeta'];
						$deuda		= $estadisticaCredito['deuda'];
						$ganancia	= $deudaNeta-$deuda;
						?>
						<strong>&cent; <?php echo $cliente->FormatoSaldo($ganancia); ?></strong>
					</div>
				</div>
				<hr/>
				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-condensed" id="example">
					<thead>
						<tr>
							<th>C&eacute;dula</th>
							<th>Nombre</th>
							<th>Deuda</th>
							<th>Saldo</th>
							<th>Opci&oacute;n</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$clientesSql = $db->Conectar()->query("CALL ObtenerClientes()");
						while($clientes =$clientesSql->fetch_array()){
						?>
						<tr class="odd gradeX">
							<td><?php echo $clientes['cedula']; ?></td>
							<td><?php echo $clientes['nombre'].' '.$clientes['apellido1'].' '.$clientes['apellido2']; ?></td>
							<td>&cent; <?php echo $cliente->FormatoSaldo($clientes['deudaNeta']); ?></td>
							<td>&cent; <?php echo $cliente->FormatoSaldo($clientes['saldo']); ?></td>
							<td><a href="<?php echo URLBASE ?>cliente/<?php echo $clientes['id']; ?>/<?php echo $enlace->LimpiaCadenaTexto($clientes['nombre'].'-'.$clientes['apellido1'].'-'.$clientes['apellido2']);?>"><button class="btn btn-primary btn-sm">Ver Cliente</button></a></td>
						</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
		<hr/>
	<?php PiePagina(); ?>
    </div>
	<!-- Cargado archivos javascript al final para que la pagina cargue mas rapido -->
    <script src="<?php echo ESTATICO ?>js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo ESTATICO ?>js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo ESTATICO ?>js/dataTables.bootstrap.js"></script>
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function() {
			$('#example').dataTable();
		} );
	</script>
    <script src="<?php echo ESTATICO ?>js/bootstrap.min.js"></script>
    <script src="<?php echo ESTATICO ?>js/bootswatch.js"></script>
</body>
</html>
