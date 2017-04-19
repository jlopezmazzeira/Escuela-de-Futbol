<?php
    echo $this->Html->css('bootstrap-select.min');
?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div>
			    <form class="well form-horizontal" action="<?php echo Router::url(array('controller'=>'peoples','action' => 'addProvider')); ?>" method="POST" id="provider_form">
			        <fieldset>
			        	<legend class="text-center">Nuevo Proveedor</legend>

				        <div class="form-group">
				            <label class="col-md-4 control-label">RUC</label>
				            <div class="col-md-4 inputGroupContainer">
				                <div class="input-group">
				                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
				                    <input type="text" id="document_number" name="document_number" placeholder="1705247392001" maxlength="13" class="form-control" required>
				                </div>
				            </div>
				        </div>

				        <div class="form-group">
				            <label id="person_name" class="col-md-4 control-label">Nombre</label>
				            <div class="col-md-4 inputGroupContainer">
				              <div class="input-group">
				                  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
				                  <input type="text" id="name" name="name" placeholder="Nombre" maxlength="80" class="form-control controles" required>
				              </div>
				            </div>
				        </div>

				        <div class="form-group">
				            <label class="col-md-4 control-label">Tipo</label>
				            <div class="col-md-4 inputGroupContainer">
			                    <select id="type_provider" name="type_provider" class="selectpicker form-control" data-live-search="true" data-live-search-placeholder="Buscar" required>
			                    	<option value="">Seleccione</option>
			                    	<?php foreach($types_providers as $type_provider): ?>
			                    			<option value="<?php echo $type_provider['TypesProvider']['id']; ?>"><?php echo $type_provider['TypesProvider']['name']; ?></option>
			                    	<?php endforeach; ?>
			                    </select>
				            </div>
				        </div>

				        <div class="form-group">
				            <label class="col-md-4 control-label">Contabilidad</label>
				            <div class="col-md-4">
				            	<?php foreach ($types_accounting as $type_account): ?>
					                <div class="radio">
					                    <label>
					                        <input type="radio" id="r<?php echo $type_account['TypesAccounting']['id']; ?>" name="type_accounting" value="<?php echo $type_account['TypesAccounting']['id']; ?>" /><?php echo $type_account['TypesAccounting']['name']; ?>
					                    </label>
					                </div>
				            	<?php endforeach; ?>
				            </div>
				        </div>

				        <div class="form-group">
				            <label class="col-md-4 control-label">Direcci&oacute;n</label>
				            <div class="col-md-4 inputGroupContainer">
				                <div class="input-group">
				                    <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
				                    <input type="text" id="address" name="address" placeholder="Dirección" maxlength="120" class="form-control controles">
				                </div>
				            </div>
				        </div>

				        <div class="form-group">
				            <label class="col-md-4 control-label">Tel&eacute;fono(s)</label>
				            <div class="col-md-4 inputGroupContainer">
				                <div class="input-group">
				                    <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
				                    <input type="text" id="phone" name="phone" placeholder="0987698131" maxlength="20" class="form-control controles">
				                </div>
				            </div>
				        </div>
				        <div class="form-group">
				            <label class="col-md-4 control-label"Correo>Email</label>
				            <div class="col-md-4 inputGroupContainer">
				                <div class="input-group">
				                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
				                    <input type="text" id="email" name="email" placeholder="Correo electrónico" maxlength="80" class="form-control controles">
				                </div>
				            </div>
				        </div>

				        <div class="form-group">
				            <label class="col-md-4 control-label"></label>
				            <div class="col-md-4">
				                <input type="submit" class="btn btn-primary" value="Guardar">
				                <a type="button" class="btn btn-warning volver" href="<?php echo Router::url(array('controller'=>'peoples','action' => 'providers')); ?>">Volver</a>
				            </div>
				        </div>
			        </fieldset>
			    </form>
			</div>
		</div>
	</div>
</div>
<?php
	/*Library Select*/
	echo $this->Html->script('bootstrap-select.min'); 
	echo $this->Html->script('defaults-es_CL.min');

	echo $this->Html->script('bootstrapValidator');
	echo $this->Html->script('provider');
?>
