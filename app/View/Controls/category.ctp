<?php
	echo $this->Html->css('bootstrap-datepicker3.min');
    echo $this->Html->css('bootstrap-select.min');
?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<form id="form_report_category" class="well form-horizontal" action="<?php echo Router::url(array('controller'=>'controls','action' => 'reportCategory', 'ext' => 'pdf')); ?>" method="post">
		        <fieldset>
		          	<legend class="text-center">Reporte de Asistencias por Categor&iacute;a</legend>
		          	<div class="form-group">
		              	<label class="col-md-4 control-label"></label>
		              	<div class="col-md-4">
		                  	<input type="submit" class="btn btn-primary" value="Generar">
		                  	<a type="button" class="btn btn-warning volver" href="<?php echo Router::url(array('controller'=>'home','action' => 'index')); ?>">Volver</a>
		                  	<input type="button" id="btnReset" class="btn btn-default" value="Limpiar">
		              	</div>
		          	</div>
		          	<div class="form-group">
		              	<label class="col-md-4 control-label" >Categor&iacute;a</label>
		                <div class="col-md-6 inputGroupContainer">
		                  	<select id="category" name="category" class="selectpicker form-control" data-live-search="true"
		                           data-live-search-placeholder="Buscar" data-actions-box="true">
		                           <option value="">Seleccione</option>
		                        <?php foreach ($categories as $category): ?>
									<option value="<?php echo $category['c']['id'] ?>"><?php echo $category['c']['name'] ?></option>
								<?php endforeach; ?>
		                  	</select>
		                </div>
		          	</div>
		          	<div class="form-group">
			            <label class="col-md-4 control-label">Desde</label>
			            <div class="col-md-7 inputGroupContainer">
		                	<div class="col-md-5 element-margin-right">
		                		<div class="form-group">
			    					<div id="date_from_div" class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-end-date="0d">
			    						<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i>
										</span>
			      						<input type="text" id="date_from" name="date_from" class="form-control input-height">
			    					</div>
			  					</div>
		                	</div>
			            </div>
			        </div>
			        <div class="form-group">
			            <label class="col-md-4 control-label">Hasta</label>
			            <div class="col-md-7 inputGroupContainer">
		                	<div class="col-md-5 element-margin-right">
		                		<div class="form-group">
			    					<div id="date_until_div" class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-end-date="0d">
			    						<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i>
										</span>
			      						<input type="text" id="date_until" name="date_until" class="form-control input-height">
			    					</div>
			  					</div>
		                	</div>
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
	        	<p id="message"></p>
	      	</div>
	      	<div class="modal-footer">
	      		<button type="button" class="btn btn-default" data-dismiss="modal">Aceptar</button>
	      	</div>
	    </div>
	</div>
</div>
<?php 
	/*Library Datepicker Bootstrap*/
	echo $this->Html->script('bootstrap-datepicker.min');
	echo $this->Html->script('bootstrap-datepicker.es.min');

	/*Library Select*/
	echo $this->Html->script('bootstrap-select.min'); 
	echo $this->Html->script('defaults-es_CL.min');

	echo $this->Html->script('bootstrapValidator');

	echo $this->Html->script('control');
?>