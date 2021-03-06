<?php
	echo $this->Html->css('bootstrap-datepicker3.min');
    echo $this->Html->css('bootstrap-select.min');
?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<form id="form_report_carrier_income" class="well form-horizontal" action="<?php echo Router::url(array('controller'=>'transports','action' => 'reportCarrierIncome', 'ext' => 'pdf')); ?>" method="post">
		        <fieldset>
		          	<legend class="text-center">Reporte de Recaudaci&oacute;n por Transportista</legend>
			        <div class="form-group">
			            <label class="col-md-4 control-label"></label>
			            <div class="col-md-4">
			                <input type="button" id="btnSubmit" class="btn btn-primary" value="Generar">
			                <a type="button" class="btn btn-warning volver" href="<?php echo Router::url(array('controller'=>'transports','action' => 'index')); ?>">Volver</a>
			                <input type="button" id="btnReset" class="btn btn-default" value="Limpiar">
			            </div>
			        </div>
			        <div class="form-group">
		              	<label class="col-md-4 control-label" >Transportista</label>
		                <div class="col-md-6 inputGroupContainer">
		                  	<select id="transport" name="transport" class="selectpicker form-control" data-live-search="true" data-live-search-placeholder="Buscar">
		                           <option value="">Seleccione</option>
		                        <?php foreach ($transports as $transport): ?>
										<option value="<?php echo $transport['Transport']['id'] ?>"><?php echo $transport['Transport']['name']." ".$transport['Transport']['lastname'] ?></option>
								<?php endforeach; ?>
		                  	</select>
		                </div>
			        </div>
			        <div class="form-group">
			            <label class="col-md-4 control-label">Periodo</label>
			            <div class="col-md-7 inputGroupContainer">
		                	<div class="col-md-5 element-margin-right">
		                		<div class="form-group">
			    					<div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-end-date="0d">
			    						<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i>
										</span>
			      						<input type="text" id="date_from" name="date_from" class="form-control input-height">
			    					</div>
			  					</div>
		                	</div>
		                	<div class="col-md-5">
		                		<div class="form-group">
			    					<div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-end-date="0d">
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

	echo $this->Html->script('report');
?>