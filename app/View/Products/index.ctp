<div class="container">
	<div class="row">
		<div class="col-md-12 text-center title-section">
			<h1>Informaci&oacute;n de Productos</h1>
			<span>
				<a href="<?php echo Router::url(array('controller'=>'products','action' => 'add')); ?>" title="Agregar producto"><i class="fa fa-shopping-bag action" aria-hidden="true"></i></a>
			</span>
			<span>
				<a href="<?php echo Router::url(array('controller'=>'home','action' => 'index')); ?>" title="Regresar">
					<i class="fa fa-reply action" aria-hidden="true"></i>
				</a>
			</span>
		</div>
	</div>
	<div class="row">
		<div class="col-md-offset-3 col-md-6">
            <div id="custom-search-input">
                <div class="input-group col-md-12">
                    <input type="text" id="search" class="form-control input-lg light-table-filter" data-table="order-table" placeholder="Buscar" />
                    <span class="input-group-btn">
                        <button class="btn btn-info btn-lg" type="button">
                            <i class="glyphicon glyphicon-search"></i>
                        </button>
                    </span>
                </div>
            </div>
        </div>
	</div>
	<div class="row">
		<div>
			<div class="table-responsive">
  				<table class="table order-table">
  					<thead>
  						<tr>
	  						<th>N°</th>
	  						<th>Nombre</th>
	  						<th>Descripci&oacute;n</th>
	  						<th>Costo</th>
	  						<th>Acci&oacute;n</th>
	  					</tr>
  					</thead>
  					<tbody>
					<?php 
						if ($size > 0) {
							$i = 1;
							foreach ($products as $product):
					?>
								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo $product['Product']['name']; ?></td>
									<td><?php echo $product['Product']['description']; ?></td>
									<td><?php echo $product['Product']['cost']; ?></td>
									<td>
					                	<a href="<?php echo Router::url(array('controller'=>'products','action' => 'edit/'.$product['Product']['id'])); ?>" title="Editar"><i class="fa fa-pencil action" aria-hidden="true"></i></a>
					                	<a href="#" title="Eliminar producto" onclick='deleteProduct(<?php echo $product['Product']['id']; ?>)'><i class="fa fa-trash-o action delete"></i></a>
									</td>
								</tr>
					<?php 		
								$i++;
							endforeach;
						} else {
					?>
						<tr>
							<td colspan="6" class="text-center">No Existen productos registrados</td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<form id="delete_form" action="<?php echo Router::url(array('controller'=>'products','action' => 'delete')); ?>" method="POST">
	<div id="modalDeleteProduct" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
		    <div class="modal-content">
		    	<div class="modal-header">
		        	<h4 class="modal-title">Eliminar Producto</h4>
		      	</div>
		      	<div class="modal-body">
		        	<p>Est&aacute; seguro que desea eliminar el producto?</p>
		      	</div>
		      	<div class="modal-footer">
		        	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		        	<button type="submit" class="btn btn-danger">Eliminar</button>
		      	</div>
		    </div>
		</div>
	</div>
</form>
<div id="modalMessage" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	      	<div class="modal-body">
	        	<p class="message"></p>
	      	</div>
	      	<div class="modal-footer">
	      		<a href="<?php echo Router::url(array('controller'=>'products','action' => 'index')); ?>" type="button" class="btn btn-default">Cerrar</a>
	      	</div>
	    </div>
	</div>
</div>
<?php echo $this->Html->script('product'); ?>