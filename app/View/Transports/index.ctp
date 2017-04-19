<div class="container">
	<div class="row">
		<div class="col-md-12 text-center title-section">
			<h1>Informaci&oacute;n de Transportistas</h1>
			<span>
				<a href="<?php echo Router::url(array('controller'=>'transports','action' => 'add')); ?>" title="Agregar transportista"><i class="fa fa-user-plus action" aria-hidden="true"></i></a>
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
					foreach ($transports as $transport):
			?>
						<div class="col-md-4">
					        <div class="widget events-widget">
					            <header class="widget-header">
					                <h2 class="widget-headline"><?php echo $transport['Transport']['name']." ".$transport['Transport']['lastname']; ?></h2>
					            </header>
					            <div class="widget-body">
					                <p class="widget-subHeadline"><b>M&oacute;vil: </b> <?php echo $transport['Transport']['movil']; ?></p>
					                <p class="widget-subHeadline"><b>Fijo:</b> <?php echo $transport['Transport']['phone']; ?></p>
					                <div class="widget-controls">
					                	<a href="<?php echo Router::url(array('controller'=>'transports','action' => 'edit/'.$transport['Transport']['id'])); ?>" title="Editar"><i class="fa fa-pencil icon-bottom action" aria-hidden="true"></i></a>
					                	<?php 
					                		if (empty($transport['Transport']['license'])):
					                			echo $this->Html->image('license.png',array('alt' => 'Licencia'));
					                		else: 
					                			echo $this->Html->image('licenseg.png',array('alt' => 'Licencia', 'id' => 'img-license', 'file' => $route_license.'/'.$transport['Transport']['id'].'/'.$transport['Transport']['license']));
					                		endif; 
					                	?>
					                	<?php 
					                		if (empty($transport['Transport']['permission'])):
					                			echo $this->Html->image('permission.png',array('alt' => 'Permiso'));
					                		else: 
					                			echo $this->Html->image('permissiong.png',array('alt' => 'Permiso', 'id' => 'img-permission', 'file' => $route_permission.'/'.$transport['Transport']['id'].'/'.$transport['Transport']['permission']));
					                		endif; 
					                	?>
					                	<?php 
					                		if (empty($transport['Transport']['enrollment'])):
					                			echo $this->Html->image('enrollment.png',array('alt' => 'Matrícula'));
					                		else: 
					                			echo $this->Html->image('enrollmentg.png',array('alt' => 'Matrícula', 'id' => 'img-enrollment', 'file' => $route_enrollmen.'/'.$transport['Transport']['id'].'/'.$transport['Transport']['enrollment']));
					                		endif; 
					                	?>
					                	<a href="#" title="Eliminar transportista" onclick='deleteTransport(<?php echo $transport['Transport']['id']; ?>)'><i class="fa fa-trash-o delete icon-bottom"></i></a>
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
<form id="delete_form" action="<?php echo Router::url(array('controller'=>'transports','action' => 'delete')); ?>" method="POST">
	<div id="modalDeleteTransport" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
		    <div class="modal-content">
		    	<div class="modal-header">
		        	<h4 class="modal-title">Eliminar Transporte</h4>
		      	</div>
		      	<div class="modal-body">
		        	<p>¿Está seguro que desea eliminar el transportista?</p>
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
	      		<a href="<?php echo Router::url(array('controller'=>'transports','action' => 'index')); ?>" type="button" class="btn btn-default">Cerrar</a>
	      	</div>
	    </div>
	</div>
</div>
<?php  
	echo $this->Html->script('transport');
?>