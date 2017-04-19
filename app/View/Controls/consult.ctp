<?php
    echo $this->Html->css('bootstrap-datepicker3.min');
    echo $this->Html->css('bootstrap-select.min');
?>
<div class="container">
	<div class="row">
		<div class="col-md-12 text-center title-section">
			<h1>Consultar asistencia por Categor&iacute;a</h1>
			<span>
				<a href="<?php echo Router::url(array('controller'=>'home','action' => 'index')); ?>" title="Regresar">
					<i class="fa fa-reply action" aria-hidden="true"></i>
				</a>
			</span>
		</div>
	</div>
	<div class="row">
		<div class="col-md-offset-1 col-md-11">
			<form id="control_consult_form" class="inline-block" action="POST">
				<div class="form-group">
				    <label class="col-md-2 control-label">Categor&iacute;a</label>
			        <div class="col-md-3">
			            <select id="category" name="category" class="selectpicker form-control" data-live-search="true" data-live-search-placeholder="Buscar">
			               	<option value="" selected>Seleccione</option>
			                <?php foreach ($categories_employee as $category): ?>
			                    <option value="<?php echo $category['c']['id']; ?>">
			                        <?php echo $category['c']['name']; ?>
			                    </option>
			                <?php endforeach; ?>
			            </select>
			        </div>
				</div>
				<div class="form-group">
				    <label class="col-md-2 control-label">Fecha</label>
			        <div class="col-md-3">
			            <div class="input-group date" id="date_control_div" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-end-date="0d">
    						<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i>
							</span>
      						<input type="text" id="date_control" name="date_control" class="form-control">
    					</div>
			        </div>
				</div>
				<input type="button" name="btnConsult" id="btnConsult" class="btn btn-primary" value="Consultar">
				<input type="button" id="btnReset" class="btn btn-default" value="Limpiar">
			</form>
		</div>	
	</div>
	<div id="div_students" class="row" hidden>
		<div class="col-md-12 text-center title-section">
			<h2>Estudiantes</h2>
		</div>
		<div class="col-md-12">
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr>
							<th>N°</th>
							<th>Estudiante</th>
							<th>Edad</th>
							<th>Estatus</th>
							<th>Asistencia</th>
							<th>Observaci&oacute;n</th>
						</tr>
					</thead>
					<tbody id="students"></tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<p><strong>NOTA: Los estudiantes con estatus de deudor y ausente deben dirigirse a administraci&oacute;n.</strong></p>
			</div>
		</div>
	</div>
	<div id="div_btn" class="row element-margin-bottom" hidden>
		<div class="col-md-12">
			<div class="form-group">
			  	<label class="col-md-4 control-label"></label>
			  	<div class="col-md-4">
			    	<input type="button" id="btnSubmit" class="btn btn-primary" value="Guardar">
			    	<a type="button" class="btn btn-warning volver" href="<?php echo Router::url(array('controller'=>'home','action' => 'index')); ?>">Volver</a>
			  	</div>
			</div>
		</div>
	</div>
</div>
<div id="modalMessage" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        	<h4 class="modal-title">Mensaje de Advertencia</h4>
	      	</div>
	      	<div class="modal-body">
	      		<p id="message"></p>
	      	</div>
	      	<div class="modal-footer">
	      		<input type="button" value="Aceptar" class="btn btn-primary" data-dismiss="modal">
	      	</div>
	    </div>
	</div>
</div>
<div id="modalConfirmation" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        	<h4 class="modal-title">Mensaje de Advertencia</h4>
	      	</div>
	      	<div class="modal-body">
	      		<p>¿Est&aacute; seguro que desea guardar la asistencia?</p>
	      	</div>
	      	<div class="modal-footer">
	      		<input type="button" id="btnConfirmation" value="Aceptar" class="btn btn-primary" data-dismiss="modal">
	      	</div>
	    </div>
	</div>
</div>
<div id="modalSuccess" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        	<h4 class="modal-title">Mensaje</h4>
	      	</div>
	      	<div class="modal-body">
	      		<p>Se ha guardado la asistencia exitosamente!</p>
	      	</div>
	      	<div class="modal-footer">
	      		<a type="button" class="btn btn-primary" href="<?php echo Router::url(array('controller'=>'controls','action' => 'consult')); ?>">Aceptar</a>
	      	</div>
	    </div>
	</div>
</div>
<div id="modalWait" class="modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog text-center" role="document">
    	<h2>Por favor espere mientras procesamos la solicitud</h2>
    	<i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
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