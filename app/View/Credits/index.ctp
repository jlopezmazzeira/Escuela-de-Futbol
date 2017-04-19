<?php
    echo $this->Html->css('bootstrap-datepicker3.min');
    echo $this->Html->css('bootstrap-select.min');
?>
<div class="container">
	<div class="row">
		<div class="col-md-12 text-center title-section">
			<h1>Notas de Cr&eacute;ditos</h1>
			<span>
				<a href="<?php echo Router::url(array('controller'=>'home','action' => 'index')); ?>" title="Regresar">
					<i class="fa fa-reply action" aria-hidden="true"></i>
				</a>
			</span>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 text-center">
			<div class="col-md-offset-3 col-md-7">
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
			<div class="col-md-offset-3 col-md-7 element-margin-bottom">
			  	<form class="form-inline">
			  		<div class="form-group">
    					<div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-end-date="0d">
    						<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i>
							</span>
      						<input type="text" id="date_from" class="form-control input-height">
    					</div>
  					</div>
  					<div class="form-group">
    					<div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-end-date="0d">
      						<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i>
							</span>
      						<input type="text" id="date_until" class="form-control input-height">
    					</div>
  					</div>
  					<button type="button" id="filter_date_range" class="btn btn-primary">Buscar</button>
  					<button type="button" id="reset" class="btn btn-primary">Limpiar</button>
			  	</form>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="table-responsive">
  			<table class="table order-table">
  				<thead>
  					<tr>
						<th>N°</th>
						<th>Nombre</th>
						<th>Tipo de Documento</th>
						<th>N° de Documento</th>
						<th>Tel&eacute;fono</th>
						<th>Fecha</th>
						<th>Monto</th>
						<th>Acci&oacute;n</th>
					</tr>
  				</thead>
  				<tbody>
				<?php 
					if ($size > 0) {
						$i = 1;
						foreach ($credits as $credit):
				?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $credit['p']['name']; ?></td>
							<td><?php echo $credit['p']['document_type']; ?></td>
							<td><?php echo $credit['p']['document_number']; ?></td>
							<td><?php echo $credit['p']['phone']; ?></td>
							<td><?php echo $credit['Credit']['date_created']; ?></td>
							<td><?php echo $credit['Credit']['amount']; ?></td>
							<td>
								<a href="#" title="Eliminar nota de crédito" onclick='deleteCredit(<?php echo $credit['Credit']['id']; ?>)'><i class="fa fa-trash-o action delete"></i></a>
							</td>
						</tr>
				<?php 		
						$i++;
						endforeach;
					} else {
				?>
					<tr>
						<td colspan="6" class="text-center">No existen notas de cr&eacute;ditos</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<form id="delete_form" action="<?php echo Router::url(array('controller'=>'credits','action' => 'delete')); ?>" method="POST">
	<div id="modalDeleteCredit" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
		    <div class="modal-content">
		    	<div class="modal-header">
		        	<h4 class="modal-title">Eliminar Nota de Cr&eacute;dito</h4>
		      	</div>
		      	<div class="modal-body">
		        	<p>¿Est&aacute; seguro que desea eliminar la nota de cr&eacute;dito?</p>
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
	      		<a href="<?php echo Router::url(array('controller'=>'credits','action' => 'index')); ?>" type="button" class="btn btn-default">Cerrar</a>
	      	</div>
	    </div>
	</div>
</div>
<?php 
	/*Library Datepicker Bootstrap*/
	echo $this->Html->script('bootstrap-datepicker.min');
	echo $this->Html->script('bootstrap-datepicker.es.min');

	echo $this->Html->script('filter-credits-notes');
?>