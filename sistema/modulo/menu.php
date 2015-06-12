<div class="navbar navbar-default navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<a href="<?php echo URLBASE ?>" class="navbar-brand"><img src="<?php echo ESTATICO ?>img/stocklogo.png" width="200px"/></a>
			<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>
		<div class="navbar-collapse collapse" id="navbar-main">
			<ul class="nav navbar-nav">
				<li>
					<a href="<?php echo URLBASE ?>">Inicio</a>
				</li>
				<li>
					<a href="<?php echo URLBASE ?>cliente-nuevo">Cliente</a>
				</li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li>
					<a href="<?php echo URLBASE ?>admin">Administraci&oacute;n</a>
				</li>
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#" id="themes">Cuenta <span class="caret"></span></a>
					<ul class="dropdown-menu" aria-labelledby="themes">
						<li>
							<a href="#">Ajustes de usuario</a>
						</li>
						<li class="divider"></li>
						<li>
							<a href="<?php echo URLBASE ?>cerrar-sesion">Cerrar Sesi&oacute;n</a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</div>