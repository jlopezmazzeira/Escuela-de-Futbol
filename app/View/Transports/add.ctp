<?php echo $this->Html->css('fileinput.min'); ?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div>
			    <form class="well form-horizontal" enctype="multipart/form-data" action="<?php echo Router::url(array('controller'=>'transports','action' => 'add')); ?>" method="post" id="transport_form">
			        <fieldset>
			        <legend class="text-center">Nuevo Transportista</legend>
			            <div class="form-group">
			                <label class="col-md-4 control-label">Nombre</label>
			                <div class="col-md-4 inputGroupContainer">
			                  	<div class="input-group">
			                      	<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
			                      	<input type="text" id="name" name="name" placeholder="Nombre del transportista" class="form-control" maxlength="80" required>
			                  	</div>
			                </div>
			            </div>
			            <div class="form-group">
			                <label class="col-md-4 control-label">Apellido</label>
			                <div class="col-md-4 inputGroupContainer">
			                  	<div class="input-group">
			                      	<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
			                      	<input type="text" id="lastname" name="lastname" placeholder="Apellido del transportista" class="form-control" maxlength="80" required>
			                  	</div>
			                </div>
			            </div>
			            <div class="form-group">
			                <label class="col-md-4 control-label">Tel&eacute;fono</label>
			                <div class="col-md-4 inputGroupContainer">
			                    <div class="input-group">
			                        <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
			                        <input type="text" id="movil" name="movil" placeholder="0995850151" class="form-control" maxlength="20" required>
			                    </div>
			                </div>
			            </div>
			            <div class="form-group">
			                <label class="col-md-4 control-label">Tel&eacute;fono</label>
			                <div class="col-md-4 inputGroupContainer">
			                    <div class="input-group">
			                        <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
			                        <input type="text" id="phone" name="phone" placeholder="2643959" class="form-control" maxlength="13">
			                    </div>
			                </div>
			            </div>
			            <div class="form-group">
			                <label class="col-md-4 control-label">Licencia</label>
			                <div class="col-md-4">
			                	<input type="file" id="license" name="data[File][license]" class="form-control file">
			                </div>
			            </div>
			            <div class="form-group">
			                <label class="col-md-4 control-label">Matricula</label>
			                <div class="col-md-4">
			                	<input type="file" id="enrollment" name="data[File][enrollment]" class="form-control file">
			                </div>
			            </div>
			            <div class="form-group">
			                <label class="col-md-4 control-label">Permiso</label>
			                <div class="col-md-4">
			                	<input type="file" name="data[File][permission]" id="permission" class="form-control file">
			                </div>
			            </div>
			            <div class="form-group">
			                <label class="col-md-4 control-label"></label>
			                <div class="col-md-4">
			                    <input type="submit" class="btn btn-primary" value="Guardar">
			                    <a type="button" class="btn btn-warning volver" href="<?php echo Router::url(array('controller'=>'transports','action' => 'index')); ?>">Volver</a>
			                </div>
			            </div>
			        </fieldset>
			    </form>
			</div>
		</div>
	</div>
</div>
<?php 
	/*Library File Input*/
	echo $this->Html->script('fileinput.min');
	echo $this->Html->script('fileinput_locale_es');
	
    echo $this->Html->script('bootstrapValidator');
	echo $this->Html->script('transport');
?>
