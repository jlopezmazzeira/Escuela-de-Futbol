<div class="container">
	<div class="row">
		<div class="col-md-12 text-center title-section">
			<h1>Informaci&oacute;n de Categor&iacute;as </h1>
			<span>
				<a href="<?php echo Router::url(array('controller'=>'categories','action' => 'add')); ?>" title="Agregar categor&iacute;a"><i class="fa fa-plus-square action" aria-hidden="true"></i></a>
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
					foreach ($categories as $category):
			?>
						<div class="col-md-4">
					        <div class="widget events-widget">
					            <header class="widget-header">
					                <h2 class="widget-headline"><?php echo $category['Category']['name']; ?></h2>
					            </header>
					            <div class="widget-body">
					                <h3 class="widget-subHeadline"><b>Edad:</b></h3>
					                <p class="widget-subHeadline"><b>M&iacute;nima: </b> <?php echo $category['Category']['age_min']; ?></p>
					                <p class="widget-subHeadline"><b>M&aacute;xima:</b> <?php echo $category['Category']['age_max']; ?></p>
					                <div class="widget-controls">
					                	<a href="<?php echo Router::url(array('controller'=>'categories','action' => 'edit/'.$category['Category']['id'])); ?>" title="Editar"><i class="fa fa-pencil icon-bottom action" aria-hidden="true"></i></a>
					                	<a href="#" title="Eliminar categor&iacute;a" onclick='deleteCategory(<?php echo $category['Category']['id']; ?>)'><i class="fa fa-trash-o delete icon-bottom"></i></a>
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
<form id="delete_form" action="<?php echo Router::url(array('controller'=>'categories','action' => 'delete')); ?>" method="POST">
	<div id="modalDeleteCategory" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
		    <div class="modal-content">
		    	<div class="modal-header">
		        	<h4 class="modal-title">Eliminar Categor&iacute;a</h4>
		      	</div>
		      	<div class="modal-body">
		        	<p>¿Está seguro que desea eliminar la categor&iacute;a?</p>
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
	      		<a href="<?php echo Router::url(array('controller'=>'categories','action' => 'index')); ?>" type="button" class="btn btn-default">Cerrar</a>
	      	</div>
	    </div>
	</div>
</div>
<?php  
	echo $this->Html->script('category');
?>