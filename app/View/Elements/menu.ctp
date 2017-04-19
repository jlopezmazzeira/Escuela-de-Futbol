<nav class="navbar nav-academy navbar-fixed-top">
  	<div class="container-fluid">
	    <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      	<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
	        	<span class="sr-only">Toggle navigation</span>
	        	<span class="icon-bar"></span>
	        	<span class="icon-bar"></span>
	        	<span class="icon-bar"></span>
	      	</button>
	      	<div class="logo">
	      		<a class="navbar-brand" href="<?php echo Router::url(array('controller'=>'home','action' => 'index')); ?>">
	      		<?php echo $this->Html->image('logo.png',array('alt' => 'Estudiantes de la Plata Ecuador')); ?></a>
	      	</div>
	    </div>

    	<!-- Collect the nav links, forms, and other content for toggling -->
    	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      		<ul class="nav navbar-nav navbar-right">
      			<li><a href="<?php echo Router::url(array('controller'=>'home','action' => 'index')); ?>">Inicio</a></li>
      			<?php if($current_user['role_id'] == 1): ?>
        		<li class="dropdown">
          			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Configuraci&oacute;n <span class="caret"></span></a>
	          		<ul class="dropdown-menu">
			            <li><a href="<?php echo Router::url(array('controller'=>'users','action' => 'index')); ?>">Usuarios</a></li>
			            <li><a href="<?php echo Router::url(array('controller'=>'parameters','action' => 'index')); ?>">Par&aacute;metros</a></li>
			            <li><a href="<?php echo Router::url(array('controller'=>'scholarships','action' => 'index')); ?>">Descuentos</a></li>
			            <li><a href="<?php echo Router::url(array('controller'=>'routes','action' => 'index')); ?>">Rutas</a></li>
			            <li><a href="<?php echo Router::url(array('controller'=>'categories','action' => 'index')); ?>">Categor&iacute;as</a></li>
	          		</ul>
        		</li>
        		<li class="dropdown">
          			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Gesti&oacute;n <span class="caret"></span></a>
	          		<ul class="dropdown-menu">
			            <li><a href="<?php echo Router::url(array('controller'=>'products','action' => 'index')); ?>">Productos</a></li>
			            <li><a href="<?php echo Router::url(array('controller'=>'transports','action' => 'index')); ?>">Transportistas</a></li>
			            <li><a href="<?php echo Router::url(array('controller'=>'students','action' => 'massEmail')); ?>">Email masivos</a></li>
	          		</ul>
        		</li>
        		<li class="dropdown">
          			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Modulos <span class="caret"></span></a>
	          		<ul class="dropdown-menu">
			            <li><a href="<?php echo Router::url(array('controller'=>'peoples','action' => 'clients')); ?>">Clientes</a></li>
			            <li><a href="<?php echo Router::url(array('controller'=>'students','action' => 'index')); ?>">Estudiantes</a></li>
			            <li><a href="<?php echo Router::url(array('controller'=>'peoples','action' => 'providers')); ?>">Proveedores</a></li>
			            <li><a href="<?php echo Router::url(array('controller'=>'bills','action' => 'index')); ?>">Facturas</a></li>
			            <li><a href="<?php echo Router::url(array('controller'=>'receipts','action' => 'index')); ?>">Recibos</a></li>
			            <li><a href="<?php echo Router::url(array('controller'=>'credits','action' => 'index')); ?>">Notas de Cr&eacute;ditos</a></li>
			            <li><a href="<?php echo Router::url(array('controller'=>'orders','action' => 'index')); ?>">Ordenes de Pagos</a></li>
			            <li><a href="<?php echo Router::url(array('controller'=>'payments','action' => 'index')); ?>">Pagos a proveedores</a></li>
			            <li><a href="<?php echo Router::url(array('controller'=>'closings','action' => 'index')); ?>">Cierres</a></li>
	          		</ul>
        		</li>
        		<li class="dropdown">
          			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reportes <span class="caret"></span></a>
	          		<ul class="dropdown-menu">
			            <li><a href="<?php echo Router::url(array('controller'=>'bills','action' => 'bill')); ?>">Por N° Facturas</a></li>
			            <li><a href="<?php echo Router::url(array('controller'=>'categories','action' => 'category')); ?>">Categor&iacute;as</a></li>
			            <li><a href="<?php echo Router::url(array('controller'=>'students','action' => 'inscription')); ?>">Estudiantes nuevos</a></li>
			            <li><a href="<?php echo Router::url(array('controller'=>'peoples','action' => 'provider')); ?>">Pago a Proveedores</a></li>
			            <li><a href="<?php echo Router::url(array('controller'=>'bills','action' => 'accountsReceivable')); ?>">Cuentas por cobrar</a></li>
			            <li><a href="<?php echo Router::url(array('controller'=>'transports','action' => 'carrierIncome')); ?>">Transportistas</a></li>
			            <li><a href="<?php echo Router::url(array('controller'=>'controls','action' => 'student')); ?>">Reporte de asistencias por estudiante</a></li>
			            <li><a href="<?php echo Router::url(array('controller'=>'controls','action' => 'category')); ?>">Reporte de asistencias por categor&iacute;a</a></li>
	          		</ul>
        		</li>
        		<?php endif; ?>
        		<?php if ($current_user['role_id'] == 3): ?>
        				<li class="dropdown">
		          			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Asistencias <span class="caret"></span></a>
			          		<ul class="dropdown-menu">
					            <li><a href="<?php echo Router::url(array('controller'=>'controls','action' => 'index')); ?>">Control de asistencia</a></li>
					            <li><a href="<?php echo Router::url(array('controller'=>'controls','action' => 'consult')); ?>">Consultar asistencia</a></li>
					            <li><a href="<?php echo Router::url(array('controller'=>'controls','action' => 'check')); ?>">Ficha de asistencia</a></li>
					            <li><a href="<?php echo Router::url(array('controller'=>'controls','action' => 'student')); ?>">Reporte de asistencias por estudiante</a></li>
					            <li><a href="<?php echo Router::url(array('controller'=>'controls','action' => 'category')); ?>">Reporte de asistencias por categor&iacute;a</a></li>
			          		</ul>
		        		</li>
        		<?php endif ?>
        		<li class="dropdown">
          			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $current_user['name']; ?> <span class="caret"></span></a>
	          		<ul class="dropdown-menu">
			            <li><a href="<?php echo Router::url(array('controller'=>'users','action' => 'profile/'.$current_user['id'])); ?>">Mi perfil</a></li>
			            <li><a href="<?php echo Router::url(array('controller'=>'users','action' => 'changePassword')); ?>">Cambiar contraseña</a></li>
			            <li><a href="<?php echo Router::url(array('controller'=>'users','action' => 'logout')); ?>">Salir</a></li>
	          		</ul>
        		</li>
      		</ul>
    	</div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
