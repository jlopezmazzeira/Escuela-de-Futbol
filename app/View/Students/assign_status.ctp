<?php
	echo $this->Html->css('bootstrap-datepicker3.min');
    echo $this->Html->css('bootstrap-select.min');
?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<form class="well form-horizontal" action="<?php echo Router::url(array('controller'=>'students', 'action' => 'assignStatus/'.$student['Student']['id'])); ?>" method="post" id="form_change_status">
			    <fieldset>
			      	<legend class="text-center">Asignar Estatus a Estudiante</legend>

			        <div class="form-group">
			            <label class="col-md-4 control-label">Estatus</label>
			            <div class="col-md-6 inputGroupContainer">
			                <select id="status" name="status" class="selectpicker form-control" data-live-search="true" data-live-search-placeholder="Buscar">
			                    <option value="1" <?php if($student['Student']['status'] == 1) echo "selected"; ?>>Activo</option>
		                        <option value="4" <?php if($student['Student']['status'] == 4) echo "selected"; ?>>Inactivo</option>
		                        <option value="3" <?php if($student['Student']['status'] == 3) echo "selected"; ?>>Inhabilitado</option>
		                        <option value="5" <?php if($student['Student']['status'] == 5) echo "selected"; ?>>Ausente</option>
			                </select>
			            </div>
			      	</div>
			      	<div class="form-group">
			            <label class="col-md-4 control-label">Motivo</label>
			            <div class="col-md-6 inputGroupContainer">
			            	<textarea id="observation" name="observation" class="form-control" rows="4" cols="145"><?php echo $student['Student']['observation']; ?>
							</textarea>
			            </div>
			      	</div>
			      	<div id="div_date" class="form-group" hidden>
					  	<label class="col-md-4 control-label">Fecha a pagar</label>
					  	<div class="col-md-6 inputGroupContainer">
					  		<div class="input-group date" id="date_payment_div" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-end-date="0d">
					  			<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i>
							    </span>
							    <input type="text" id="date_payment" name="date_payment" class="form-control">
							</div>
					  	</div>
					</div>
			      	<div class="form-group">
			          	<label class="col-md-4 control-label"></label>
			          	<div class="col-md-4">
			              	<input type="submit" class="btn btn-primary" value="Asignar">
			              	<a type="button" class="btn btn-warning volver" href="<?php echo Router::url(array('controller'=>'students','action' => 'view/'.$student['Student']['id'])); ?>">Volver</a>
			         	 </div>
			      	</div>
			      	<input type="hidden" name="status_previus" id="status_previus" value="<?php echo $student['Student']['status']; ?>">
			    </fieldset>
			</form>
		</div>
	</div>
</div>
<?php 
	/*Library Datepicker Bootstrap*/
	echo $this->Html->script('bootstrap-datepicker.min');
	echo $this->Html->script('bootstrap-datepicker.es.min');

	/*Labrary Select Bootstrap*/
	echo $this->Html->script('bootstrap-select.min'); 
	echo $this->Html->script('defaults-es_CL.min');

	echo $this->Html->script('bootstrapValidator');
	echo $this->Html->script('filter-student');
?>