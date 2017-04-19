<?php
	echo $this->Html->css('bootstrap-datepicker3.min');
    echo $this->Html->css('bootstrap-select.min');
?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<form id="form_check_category" class="well form-horizontal" action="<?php echo Router::url(array('controller'=>'controls','action' => 'printCheck', 'ext' => 'pdf')); ?>" method="post">
		        <fieldset>
		          	<legend class="text-center">Imprimir ficha de asistencia</legend>
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
		              	<label class="col-md-4 control-label" >Mes</label>
		                <div class="col-md-6 inputGroupContainer">
		                  	<select id="month" name="month" class="selectpicker form-control" data-live-search="true"
		                           data-live-search-placeholder="Buscar" data-actions-box="true">
	                           	<option value="">Seleccione</option>
	                        	<option value="1">Enero</option>
                                <option value="2">Febrero</option>
                                <option value="3">Marzo</option>
                                <option value="4">Abril</option>
                                <option value="5">Mayo</option>
                                <option value="6">Junio</option>
                                <option value="7">Julio</option>
                                <option value="8">Agosto</option>
                                <option value="9">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
		                  	</select>
		                </div>
		          	</div>
		        </fieldset>
		    </form>
		</div>
	</div>
</div>
<?php	
	/*Library Select*/
	echo $this->Html->script('bootstrap-select.min'); 
	echo $this->Html->script('defaults-es_CL.min');

	echo $this->Html->script('bootstrapValidator');
	
	echo $this->Html->script('control');
?>