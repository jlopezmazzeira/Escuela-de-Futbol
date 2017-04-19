<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div>
                <form class="well form-horizontal" action="<?php echo Router::url(array('controller'=>'scholarships','action' => 'edit/'.$scholarship['Scholarship']['id'])); ?>" method="post"  id="scholarship_form">
                    <fieldset>
                        <legend class="text-center">Editar Descuento</legend>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Nombre</label>
                            <div class="col-md-4 inputGroupContainer">
                              <div class="input-group">
                                  <span class="input-group-addon"><i class="glyphicon glyphicon-menu-right"></i></span>
                                  <input type="text" id="name" name="name" placeholder="Nombre" class="form-control" maxlength="80" value="<?php echo $scholarship['Scholarship']['name']; ?>" required>
                              </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Alias</label>
                            <div class="col-md-4 inputGroupContainer">
                              <div class="input-group">
                                  <span class="input-group-addon"><i class="glyphicon glyphicon-menu-right"></i></span>
                                  <input type="text" id="alias" name="alias" placeholder="Alias" class="form-control" maxlength="80" value="<?php echo $scholarship['Scholarship']['alias']; ?>" required>
                              </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" >Descuento</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                                    <input type="text" id="percentage" name="percentage" placeholder="Porcentaje" class="form-control" maxlength="6" value="<?php echo $scholarship['Scholarship']['percentage']; ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Descripci&oacute;n</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                                    <textarea id="description" name="description" class="form-control" maxlength="120" placeholder="Descripción"><?php echo $scholarship['Scholarship']['description']; ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label"></label>
                            <div class="col-md-4">
                                <input type="submit" class="btn btn-primary" value="Modificar">
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