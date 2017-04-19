<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div>
			    <form class="well form-horizontal" action="<?php echo Router::url(array('controller'=>'routes','action' => 'edit/'.$route['Route']['id'])); ?>" method="post" id="route_form">
			        <fieldset>
			        	<legend class="text-center">Editar Ruta</legend>

			            <div class="form-group">
			                <label class="col-md-4 control-label">Nombre</label>
			                <div class="col-md-4 inputGroupContainer">
			                  	<div class="input-group">
			                      	<span class="input-group-addon"><i class="glyphicon glyphicon-bed"></i></span>
			                      	<input type="text" id="name" name="name" placeholder="Nombre de la ruta" class="form-control" maxlength="80" value="<?php echo $route['Route']['name']; ?>" required>
			                  	</div>
			                </div>
			            </div>

			            <div class="form-group">
			                <label class="col-md-4 control-label">Costo</label>
			                <div class="col-md-4 inputGroupContainer">
			                  	<div class="input-group">
			                      	<span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
			                      	<input type="text" id="cost" name="cost" placeholder="Costo de la ruta" class="form-control" maxlength="6" value="<?php echo $route['Route']['cost']; ?>" required>
			                  	</div>
			                </div>
			            </div>

			            <div class="form-group">
			                <label class="col-md-4 control-label">Descripci&oacute;n</label>
			                <div class="col-md-4 inputGroupContainer">
			                    <div class="input-group">
			                        <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
			                        <textarea id="description" name="description" class="form-control" maxlength="120" placeholder="Breve descripción de la ruta"><?php echo $route['Route']['description']; ?></textarea>
			                    </div>
			                </div>
			            </div>

			            <div class="form-group">
			                <label class="col-md-4 control-label"></label>
			                <div class="col-md-4">
			                    <input type="submit" class="btn btn-primary" value="Modificar">
			                    <a type="button" class="btn btn-warning volver" href="<?php echo Router::url(array('controller'=>'routes','action' => 'index')); ?>">Volver</a>
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
    echo $this->Html->script('route');
?>