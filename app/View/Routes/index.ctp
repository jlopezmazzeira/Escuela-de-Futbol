<div class="container">
	<div class="row">
		<div class="col-md-12 text-center title-section">
			<h1>Informaci&oacute;n de Rutas</h1>
			<span>
				<a href="<?php echo Router::url(array('controller'=>'routes','action' => 'add')); ?>" title="Agregar ruta"><i class="fa fa-plus-square action" aria-hidden="true"></i></a>
			</span>
			<span>
				<a href="<?php echo Router::url(array('controller'=>'home','action' => 'index')); ?>" title="Regresar">
					<i class="fa fa-reply action" aria-hidden="true"></i>
				</a>
			</span>
		</div>
	</div>
	<div class="row">
		<div class="dashboard">
			<?php 
				if ($size > 0) {
					foreach ($routes as $route):
			?>
						<div class="col-md-4">
					        <div class="widget events-widget">
					            <header class="widget-header">
					                <h2 class="widget-headline"><?php echo $route['Route']['name']; ?></h2>
					            </header>
					            <div class="widget-body">
					                <h3 class="widget-subHeadline"><b>Costo: </b> <?php echo $route['Route']['cost']; ?></h3>
					                <p class="widget-subHeadline"><?php echo $route['Route']['description']; ?></p>
					                <div class="widget-controls">
					                	<a href="<?php echo Router::url(array('controller'=>'routes','action' => 'edit/'.$route['Route']['id'])); ?>" title="Editar"><i class="fa fa-pencil icon-bottom action" aria-hidden="true"></i></a>
					                	<?php 
					                		echo $this->Html->Link($this->Html->image('driver.png'),['action' => 'assignTransport', $route['Route']['id']], ['escape' => false, 'title' => 'Asignar Transportista/s']); 
					                	?>
					                	<a href="#" title="Eliminar ruta" onclick='deleteRoute(<?php echo $route['Route']['id']; ?>)'><i class="fa fa-trash-o delete icon-bottom"></i></a>
					                </div>
					            </div>
					        </div>
						</div>
			<?php 		
					endforeach;
				}
			?>
		</div>
	</div>
</div>
<form id="delete_form" action="<?php echo Router::url(array('controller'=>'routes','action' => 'delete')); ?>" method="POST">
	<div id="modalDeleteRoute" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
		    <div class="modal-content">
		    	<div class="modal-header">
		        	<h4 class="modal-title">Eliminar Ruta</h4>
		      	</div>
		      	<div class="modal-body">
		        	<p>¿Está seguro que desea eliminar la ruta?</p>
		      	</div>
		      	<div class="modal-footer">
		        	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		        	<button type="submit" class="btn btn-danger">Eliminar</button>
		      	</div>
		    </div>
		</div>
	</div>
</form>
<div id="modalMessage" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	      	<div class="modal-body">
	        	<p class="message"></p>
	      	</div>
	      	<div class="modal-footer">
	      		<a href="<?php echo Router::url(array('controller'=>'routes','action' => 'index')); ?>" type="button" class="btn btn-default">Cerrar</a>
	      	</div>
	    </div>
	</div>
</div>
<?php  
	echo $this->Html->script('route');
?>