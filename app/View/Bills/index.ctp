<?php
    echo $this->Html->css('bootstrap-datepicker3.min');
?>
<div class="container">
</script>

	<div class="row">
		<div class="col-md-12 text-center title-section">
			<h1>Facturas</h1>
			<span>
				<a href="#" title="Generar Cierre" onclick="generateClosing()"><i class="fa fa-bar-chart action" aria-hidden="true"></i></a>
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
						<th>Factura</th>
						<th>Nombre</th>
						<th>Modo de pago</th>
						<th>Fecha</th>
						<th>Monto</th>
						<th>Estatus</th>
						<th>Acci&oacute;n</th>
					</tr>
  				</thead>
  				<tbody>
				<?php 
					if ($size > 0) {
						$i = 1;
						foreach ($bills as $bill):
							$status = "";
							if ($bill['Bill']['status'] == 1) {
								$status = "Pagada";
							}

							if ($bill['Bill']['status'] == 2) {
								$status = "Anulada";
							}

							if ($bill['Bill']['status'] == 3) {
								$status = "Pendiente";
							}
				?>
						<tr>
							<td><?php echo $i; ?></td>
							<td>
								<?php if ($bill['Bill']['closed'] == 1): ?>
										<i class="fa fa-check closing"></i>
								<?php endif ?>
								<?php echo $bill['Bill']['bill_code']; ?></td>
							<td><?php echo $bill['p']['name']; ?></td>
							<td><?php echo $bill['mb']['name']; ?></td>
							<td><?php echo $bill['Bill']['date_payment']; ?></td>
							<td><?php echo $bill['Bill']['total']; ?></td>
							<td><?php echo $status; ?></td>
							<td>
		                        <a href="<?php echo Router::url(array('controller'=>'bills','action' => 'detailBill/'.$bill['Bill']['id'])); ?>" title="Ver detalle"><i class="fa fa-list action"></i></a>
		                        <a href="<?php echo Router::url(array('controller'=>'bills','action' => 'printBill/'.$bill['Bill']['id'], 'ext' => 'pdf')); ?>" title="Imprimir" target="_blank"><i class="fa fa-file-pdf-o action"></i></a>
		                        <?php if($bill['Bill']['status'] == 3){ ?>
		                        	<a href="<?php echo Router::url(array('controller'=>'receipts','action' => 'add/'.$bill['Bill']['id'])); ?>" title="Abonar"><i class="fa fa-credit-card action"></i></a>
		                        <?php } ?>
								<?php if($bill['Bill']['status'] == 1 || $bill['Bill']['status'] == 3){ ?>
		                        	<a href="#" title="Anular factura" onclick='cancelInvoice(<?php echo $bill['Bill']['id']; ?>)'><i class="fa fa-ban action delete"></i></a>
		                        <?php } ?>
							</td>
						</tr>
				<?php 		
						$i++;
						endforeach;
					} else {
				?>
					<tr>
						<td colspan="8" class="text-center">No existen facturas registradas</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<form id="cancel_bill_form" action="<?php echo Router::url(array('controller'=>'bills','action' => 'cancelInvoice')); ?>" method="POST">
	<div id="modalCancelBill" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
		    <div class="modal-content">
		    	<div class="modal-header">
		        	<h4 class="modal-title">Anular Factura</h4>
		      	</div>
		      	<div class="modal-body">
		        	<p>Est&aacute; a punto de anular la factura. ¿Desea generar una orden de pago de la factura?</p>
		      	</div>
		      	<div class="modal-footer">
		      		<button type="button" id="generateOrder" class="btn btn-danger">Si</button>
		      		<button type="button" id="cancel" class="btn btn-warning">No</button>
		        	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		      	</div>
		    </div>
		</div>
	</div>
</form>
<div id="modalGenerateClosingConfirmation" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        	<h4 class="modal-title">Generar Cierre</h4>
	      	</div>
	      	<div class="modal-body closing">
	      		<div class="row">
	      			<div class="col-md-12">
	      				<div class="wrapper wrapper-closing">
	      					<div class="block">
						    	<div id="total_check" class="number-closing"></div>
						    	<div class="string">Cheque</div>
						  	</div>
						  	<div class="block">
						    	<div id="total_deposit" class="number-closing"></div>
						    	<div class="string">Deposito</div>
						  	</div>
						  	<div class="block">
						    	<div id="total_cash" class="number-closing"></div>
						    	<div class="string">Efectivo</div>
						 	</div>
						  	<div class="block">
						    	<div id="total_tdc" class="number-closing"></div>
						    	<div class="string">Tarjeta Credito</div>
						  	</div>
						  	<div class="block">
						    	<div id="total_transfer" class="number-closing"></div>
						    	<div class="string">Transferencia</div>
						  	</div>
						  	<div class="block">
						    	<div id="total" class="number-closing"></div>
						    	<div class="string">Total</div>
						  	</div>
						</div>
	      			</div>
	      		</div>
	      		<div class="row">
		      		<form class="form-inline">
		      			<div class="col-md-12 element-margin-bottom-closing">
		      				<div class="col-md-6">
			      				<div class="form-group">
			      					<div class="col-md-1 label-closing">
								    	<label for="hundred_dollars">$100</label>
								    </div>
								    <div class="col-md-5">
								    	<input type="number" class="form-control" id="hundred_dollars" min="0" value="0">
								    </div>
								</div>
			      			</div>
			      			<div class="col-md-6">
			      				<div class="form-group">
			      					<div class="col-md-1 label-closing">
								    	<label for="fifty_cent">¢50</label>
								    </div>
								    <div class="col-md-5">
								    	<input type="number" class="form-control" id="fifty_cents" min="0" value="0">
								    </div>
								</div>
			      			</div>
		      			</div>
		      			<div class="col-md-12 element-margin-bottom-closing">
		      				<div class="col-md-6">
			      				<div class="form-group">
			      					<div class="col-md-1 label-closing">
								    	<label for="fifty_dollars">$50</label>
								    </div>
								    <div class="col-md-5">
								    	<input type="number" class="form-control" id="fifty_dollars" min="0" value="0">
								    </div>
								</div>
			      			</div>
			      			<div class="col-md-6">
			      				<div class="form-group">
			      					<div class="col-md-1 label-closing">
								    	<label for="twenty_five_cents">¢25</label>
								    </div>
								    <div class="col-md-5">
								    	<input type="number" class="form-control" id="twenty_five_cents" min="0" value="0">
								    </div>
								</div>
			      			</div>
		      			</div>
		      			<div class="col-md-12 element-margin-bottom-closing">
		      				<div class="col-md-6">
			      				<div class="form-group">
			      					<div class="col-md-1 label-closing">
								    	<label for="ten_cents">$20</label>
								    </div>
								    <div class="col-md-5">
								    	<input type="number" class="form-control" id="twenty_dollars" min="0" value="0">
								    </div>
								</div>
			      			</div>
			      			<div class="col-md-6">
			      				<div class="form-group">
			      					<div class="col-md-1 label-closing">
								    	<label for="exampleInputName2">¢10</label>
								    </div>
								    <div class="col-md-5">
								    	<input type="number" class="form-control" id="ten_cents" min="0" value="0">
								    </div>
								</div>
			      			</div>
		      			</div>
		      			<div class="col-md-12 element-margin-bottom-closing">
		      				<div class="col-md-6">
			      				<div class="form-group">
			      					<div class="col-md-1 label-closing">
								    	<label for="ten_dollars">$10</label>
								    </div>
								    <div class="col-md-5">
								    	<input type="number" class="form-control" id="ten_dollars" min="0" value="0">
								    </div>
								</div>
			      			</div>
			      			<div class="col-md-6">
			      				<div class="form-group">
			      					<div class="col-md-1 label-closing">
								    	<label for="five_cents">¢5</label>
								    </div>
								    <div class="col-md-5">
								    	<input type="number" class="form-control" id="five_cents" min="0" value="0">
								    </div>
								</div>
			      			</div>
		      			</div>
		      			<div class="col-md-12 element-margin-bottom-closing">
		      				<div class="col-md-6">
			      				<div class="form-group">
			      					<div class="col-md-1 label-closing">
								    	<label for="five_dollars">$5</label>
								    </div>
								    <div class="col-md-5">
								    	<input type="number" class="form-control" id="five_dollars" min="0" value="0">
								    </div>
								</div>
			      			</div>
			      			<div class="col-md-6">
			      				<div class="form-group">
			      					<div class="col-md-1 label-closing">
								    	<label for="one_cent">¢1</label>
								    </div>
								    <div class="col-md-5">
								    	<input type="number" class="form-control" id="one_cent" min="0" value="0">
								    </div>
								</div>
			      			</div>
		      			</div>
		      			<div class="col-md-12 element-margin-bottom-closing">
			      			<div class="col-md-6">
			      				<div class="form-group">
			      					<div class="col-md-1 label-closing">
								    	<label for="one_dollar">$1</label>
								    </div>
								    <div class="col-md-5">
								    	<input type="number" class="form-control" id="one_dollar" min="0" value="0">
								    </div>
								</div>
			      			</div>
			      			<div class="col-md-6 popup">
			      				<div class="form-group">
			      					<div class="col-md-1 label-closing">
								    	<label for="total_deposit_cash">Total</label>
								    </div>
								    <div class="col-md-5">
								    	<input type="text" class="form-control" id="total_deposit_cash" value="0" readonly>
								    	<span class="popuptext" id="messageAmount">El monto total a depositar es distinto al monto en efectivo!</span>
								    </div>
								</div>
							</div>
		      			</div>
					</form>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">
							<table id="table_bills" class="table" hidden>
								<caption class="text-center"><h2>Facturas</h2></caption>
								<thead>
									<th>N°</th>
									<th>Factura</th>
									<th>Modo de pago</th>
									<th>Observaci&oacute;n</th>
									<th>Fecha</th>
									<th>Monto</th>
								</thead>
								<tbody id="bills_data"></tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">
							<table id="table_receipts" class="table" hidden>
								<caption class="text-center"><h2>Recibos</h2></caption>
								<thead>
									<th>N°</th>
									<th>Recibo</th>
									<th>Factura</th>
									<th>Modo de pago</th>
									<th>Observaci&oacute;n</th>
									<th>Fecha</th>
									<th>Monto</th>
								</thead>
								<tbody id="receipts_data"></tbody>
							</table>
						</div>
					</div>
				</div>
	      	</div>
	      	<div class="modal-footer">
	      		<button type="button" id="generateClosingConfirmation" class="btn btn-primary">Generar cierre</button>
	      		<button type="button" id="resetValue" class="btn btn-warning">Reiniciar valores</button>
	        	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	      	</div>
	    </div>
	</div>
</div>
<div id="modalGenerateClosing" class="modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        	<h4 class="modal-title">Mensaje de Advertencia</h4>
	      	</div>
	      	<div class="modal-body">
	        	<p>¿Est&aacute; seguro que desea generar el cierre?</p>
	      	</div>
	      	<div class="modal-footer">
	      		<button type="button" id="generateClosingC" class="btn btn-primary">Aceptar</button>
	        	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	      	</div>
	    </div>
	</div>
</div>
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
	      		<a id="btnMessage" href="<?php echo Router::url(array('controller'=>'bills','action' => 'index')); ?>" type="button" class="btn btn-primary">Aceptar</a>
	      		<a id="btnMessageSuccess" href="<?php echo Router::url(array('controller'=>'closings','action' => 'index')); ?>" type="button" class="btn btn-primary" hidden>Aceptar</a>
	      		<a id="closing_id" href="<?php echo Router::url(array('controller'=>'closings','action' => 'closeDay')); ?>" hidden target="_blank">Descargar Cierre</a>
	      	</div>
	    </div>
	</div>
</div>
<div id="modalWait" class="modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="col-md-12 text-center">
	    <h2>Por favor espere mientras procesamos la solicitud</h2>
	    <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
	</div>
</div>
<?php 
	/*Library Datepicker Bootstrap*/
	echo $this->Html->script('bootstrap-datepicker.min');
	echo $this->Html->script('bootstrap-datepicker.es.min');

	echo $this->Html->script('filter-bill');
?>