<?php
    echo $this->Html->css('bootstrap-select.min');
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div>
                <form class="well form-horizontal" action="<?php echo Router::url(array('controller'=>'users','action' => 'edit/'.$user['User']['id'])); ?>" method="post" id="user_form">
                    <fieldset>
                        <legend class="text-center">Editar Usuario</legend>
                        <div class="form-group">
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
                                    <input type="text" id="address" name="address" placeholder="Direcci&oacute;n" class="form-control" maxlength="120" type="text" value="<?php echo $user['User']['address']; ?>">
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
                            <label class="col-md-4 control-label">Tipo de Usuario</label>
                            <div class="col-md-4">
                                <div class="radio">
                                    <label>
                                        <input type="radio" id="role_a" name="role" value="1" <?php if($user['User']['role_id'] == 1)  echo 'checked'; ?> />Administrador
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" id="role_p" name="role" value="3" <?php if($user['User']['role_id'] == 3)  echo 'checked'; ?> /> Profesor
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <legend>Informaci&oacute;n extra para el Profesor <i id="iconInformation" class="glyphicon glyphicon-chevron-right"></i></legend>
                            </div>
                            <div id="teacherInformacion">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" >Categor&iacute;a</label>
                                        <div class="col-md-4 inputGroupContainer">
                                            <select name="data[Category][]" class="selectpicker form-control" multiple data-live-search="true" data-live-search-placeholder="Buscar" data-actions-box="true">
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?php echo $category['Category']['id'] ?>" <?php if (in_array($category['Category']['id'],$categories_id)) echo "selected"; ?>><?php echo $category['Category']['name'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label"></label>
                            <div class="col-md-4">
                                <input type="submit" class="btn btn-primary" value="Guardar">
                                <a type="button" class="btn btn-warning volver" href="<?php echo Router::url(array('controller'=>'users','action' => 'index')); ?>">Volver</a>
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
    echo $this->Html->script('user');
?>
