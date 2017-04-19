<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div>
                <form class="well form-horizontal" action="<?php echo Router::url(array('controller'=>'parameters','action' => 'edit/'.$parameter['Parameter']['id'])); ?>" method="post" id="parameter_form">
                    <fieldset>
                        <legend class="text-center">Editar Par&aacute;metro</legend>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Nombre</label>
                            <div class="col-md-4 inputGroupContainer">
                              <div class="input-group">
                                  <span class="input-group-addon"><i class="glyphicon glyphicon-menu-right"></i></span>
                                  <input type="text" id="name" name="name" placeholder="Nombre" class="form-control" maxlength="80" value="<?php echo $parameter['Parameter']['name']; ?>" <?php if($parameter['Parameter']['name'] == "Fitness") echo "readonly"; ?> required>
                              </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Alias</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                                    <input type="text" id="alias" name="alias" placeholder="Alias" class="form-control" maxlength="15" value="<?php echo $parameter['Parameter']['alias']; ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Valor</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                                    <input type="text" id="value" name="value" placeholder="Valor" class="form-control" maxlength="6" value="<?php echo $parameter['Parameter']['value']; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Descripci&oacute;n</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                                    <textarea id="description" name="description" class="form-control" maxlength="120" placeholder="Descripción"><?php echo $parameter['Parameter']['description']; ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label"></label>
                            <div class="col-md-4">
                                <input type="submit" class="btn btn-primary" value="Modificar">
                                <a type="button" class="btn btn-warning volver" href="<?php echo Router::url(array('controller'=>'parameters','action' => 'index')); ?>">Volver</a>
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
    echo $this->Html->script('parameter');
?>