<?php session_start();
include ('sistema/configuracion.php');
$usuario->LoginCuentaConsulta();
$usuario->VerificacionCuenta();
$cliente->MostrarCliente();
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<title>Cliente: <?php echo $clientes['nombre'].' '.$clientes['apellido1'].' '.$clientes['apellido2']; ?> | <?php echo TITULO ?></title>
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
			<div class="row">
				<div class="col-lg-8 col-md-7 col-sm-6">
					<h1><?php echo TITULO ?></h1>
					<p class="lead">Cliente: <?php echo $clientes['nombre'].' '.$clientes['apellido1'].' '.$clientes['apellido2']; ?></p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">

				<div class="row">
					<?php
					$estadisticasSql= $db->Conectar()->query("SELECT
						`deuda`
						, `saldo`
						, `cuota`
						, `id`
						,`deudaNeta`
					FROM
						`credito`
					WHERE id_cliente = '{$clientes['id']}'");
					$estadistica	= $estadisticasSql->fetch_array(); 
					?>
					<div class="col-md-4 text-success" align="center">
						<strong>Total Deuda</strong><br>
						<strong>&cent; <?php echo $cliente->FormatoSaldo($estadistica['deudaNeta']); ?></strong>
					</div>
					<div class="col-md-4 text-primary" align="center">
						<strong>Total Abonado</strong><br>
						<strong>&cent; <?php echo $cliente->FormatoSaldo($estadistica['deudaNeta'] - $estadistica['saldo']); ?></strong>
					</div>
					<div class="col-md-4 text-danger" align="center">
						<strong>Saldo Faltante</strong><br>
						<strong>&cent; <?php echo $cliente->FormatoSaldo($estadistica['saldo']); ?></strong>
					</div>
				</div>
				<hr/>
				<?php 
				$por= $cliente->AbonosSaldo($clientes['id'])*100/$estadistica['deuda'];
				?>
				<strong><center>Total Abonado: <?php echo $cliente->Formato($por); ?>% || Total Saldo <?php echo $cliente->Formato(100-$por); ?>%</center></strong>
				<div class="bs-component">
				  <div class="progress progress-striped active">
					<div class="progress-bar" style="width: <?php echo $por; ?>%"></div>
				  </div>
				</div>
				<div class="bs-component">
				<!-- Button trigger modal -->
				<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
				  Registrar Abono
				</button>
				<!-- Modal -->
				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				  <div class="modal-dialog">
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Registrar Abono <?php echo $clientes['nombre'].' '.$clientes['apellido1'].' '.$clientes['apellido2']; ?></h4>
					  </div>
					  <div class="modal-body">
						<form class="form-horizontal" method="post" action="">
							<input type="hidden" name="idcredito" value="<?php echo $estadistica['id']; ?>">
							<input type="hidden" name="idusuario" value="<?php echo $clientes['id']; ?>">
							<div class="form-group">
								<label  class="control-label">&nbsp;&nbsp;&nbsp;&iquest;Cuanto quiere abonar a la cuenta?</label>
								<div class="col-sm-12">
									<div class="input-group">
										<span class="input-group-addon"><strong>&cent;</strong></span>
										<input type="text" class="form-control" name="cuota" id="inputEmail3" placeholder="Email" value="<?php echo $estadistica['cuota']; ?>" autofocus required >
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="inputPassword3" class="control-label">&nbsp;&nbsp;&nbsp;Observaciones</label>
								<div class="col-sm-12">
								  <textarea class="form-control" rows="3" name="observacion">Deposita <?php echo $clientes['nombre'].' '.$clientes['apellido1'].' '.$clientes['apellido2']; ?></textarea>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
								   <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
									<button type="submit" name="RegistrarAbono" class="btn btn-primary">Registrar Abono</button>
								</div>
							</div>
						</form>
					  </div>
					</div>
				  </div>
				</div>
				</div>
				<?php
				if(isset($_POST['RegistrarAbono'])){
					// valores del formulario
					$idCredito	= $_POST['idcredito'];
					$idusuario	= $_POST['idusuario'];
					$cuota		= $_POST['cuota'];
					$observacion= $_POST['observacion'];
					$fecha		= FechaActual();
					$hora		= HoraActual();
					//Obteniendo saldo minimo
					$SaldoMinimoSql	= $db->Conectar()->query("SELECT saldo FROM `credito` WHERE id='{$idCredito}'");
					$SaldoMinimo	= $SaldoMinimoSql->fetch_array();
					$saldo = $SaldoMinimo['saldo'];
					$saldoActual = $saldo - $cuota;
					//Registrando Abono
					$registrarAbono = $db->Conectar()->query("INSERT INTO `abono` (`id_credito`, `abono`, `saldo`, `nota`, `id_usuario`, `fecha`, `hora`) VALUES
					('{$idCredito}', '{$cuota}', '{$saldoActual}', '{$observacion}', '{$idusuario}', '{$fecha}', '{$hora}')");
					//Actulizando Saldo
					$ActulizarSaldo = $db->Conectar()->query("UPDATE `credito` SET `saldo` = '{$saldoActual}' WHERE `id` = '{$idCredito}'");
					if($registrarAbono == true){
						echo'
						<div class="alert alert-dismissible alert-success">
							<button type="button" class="close" data-dismiss="alert">×</button>
							<strong>&iexcl;Bien hecho!</strong> El abono se ha realizado con exito.
						</div>
						';
					}else{
						echo'
						<div class="alert alert-dismissible alert-danger">
							<button type="button" class="close" data-dismiss="alert">×</button>
							<strong>&iexcl;Lo Sentimos!</strong> A ocurrido un error al realizar el abono, intentalo de nuevo.
						</div>
						';
					}
				}
				?>
				<hr/>
				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-condensed" id="example">
					<thead>
						<tr>
							<th>Fecha</th>
							<th>Observaci&oacute;n</th>
							<th>Abono</th>
							<th>Saldo</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$clienteSql = $db->Conectar()->query("SELECT
							`abono`.*
							, `credito`.`id_cliente`
						FROM
							`abono`
							INNER JOIN `credito`
								ON (`credito`.`id_cliente` = `abono`.`id_credito`)
						 WHERE id_cliente='{$clientes['id']}';");
						while($clientes =$clienteSql->fetch_array()){
						?>
						<tr class="odd gradeX">
							<td><?php echo $clientes['fecha'].' '.$clientes['hora']; ?></td>
							<td><?php echo $clientes['nota']; ?></td>
							<td>&cent; <?php echo $cliente->FormatoSaldo($clientes['abono']); ?></td>
							<td>&cent; <?php echo $cliente->FormatoSaldo($clientes['saldo']); ?></td>
						</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-condensed" id="example">
<thead>
	<tr>
<?php
for($i = 1; $i <= 8; ++$i){
    echo '<th>Pago'.$i.'</th>';
}
?>
	</tr>
</thead>
<tbody>
<?php
$fecha_matricula = "11/06/2016";
for($i = 1; $i <= 8; ++$i){
    echo '<td>'.date("d/m/Y", strtotime($fecha_matricula." +$i month"))."</td>";
}
?>
</tbody>
</table>
	<hr/>
		<?php PiePagina(); ?>
    </div>
	<!-- Cargado archivos javascript al final para que la pagina cargue mas rapido -->
	<script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
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
