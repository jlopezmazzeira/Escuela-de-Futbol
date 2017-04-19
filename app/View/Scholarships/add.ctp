<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div>
                <form class="well form-horizontal" action="<?php echo Router::url(array('controller'=>'scholarships','action' => 'add')); ?>" method="post" id="scholarship_form">
                    <fieldset>
                        <legend class="text-center">Nuevo Descuento</legend>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Nombre</label>
                            <div class="col-md-4 inputGroupContainer">
                              <div class="input-group">
                                  <span class="input-group-addon"><i class="glyphicon glyphicon-menu-right"></i></span>
                                  <input type="text" id="name" name="name" placeholder="Nombre" class="form-control" maxlength="80" required>
                              </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Alias</label>
                            <div class="col-md-4 inputGroupContainer">
                              <div class="input-group">
                                  <span class="input-group-addon"><i class="glyphicon glyphicon-menu-right"></i></span>
                                  <input type="text" id="alias" name="alias" placeholder="Alias" class="form-control" maxlength="80" required>
                              </div>
                            </div>
                        </div>
 
                        <div class="form-group">
                            <label class="col-md-4 control-label" >Descuento</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                                    <input type="text" id="percentage" name="percentage" placeholder="Porcentaje" class="form-control" maxlength="6" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Descripci&oacute;n</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                                    <textarea id="description" name="description" class="form-control" maxlength="120" placeholder="Descripción"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label"></label>
                            <div class="col-md-4">
                                <input type="submit" class="btn btn-primary" value="Agregar">
                                <a type="button" class="btn btn-warning volver" href="<?php echo Router::url(array('controller'=>'scholarships','action' => 'index')); ?>">Volver</a>
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
    echo $this->Html->script('scholarship');
?>