<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div>
				<form class="well form-horizontal" action="<?php echo Router::url(array('controller'=>'products','action' => 'add')); ?>" method="post" id="product_form">
				    <fieldset>
				    	<legend class="text-center">Nuevo producto</legend>
					    <div class="form-group">
					      	<label class="col-md-4 control-label">Nombre</label>
					      	<div class="col-md-4 inputGroupContainer">
					      		<div class="input-group">
					      			<span class="input-group-addon"><i class="glyphicon glyphicon-shopping-cart"></i></span>
					      			<input type="text" id="name" name="name" placeholder="Nombre" class="form-control" maxlength="80" required>
					        	</div>
					      	</div>
					    </div>

					    <div class="form-group">
					      	<label class="col-md-4 control-label" >Descripci&oacute;n</label>
				        	<div class="col-md-4 inputGroupContainer">
				        		<div class="input-group">
				      				<span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
				      				<input type="text" id="description" name="description" placeholder="Descripción" class="form-control" maxlength="120">
				        		</div>
					      	</div>
					    </div>

					    <div class="form-group">
					      	<label class="col-md-4 control-label">Costo</label>
					        <div class="col-md-4 inputGroupContainer">
					        	<div class="input-group">
					            	<span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
					      			<input type="text" id="cost" name="cost" placeholder="$ 25.5" class="form-control" maxlength="6" required>
					        	</div>
					      	</div>
					    </div>

				    	<div class="form-group">
				      		<label class="col-md-4 control-label"></label>
				      		<div class="col-md-4">
				        		<input type="submit" class="btn btn-primary" value="Guardar">
				          		<a type="button" class="btn btn-warning volver" href="<?php echo Router::url(array('controller'=>'products','action' => 'index')); ?>">Volver</a>
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
    echo $this->Html->script('product');
?>