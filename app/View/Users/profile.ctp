<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div>
                <form class="well form-horizontal" action="<?php echo Router::url(array('controller'=>'users','action' => 'profile/'.$user['User']['id'])); ?>" method="post" id="user_form" role="form" data-toggle="validator">
                    <fieldset>
                        <legend class="text-center">Configurar Perfil</legend>
                        <div class="form-group has-feedback">
                            <label class="col-md-4 control-label">Nombre</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                    <input type="text" id="name" name="name" placeholder="Nombre del usuario" class="form-control" maxlength="80" value="<?php echo $user['User']['name']; ?>">
                                </div>   
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Apellido</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                    <input type="text" id="lastname" name="lastname" placeholder="Apellido del usuario" class="form-control" maxlength="80" value="<?php echo $user['User']['lastname']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Direcci&oacute;n</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                                    <input type="text" id="address" name="address" placeholder="Direcci&oacute;n" class="form-control" maxlength="120" value="<?php echo $user['User']['address']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Tel&eacute;fono</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
                                    <input type="text" id="phone" name="phone" placeholder="0998700398" class="form-control" maxlength="13" value="<?php echo $user['User']['phone']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label"Correo> Correo Electr&oacute;nico</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                                    <input type="text" id="email" name="email" placeholder="pedro.tovar@gmail.com" class="form-control" maxlength="80" value="<?php echo $user['User']['email']; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" >Usuario</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                    <input type="text" id="username" name="username" placeholder="tovarpedro241" class="form-control" maxlength="30" value="<?php echo $user['User']['username']; ?>">
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
</div>
<?php 
    echo $this->Html->script('user');
?>
