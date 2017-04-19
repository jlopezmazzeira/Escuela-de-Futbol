<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div>
                <form class="well form-horizontal" action="<?php echo Router::url(array('controller'=>'categories','action' => 'edit/'.$category['Category']['id'])); ?>" method="post" id="category_form">
                    <fieldset>
                        <legend class="text-center">Editar Categor&iacute;a</legend>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Nombre</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-th-list"></i></span>
                                    <input type="text" id="name" name="name" placeholder="Nombre de la Categor&iacute;a" class="form-control" maxlength="80" value="<?php echo $category['Category']['name']; ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" >Edad M&iacute;nima</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-minus-sign"></i></span>
                                    <input type="text" id="age_min" name="age_min" placeholder="Edad M&iacute;nima" class="form-control" maxlength="2" value="<?php echo $category['Category']['age_min']; ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" >Edad M&aacute;xima</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-plus-sign"></i></span>
                                    <input type="text" id="age_max" name="age_max" placeholder="Edad M&aacute;xima" class="form-control" maxlength="2" value="<?php echo $category['Category']['age_max']; ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label"></label>
                            <div class="col-md-4">
                                <input type="submit" class="btn btn-primary" value="Modificar">
                                <a type="button" class="btn btn-warning volver" href="<?php echo Router::url(array('controller'=>'categories','action' => 'index')); ?>">Volver</a>
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
    echo $this->Html->script('category');
?>