<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div>
			    <form class="well form-horizontal" action="<?php echo Router::url(array('controller'=>'peoples','action' => 'addClient')); ?>" method="POST" id="client_form">
			        <fieldset>
				        <legend class="text-center">Nuevo Cliente</legend>

				        <div class="form-group">
				            <label class="col-md-4 control-label">Tipo Documento</label>
				            <div class="col-md-4">
				                <div class="radio">
				                    <label>
				                        <input type="radio" name="document_type" value="Pasaporte"/>Pasaporte
				                    </label>
				                </div>
				                <div class="radio">
				                    <label>
				                        <input type="radio" name="document_type" value="Cedula" />C&eacute;dula
				                    </label>
				                </div>
				                <div class="radio">
				                    <label>
				                        <input type="radio" name="document_type" value="RUC" />RUC
				                    </label>
				                </div>
				            </div>
				        </div>

				        <div class="form-group">
				            <label class="col-md-4 control-label">N&uacute;mero Documento</label>
				            <div class="col-md-4 inputGroupContainer">
				                <div class="input-group">
				                    <span class="input-group-addon"><i class="glyphicon glyphicon-list-alt"></i></span>
				                    <input type="text" id="document_number" name="document_number" placeholder="1709832839" class="form-control">
				                </div>
				            </div>
				        </div>

				            <div class="form-group">
				            <label id="person_name" class="col-md-4 control-label">Nombre</label>
				            <div class="col-md-4 inputGroupContainer">
				              <div class="input-group">
				                  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
				                  <input type="text" id="name" name="name" placeholder="Nombre" class="form-control" maxlength="80">
				              </div>
				            </div>
				        </div>

				        <div class="form-group">
				            <label class="col-md-4 control-label">Direcci&oacute;n</label>
				            <div class="col-md-4 inputGroupContainer">
				                <div class="input-group">
				                    <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
				                    <input type="text" id="address" name="address" placeholder="Direcci&oacute;n" class="form-control" maxlength="120">
				                </div>
				            </div>
				        </div>

				        <div class="form-group">
				            <label class="col-md-4 control-label">Tel&eacute;fono(s)</label>
				            <div class="col-md-4 inputGroupContainer">
				                <div class="input-group">
				                    <span class="input-group-addon"><i class="glyphicon glyphicon-phone-alt"></i></span>
				                    <input type="text" id="phone" name="phone" placeholder="0987698131" class="form-control" maxlength="20">
				                </div>
				            </div>
				        </div>

				        <div class="form-group">
				            <label class="col-md-4 control-label"Correo>Correo Electr&oacute;nico</label>
				            <div class="col-md-4 inputGroupContainer">
				                <div class="input-group">
				                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
				                    <input type="text" id="email" name="email" placeholder="Correo electr&oacute;nico" class="form-control" maxlength="80">
				                </div>
				            </div>
				        </div>

				        <div class="form-group">
				            <label class="col-md-4 control-label"></label>
				            <div class="col-md-4">
				                <input type="submit" class="btn btn-primary" value="Guardar">
				                <a type="button" class="btn btn-warning volver" href="<?php echo Router::url(array('controller'=>'peoples','action' => 'clients')); ?>">Volver</a>
				            </div>
				        </div>
			        </fieldset>
			    </form>
			</div>
		</div>
	</div>
</div>
<?php 
    echo $this->Html->script('bootstrapValidator');
    echo $this->Html->script('client');
?>