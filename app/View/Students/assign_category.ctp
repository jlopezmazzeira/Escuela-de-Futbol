<?php
    echo $this->Html->css('bootstrap-select.min');
?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<form class="well form-horizontal" action="<?php echo Router::url(array('controller'=>'students', 'action' => 'assignCategory/'.$student['Student']['id'])); ?>" method="post">
			    <fieldset>
			      	<legend class="text-center">Asignar Categor&iacute;a a Estudiante</legend>

			        <div class="form-group">
			            <label class="col-md-4 control-label">Categor&iacute;a</label>
			            <div class="col-md-6 inputGroupContainer">
			                <select id="category_id" name="category_id" class="selectpicker form-control" data-live-search="true" data-live-search-placeholder="Buscar">
			                    <?php foreach ($categories as $category): ?>
			                        <option value="<?php echo $category['Category']['id']; ?>" <?php if ($student['Student']['category_id'] == $category['Category']['id']) echo "selected"; ?>>
			                            <?php echo $category['Category']['name']; ?>
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