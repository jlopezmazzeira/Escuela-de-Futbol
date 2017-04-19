<div class="container">
	<div class="row">
		<div class="col-md-12">
			<form class="well form-horizontal" action="<?php echo Router::url(array('controller'=>'users','action' => 'changePassword')); ?>" method="post" id="change_password_form">
				<fieldset>
                        <legend class="text-center">Cambiar Contraseña</legend>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Contraseña nueva</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk"></i></span>
                                    <input type="password" id="password_update" name="password_update" class="form-control" maxlength="80">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Confirmar contraseña</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk"></i></span>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" maxlength="80">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label"></label>
                            <div class="col-md-4">
                                <input type="submit" class="btn btn-primary" value="Guardar">
                                <input type="button" class="btn btn-warning volver" value="Volver" onClick='history.go(-1)'>
                            </div>
                        </div>
                </fieldset>
			</form>
		</div>
	</div>
</div>
<?php 
    echo $this->Html->script('bootstrapValidator');
    echo $this->Html->script('user');
?>