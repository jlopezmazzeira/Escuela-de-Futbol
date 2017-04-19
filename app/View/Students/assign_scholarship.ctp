<?php
    echo $this->Html->css('bootstrap-select.min');
?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<form class="well form-horizontal" action="<?php echo Router::url(array('controller'=>'students', 'action' => 'assignScholarship/'.$student['Student']['id'])); ?>" method="post">
			    <fieldset>
			      	<legend class="text-center">Asignar Descuento a Estudiante</legend>

			        <div class="form-group">
			            <label class="col-md-4 control-label" >Descuento</label>
			            <div class="col-md-6 inputGroupContainer">
			                <select id="scholarship_id" name="scholarship_id" class="selectpicker form-control" data-live-search="true" data-live-search-placeholder="Buscar">
			                    <option data-hidden="true"></option>
			                    <option value="">No asignar descuento</option>
			                    <?php foreach ($scholarships as $scholarship): ?>
			                        <option value="<?php echo $scholarship['Scholarship']['id']; ?>" <?php if ($student['Student']['scholarship_id'] == $scholarship['Scholarship']['id']) echo "selected"; ?>>
			                            <?php echo $scholarship['Scholarship']['name']; ?>
			                        </option>
			                    <?php endforeach; ?>
			                </select>
			            </div>
			      	</div>

			      	<div class="form-group">
			          	<label class="col-md-4 control-label"></label>
			          	<div class="col-md-4">
			              	<input type="submit" class="btn btn-primary" value="Asignar">
			              	<a type="button" class="btn btn-warning volver" href="<?php echo Router::url(array('controller'=>'students','action' => 'view/'.$student['Student']['id'])); ?>">Volver</a>
			          	</div>
			      	</div>

			    </fieldset>
			</form>
		</div>
	</div>
</div>
<?php 
	echo $this->Html->script('bootstrap-select.min'); 
	echo $this->Html->script('defaults-es_CL.min');
?>