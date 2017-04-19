<?php
    echo $this->Html->css('bootstrap-select.min');
?>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div>
				<form class="well form-horizontal" action="<?php echo Router::url(array('controller'=>'routes', 'action' => 'assignTransport/'.$route_id)); ?>"
				        method="post">
				    <fieldset>

				      	<legend class="text-center">Asignar Transportistas</legend>
                        	<div class="form-group">
				          	<label class="col-md-4 control-label"></label>
				          	<div class="col-md-4">
				              	<input type="submit" class="btn btn-primary" value="Asignar">
				              	<a type="button" class="btn btn-warning volver" href="<?php echo Router::url(array('controller'=>'routes','action' => 'index')); ?>">Volver</a>
				          	</div>
				      	</div>

				      	<div class="form-group">
				          	<label class="col-md-4 control-label" >Chofer(es)</label>
				            <div class="col-md-6 inputGroupContainer">
				              	<select name="data[]" class="selectpicker form-control" multiple data-live-search="true"
				                       data-live-search-placeholder="Buscar" data-actions-box="true">
				                	<?php foreach ($transports as $transport): ?>
				                    		<option value="<?php echo $transport['Transport']['id']; ?>" <?php if (in_array($transport['Transport']['id'],$id_transports)) echo "selected"; ?>>
				                        	<?php echo $transport['Transport']['name'].' '.$transport['Transport']['lastname']; ?>
				                    		</option>
				                	<?php endforeach; ?>
				              	</select>
				            </div>
				      	</div>

				      </fieldset>
				</form>
			</div>
		</div>
	</div>
</div>
<?php 
	echo $this->Html->script('bootstrap-select.min'); 
	echo $this->Html->script('defaults-es_CL.min');
?>
