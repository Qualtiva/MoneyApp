<?php session_start();
include ('sistema/configuracion.php');
$usuario->LoginCuentaConsulta();
$usuario->VerificacionCuenta();
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<title>Nuevo cliente | <?php echo TITULO ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<link rel="shortcut icon" href="<?php echo ESTATICO ?>img/favicon.ico">
		<link rel="stylesheet" href="<?php echo ESTATICO ?>css/bootstrap.css" media="screen">
		<link rel="stylesheet" href="<?php echo ESTATICO ?>css/bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo ESTATICO ?>css/qualtiva.css">
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		  <script src="<?php echo ESTATICO ?>html5shiv.js"></script>
		  <script src="<?php echo ESTATICO ?>respond.min.js"></script>
		<![endif]-->
		<style type="text/css">
		.btn-default {
			color: #aea79f;
			background-color: #FFFFFF;
			border-color: #aea79f;
		}
		</style>
	</head>
<body>
	<?php Menu(); ?>
    <div class="container">

		<div class="page-header" id="banner">
			<div class="row">
				<div class="col-lg-8 col-md-7 col-sm-6">
					<h1><?php echo TITULO ?></h1>
					<p class="lead">Sistema de Credito y cobros</p>
				</div>
			</div>
		</div>
		<div class="row">
			<h1 class="modal-title" id="myModalLabel">Crear Nuevo Cliente</h1>
			<br/>
			<?php
			if(isset($_POST['crearusuario'])){
				$prestamo	= filter_var($_POST['prestamo'], FILTER_VALIDATE_INT);
				$interes	= filter_var($_POST['intereses'], FILTER_VALIDATE_INT);
				$cuota		= filter_var($_POST['cuota'], FILTER_VALIDATE_INT);
				$cedula		= filter_var($_POST['cedula'], FILTER_SANITIZE_STRING);
				$nombre		= filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
				$apellido1	= filter_var($_POST['apellido1'], FILTER_SANITIZE_STRING);
				$apellido2	= filter_var($_POST['apellido2'], FILTER_SANITIZE_STRING);
				$telefono	= filter_var($_POST['telefono'], FILTER_SANITIZE_STRING);
				$correo		= filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
				$nacion		= filter_var($_POST['nacionalidad'], FILTER_SANITIZE_STRING);
				$provincia	= filter_var($_POST['provincia'], FILTER_SANITIZE_STRING);
				$canton		= filter_var($_POST['canton'], FILTER_SANITIZE_STRING);
				$distrito	= filter_var($_POST['distrito'], FILTER_SANITIZE_STRING);
				$direccion	= filter_var($_POST['direccion'], FILTER_SANITIZE_STRING);

				$cero		= 0;
				$in			= $cero.'.'.$interes;
				$preneto	= $prestamo*$in;
				$neto		= $preneto+$prestamo;
				$fecha		= FechaActual();
				$VerificarCedulaSQL = $db->Conectar()->query("SELECT cedula FROM `clientes` WHERE cedula='{$cedula}'");
				$VerificarCedula	= MysqliNumRowsQualtiva($VerificarCedulaSQL) > 0;
				if($VerificarCedula){
					echo'
					<div class="col-lg-4">
						<div class="bs-component">
							<div class="alert alert-dismissible alert-danger">
								<button type="button" class="close" data-dismiss="alert">×</button>
								<strong>'.$usuarioApp['usuario'].'</strong>, este cliente ya esta registrado en la base de datos.
							</div>
						</div>
					</div>
					<meta http-equiv="refresh" content="2;url='.URLBASE.'cliente-nuevo"/>';
				}
				else
				{
					$crearClienteSql= $db->Conectar()->query("INSERT INTO `clientes` (`cedula`, `nombre`, `apellido1`, `apellido2`, `telefono`, `correo`, `nacionalidad`, `provincia`, `canton`, `distrito`, `direccion`) VALUES ('{$cedula}', '{$nombre}', '{$apellido1}', '{$apellido2}', '{$telefono}', '{$correo}', '{$nacion}', '{$provincia}', '{$canton}', '{$distrito}', '{$direccion}')");
					$IdClienteSql	= $db->Conectar()->query("SELECT MAX(id) AS id FROM `clientes`");
					$IdCliente		= $IdClienteSql->fetch_array();
					$crearCreditoSql= $db->Conectar()->query("INSERT INTO `credito` (`id_cliente`, `deuda`, `deudaNeta`, `saldo`, `fecha`, `interes`, `cuota`) VALUES ('{$IdCliente['id']}', '{$prestamo}', '{$neto}', '{$neto}', '{$fecha}','{$interes}', '{$cuota}')");

					if($crearClienteSql && $crearCreditoSql == true){
						echo'
						<div class="alert alert-dismissible alert-success">
							<button type="button" class="close" data-dismiss="alert">×</button>
							<strong>&iexcl;Bien hecho!</strong> Haz Creado el cliente con exito.
						</div>
						<meta http-equiv="refresh" content="2;url='.URLBASE.'cliente-nuevo"/>';
					}else{
						echo'
						<div class="alert alert-dismissible alert-danger">
							<button type="button" class="close" data-dismiss="alert">×</button>
							<strong>&iexcl;Lo Sentimos!</strong> A ocurrido un error al crear un nuevo, intentalo de nuevo.
						</div>
						<meta http-equiv="refresh" content="2;url='.URLBASE.'cliente-nuevo"/>';
					}
				}
			}
			?>
			<form role="form" id="contact-form" method="post" class="contact-form">
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-addon"><strong>&cent;</strong></span>
								<input type="text" class="form-control" name="prestamo" id="inputEmail3" placeholder="Cantidad del Pr&eacute;stamo en Colones" autofocus required >
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-addon"><strong>&#37;</strong></span>
								<input type="number" class="form-control" name="intereses" id="inputEmail3" placeholder="Valor Interes Pr&eacute;stamo" autofocus required >
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-addon"><strong>&cent;</strong></span>
								<input type="text" class="form-control" name="cuota" id="inputEmail3" placeholder="Valor de la Cuota de Pr&eacute;stamo" autofocus required >
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="date" class="form-control" name="fecha" id="inputEmail3" placeholder="Fecha final del prestamo" autofocus required >
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<input type="text" class="form-control" name="cedula" autocomplete="off" id="cedula" placeholder="N&uacute;mero de C&eacute;dula del Cliente" required >
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<input type="text" class="form-control" name="nombre" autocomplete="off" id="nombre" placeholder="Nombre del Cliente" required >
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<input type="text" class="form-control" name="apellido1" autocomplete="off" id="apellido1" placeholder="Primer Apellido del Cliente" required >
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<input type="text" class="form-control" name="apellido2" autocomplete="off" id="apellido2" placeholder="Segundo Apellido del Cliente" required >
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<input type="text" class="form-control" name="telefono" autocomplete="off" id="telefono" placeholder="N&uacute;mero de Tel&eacute;fono del Cliente" required >
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<input type="email" class="form-control" name="correo" autocomplete="off" id="correo" placeholder="Correo electr&oacute;nico del Cliente" required >
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label>Nacionalidad</label>
							<select name="nacionalidad" class="form-control">
								<option selected="selected">Seleccione un Nacionalidad</option>
								<?php
								$paisSql = $db->Conectar()->query("SELECT * FROM `pais` WHERE id");
								while($pais = $paisSql->fetch_array()){
								echo'<option value="'.$pais['id'].'">'.$pais['pais'].'</option>';
								}
								?>
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Provincia de Domicilio</label>
							<select id="provincia" name="provincia" class="form-control">
								<option selected="selected">Seleccione una Provincia</option>
								<?php
								$provinciaSQL = $db->Conectar()->query("SELECT * FROM `provincia` WHERE id");
								while($provincia = $provinciaSQL->fetch_array()){
								echo'<option value="'.$provincia['id'].'">'.$provincia['provincia'].'</option>';
								}
								?>
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Cant&oacute;n de Domicilio</label>
							<select  id="canton" name="canton" class="form-control"><option value="0"  selected="selected">Seleccione un Cant&oacute;n</option></select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Distrito de Domicilio</label>
							<select  id="distrito" name="distrito" class="form-control"><option value="0"  selected="selected">Seleccione un Distrito</option></select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="textArea" class="col-lg-12 control-label">Direcci&oacute;n exacta del Domicilio</label>
							<div class="col-lg-12">
								<textarea name="direccion" class="form-control" rows="3" id="textArea"></textarea>
							</div>
						</div>
					</div>
				</div>
				<br/>
				<div class="row">
					<div class="col-md-12">
						<button type="submit" name="crearusuario" class="btn btn-primary pull-right">Crear Nuevo Cliente</button>
					</div>
				</div>
			</form>
		</div>
		<br/>
		<hr/>
	<?php PiePagina(); ?>
    </div>
	<!-- Cargado archivos javascript al final para que la pagina cargue mas rapido -->
    <script src="<?php echo ESTATICO ?>js/jquery-1.10.2.min.js"></script>
    <script src="<?php echo ESTATICO ?>js/bootstrap.min.js"></script>
    <script src="<?php echo ESTATICO ?>js/bootswatch.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script>
	$(document).ready(function(){

		$('#provincia').change(function(){
			var id_provincia = $('#provincia').val();
			if(id_provincia != 0)
			{
				$.ajax({
					type:'POST',
					url:'canton.php',
					data:{id:id_provincia},
					cache:false,
					success: function(returndata){
						$('#canton').html(returndata);
					}
				});
			}
		})

		// Distritos
		$('#canton').change(function(){
			var id_canton = $('#canton').val();
			if(id_canton != 000)
			{
				$.ajax({
					type:'POST',
					url:'distrito.php',
					data:{id:id_canton},
					cache:false,
					success: function(returndata){
						$('#distrito').html(returndata);
					}
				});
			}
		})

	})
	</script>
</body>
</html>
