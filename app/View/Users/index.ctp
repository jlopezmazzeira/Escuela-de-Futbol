<div class="container">
	<div class="row">
		<div class="col-md-12 text-center title-section">
			<h1>Informaci&oacute;n de Usuarios</h1>
			<span>
				<a href="<?php echo Router::url(array('controller'=>'users','action' => 'add')); ?>" title="Agregar usuario"><i class="fa fa-user-plus action" aria-hidden="true"></i></a>
			</span>
			<span>
				<a href="<?php echo Router::url(array('controller'=>'home','action' => 'index')); ?>" title="Regresar">
					<i class="fa fa-reply action" aria-hidden="true"></i>
				</a>
			</span>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 text-center">
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
	</div>
	<div class="row">
		<div>
			<div class="table-responsive">
  				<table class="table order-table">
  					<thead>
  						<tr>
	  						<th>N°</th>
	  						<th>Nombre</th>
							<th>Apellido</th>
							<th>Direcci&oacute;n</th>
							<th>Tel&eacute;fono</th>
							<th>Email</th>
			                <th>Usuario</th>
			                <th>Tipo</th>
							<th>Acci&oacute;n</th>
	  					</tr>
  					</thead>
  					<tbody>
  						<?php 
  							if ($size > 0){
  								$i = 1;
  								foreach ($users as $user){
  						?>
  							<tr>
  								<td><?php echo $i; ?></td>
  								<td><?php echo $user['User']['name']; ?></td>
  								<td><?php echo $user['User']['lastname']; ?></td>
  								<td><?php echo $user['User']['address']; ?></td>
  								<td><?php echo $user['User']['phone']; ?></td>
  								<td><?php echo $user['User']['email']; ?></td>
  								<td><?php echo $user['User']['username']; ?></td>
  								<td><?php echo $user['r']['name']; ?></td>
  								<td>
  									<a href="<?php echo Router::url(array('controller'=>'users','action' => 'edit/'.$user['User']['id'])); ?>" title="Editar"><i class="fa fa-pencil action" aria-hidden="true"></i></a>
					                <a href="#" title="Eliminar usuario" onclick='deleteUser(<?php echo $user['User']['id']; ?>)'><i class="fa fa-trash-o action delete"></i></a>
  								</td>
  							</tr>
  						<?php 
  									$i++;
  								} 	
  							} else {
  						?>
  								<tr>
  									<td colspan="9" class="text-center">No existen usuarios registrados</td>
  								</tr>
  						<?php
  							} 
  						?>
  						
  					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<form id="delete_form" action="<?php echo Router::url(array('controller'=>'users','action' => 'delete')); ?>" method="POST">
	<div id="modalDeleteUser" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
		    <div class="modal-content">
		    	<div class="modal-header">
		        	<h4 class="modal-title">Eliminar Usuario</h4>
		      	</div>
		      	<div class="modal-body">
		        	<p>¿Est&aacute; seguro que desea eliminar el usuario?</p>
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
	      		<a href="<?php echo Router::url(array('controller'=>'users','action' => 'index')); ?>" type="button" class="btn btn-default">Cerrar</a>
	      	</div>
	    </div>
	</div>
</div>
<?php echo $this->Html->script('user'); ?>