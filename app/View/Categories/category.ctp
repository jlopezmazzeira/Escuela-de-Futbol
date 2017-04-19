<?php
    echo $this->Html->css('bootstrap-select.min');
?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<form id="form_report_category" class="well form-horizontal" action="<?php echo Router::url(array('controller'=>'categories','action' => 'reportCategory', 'ext' => 'pdf')); ?>" method="post">
		        <fieldset>
		          	<legend class="text-center">Reporte de Estudiantes por Categor&iacute;a</legend>
		          	<div class="form-group">
		              	<label class="col-md-4 control-label"></label>
		              	<div class="col-md-4">
		                  	<input type="button" id="btnSubmit" class="btn btn-primary" value="Generar">
		                  	<a type="button" class="btn btn-warning volver" href="<?php echo Router::url(array('controller'=>'categories','action' => 'index')); ?>">Volver</a>
		                  	<input type="button" id="btnReset" class="btn btn-default" value="Limpiar">
		              	</div>
		          	</div>
		          	<div class="form-group">
		              	<label class="col-md-4 control-label" >Categor&iacute;a</label>
		                <div class="col-md-6 inputGroupContainer">
		                  	<select id="category" name="data[]" class="selectpicker form-control" multiple data-live-search="true"
		                           data-live-search-placeholder="Buscar" data-actions-box="true">
		                        <?php foreach ($categories as $category): ?>
									<option value="<?php echo $category['Category']['id'] ?>"><?php echo $category['Category']['name'] ?></option>
								<?php endforeach; ?>
		                  	</select>
		                </div>
		          	</div>
		        </fieldset>
		    </form>
		</div>
	</div>
</div>
<div id="modalMessage" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        	<h4 class="modal-title">Mensaje de advertencia</h4>
	      	</div>
	      	<div class="modal-body">
	        	<p>Debe seleccionar la(s) categor&iacute;a(s).</p>
	      	</div>
	      	<div class="modal-footer">
	      		<button type="button" class="btn btn-default" data-dismiss="modal">Aceptar</button>
	      	</div>
	    </div>
	</div>
</div>
<?php 
	/*Library Select*/
	echo $this->Html->script('bootstrap-select.min'); 
	echo $this->Html->script('defaults-es_CL.min');

	echo $this->Html->script('report');
?>