<?php
    echo $this->Html->css('bootstrap-datepicker3.min');
?>
<div class="container">
	<div class="row">
		<div class="col-md-12 text-center title-section">
			<h1>Pagos a Proveedores</h1>
			<span>
				<a href="<?php echo Router::url(array('controller'=>'home','action' => 'index')); ?>" title="Regresar">
					<i class="fa fa-reply action" aria-hidden="true"></i>
				</a>
			</span>
			<?php if (!empty($payments_expired) || !empty($payments_to_expire)): ?>
					<a id="payments_pending" href="#" title="Deudas pendientes"><i class="fa fa-bar-chart action" aria-hidden="true"></i></a>
			<?php endif ?>
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
						<th>Proveedor</th>
						<th>Factura</th>
						<th>N&uacute;mero de retenci&oacute;n</th>
						<th>Fecha</th>
						<th>Descripci&oacute;n</th>
						<th>Estatus</th>
						<th>Acci&oacute;n</th>
					</tr>
  				</thead>
  				<tbody>
				<?php 
					if ($size > 0) {
						$i = 1;
						foreach ($payments as $payment):
							$status;
							if ($payment['Payment']['status'] == 1) {
								$status = "Pagada";
							} elseif ($payment['Payment']['status'] == 2) {
								$status = "Anulada";
							} elseif ($payment['Payment']['status'] == 3) {
								$status = "Pendiente";
							}
				?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $payment['p']['name']; ?></td>
							<td><?php echo $payment['Payment']['bill_code']; ?></td>
							<td><?php echo $payment['Payment']['retention_number']; ?></td>
							<td><?php echo $payment['Payment']['date_payment']; ?></td>
							<td><?php echo $payment['Payment']['description']; ?></td>
							<td><?php echo $status; ?></td>
							<td>
								<?php if ($payment['Payment']['status'] == 3): ?>
										<a href="<?php echo Router::url(array('controller'=>'payments','action' => 'edit/'.$payment['Payment']['id'])); ?>" title="Editar"><i class="fa fa-pencil action"></i></a>
								<?php endif ?>
								<a href="<?php echo Router::url(array('controller'=>'payments','action' => 'detailPayment/'.$payment['Payment']['id'])); ?>" title="Ver detalle"><i class="fa fa-list-alt action"></i></a>
								<?php if ($payment['Payment']['status'] == 1): ?>
										<a href="#" title="Anular Pago" onclick='cancelPayment(<?php echo $payment['Payment']['id']; ?>)'><i class="fa fa-ban action delete"></i></a>
								<?php endif ?>
							</td>
						</tr>
				<?php 		
						$i++;
						endforeach;
					} else {
				?>
					<tr>
						<td colspan="8" class="text-center">No Existen pagos registrados</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div id="modalPayments" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-payment" role="document">
	    <div class="modal-content">
	      	<div class="modal-body">
	      		<?php if (!empty($payments_expired)): ?>
	      		<div class="row">
  				    <div class="col-md-12">
  						<h3 class="text-center">Facturas Vencidas</h3>
  					</div>
  					<div class="dashboard">
	  					<?php foreach ($payments_expired as $payment): ?>
	  							<div class="col-md-4">
							        <div class="widget events-widget">
							            <header class="widget-header">
							                <h4 class="widget-headline"><?php echo $payment['Payment']['bill_code']; ?></h4>
							            </header>
							            <div class="widget-body">
							                <p class="widget-subHeadline"><b>Proveedor: </b> <?php echo $payment['p']['name']; ?></p>
							                <p class="widget-subHeadline"><b>Fecha: </b> <?php echo $payment['Payment']['date_payment']; ?></p>
							                <p class="widget-subHeadline"><b>Monto:</b> <?php echo $payment['Payment']['total']; ?></p>
							            </div>
							        </div>
								</div>
	  					<?php endforeach; ?>
  					</div>
	      		</div>
	      		<?php endif; ?>
	      		<?php if (!empty($payments_to_expire)): ?>
	      		<div class="row">
  				    <div class="col-md-12">
  						<h3 class="text-center">Facturas a Vencerse</h3>
  					</div>
  					<div class="dashboard">
	  					<?php foreach ($payments_to_expire as $payment): ?>
	  							<div class="col-md-4">
							        <div class="widget events-widget">
							            <header class="widget-header">
							                <h4 class="widget-headline"><?php echo $payment['Payment']['bill_code']; ?></h4>
							            </header>
							            <div class="widget-body">
							                <p class="widget-subHeadline"><b>Proveedor: </b> <?php echo $payment['p']['name']; ?></p>
							                <p class="widget-subHeadline"><b>Fecha: </b> <?php echo $payment['Payment']['date_payment']; ?></p>
							                <p class="widget-subHeadline"><b>Monto:</b> <?php echo $payment['Payment']['total']; ?></p>
							            </div>
							        </div>
								</div>
	  					<?php endforeach; ?>
  					</div>
	      		</div>
	      		<?php endif; ?>
	      	</div>
	      	<div class="modal-footer">
	      		<input type="button" value="Aceptar" class="btn btn-primary" data-dismiss="modal"> 
	      	</div>
	    </div>
	</div>
</div>
<form id="cancel_payment_form" action="<?php echo Router::url(array('controller'=>'payments','action' => 'cancelPayment')); ?>" method="POST">
	<div id="modalCancelPayment" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
		    <div class="modal-content">
		    	<div class="modal-header">
		        	<h4 class="modal-title">Anular Pago</h4>
		      	</div>
		      	<div class="modal-body">
		        	<p>Est&aacute; seguro que desea anular el pago?</p>
		      	</div>
		      	<div class="modal-footer">
		      		<button type="submit" class="btn btn-danger">Aceptar</button>
		        	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
		      	</div>
		    </div>
		</div>
	</div>
</form>
<div id="modalMessage" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        	<h4 class="modal-title">Mensaje</h4>
	      	</div>
	      	<div class="modal-body">
	        	<p class="message"></p>
	      	</div>
	      	<div class="modal-footer">
	      		<a id="btnMessage" href="<?php echo Router::url(array('controller'=>'payments','action' => 'index')); ?>" type="button" class="btn btn-primary">Aceptar</a>
	      	</div>
	    </div>
	</div>
</div>
<?php 
	/*Library Datepicker Bootstrap*/
	echo $this->Html->script('bootstrap-datepicker.min');
	echo $this->Html->script('bootstrap-datepicker.es.min');

	echo $this->Html->script('filter-provider-bill'); 
?>