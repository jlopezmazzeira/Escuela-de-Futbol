<?php
    echo $this->Html->css('bootstrap-select.min');
?>
<div class="container">
	<form class="form-horizontal" action="<?php echo Router::url(array('controller'=>'bills','action' => 'addClientBill')); ?>" method="POST" id="client_bill_form">
		<div class="row">
			<div class="col-md-12 text-center">
				<h1>Facturar Cliente</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-4">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4>Cliente</h4>
					</div>
					<div class="panel-body">
						<ul id="dataClient">
							<li>Nombre: <?php echo $client['People']['name']; ?></li>
							<li>N° Documento: <?php echo $client['People']['document_number']; ?></li>
							<li>Tel&eacute;fono: <?php echo $client['People']['phone']; ?></li>
							<li>Direcci&oacute;n: <?php echo $client['People']['address']; ?></li>
							<li>Email: <?php echo $client['People']['email']; ?></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-xs-4">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4>Datos Factura</h4>
					</div>
					<div class="panel-body">
						<ul id="dataBill">
							<li>
								<div class="form-group form-group-bill">
									<label class="control-label">N° Factura:</label>
									<div class="col-md-12 col-padding">
										<input type="text" id="bill_code" name="bill_code" class="form-control" value="<?php echo $bill_code; ?>" readonly>
									</div>
								</div>
							</li>
							<li>
								<div class="form-group form-group-bill">
									<label class="control-label">Fecha:</label>
									<div class="col-md-12 col-padding">
										<input type="text" id="date_payment" name="date_payment" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly>
									</div>
								</div>
							</li>
							<li>
								<div class="form-group form-group-bill">
									<label class="control-label">Estatus factura:</label>
									<div class="col-md-12 col-padding">
										<select id="status_bill" name="status_bill" class="selectpicker form-control" data-live-search="true" data-live-search-placeholder="Buscar" required>
											<option value="">Seleccione</option>
											<option value="1">Pagada</option>
											<option value="3">Pendiente</option>
										</select>
									</div>
								</div> 
							</li>
							<li>
								<div class="form-group form-group-bill">
									<label class="control-label">Aplicar descuento:</label>
									<div class="col-md-12 col-padding">
										<select id="scholarship" name="scholarship" class="selectpicker form-control" data-live-search="true" data-live-search-placeholder="Buscar">
							               	<option value="" selected>Seleccione</option>
							                <?php foreach ($scholarships as $scholarship): ?>
							                    <option value="<?php echo $scholarship['Scholarship']['id']; ?>">
							                        <?php echo $scholarship['Scholarship']['name']; ?>
							                    </option>
							                <?php endforeach; ?>
							            </select>
									</div>
								</div> 
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-xs-4">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4>Producto</h4>
					</div>
					<div class="panel-body">
						<li>
							<div class="form-group form-group-bill">
								<label class="control-label">Producto:</label>
								<div class="col-md-12 col-padding">
									<select id="product" name="product" class="selectpicker form-control" data-live-search="true" data-live-search-placeholder="Buscar">
										<option value="" selected>Seleccione</option>
										<?php foreach ($products as $product): ?>
												<option value="<?php echo $product['Product']['id']; ?>"><?php echo $product['Product']['name']; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</li>
						<li>
							<div class="form-group form-group-bill">
								<label class="control-label">Costo:</label>
								<div class="col-md-12 col-padding">
									<input type="text" id="cost" name="cost" class="form-control">
								</div>
							</div>
						</li>
						<li>
							<div class="form-group form-group-bill">
								<label class="control-label">Cantidad:</label>
								<div class="col-md-12 col-padding">
									<input type="text" id="quantity" name="quantity" class="form-control">
								</div>
							</div>
						</li>
						<li>
							<div class="form-group form-group-bill">
								<label class="control-label">Descripci&oacute;n:</label>
								<div class="col-md-12 col-padding">
									<input type="text" id="description" name="description" class="form-control">
								</div>
							</div>
						</li>
						<li>
							<label class="checkbox-inline"><input type="checkbox" id="add_iva" name="add_iva" checked>Producto con IVA</label>
						</li>
						<li>
							<input type="button" id="add" name="add" class="btn btn-primary" value="Añadir">
							<input type="button" id="reset" name="reset" class="btn btn-warning" value="Restablecer">
						</li>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead class="background-head-table">
									<tr>
										<th class="text-center"><h4>Item</h4></th>
										<th class="text-center"><h4>Descripci&oacute;n</h4></th>
										<th class="text-center"><h4>Valor Unitario</h4></th>
										<th class="text-center"><h4>Cant</h4></th>
										<th class="text-center"><h4>Sub total</h4></th>
										<th class="text-center"><h4>Acci&oacute;n</h4></th>
									</tr>
								</thead>
								<tbody id="item-bill"></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="row text-right">
					<div class="col-xs-3 col-xs-offset-7">
						<strong id="item-title-bill"></strong>
					</div>
					<div class="col-xs-2">
						<strong id="item-pay-bill"></strong>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<pre>Observaci&oacute;n</pre>
				<textarea id="observation" name="observation" class="form-control" rows="4" cols="145"></textarea>
			</div>
		</div>
		<div class="row element-margin-bottom element-padding">
			<div class="col-md-12">
				<div class="form-group">
				  	<label class="col-md-4 control-label"></label>
				  	<div class="col-md-4">
				  		<input type="hidden" id="client_id" name="client_id" value="<?php echo $client['People']['id']; ?>">
				  		<input type="hidden" id="iva" name="iva" value="<?php echo $iva; ?>">
				    	<input type="button" id="btnSubmit" class="btn btn-primary" value="Guardar">
				    	<a type="button" class="btn btn-warning volver" href="<?php echo Router::url(array('controller'=>'peoples','action' => 'clients')); ?>">Volver</a>
				  	</div>
				</div>
			</div>
		</div>
	</form>
</div>
<div id="modalBill" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        	<h4 class="modal-title">Mensaje</h4>
	      	</div>
	      	<div id="body-bill" class="modal-body">
	      		<p>
	      			Se ha registrado la factura. Si desea descargar la factura presione el siguiente enlace 
	      			<a id="bill_client" href="<?php echo Router::url(array('controller'=>'bills','action' => 'printBillClient/')); ?>" target="_blank">Descargar PDF</a>
	      		</p>
	      	</div>
	      	<div class="modal-footer">
	      		<a class="btn btn-primary" href="<?php echo Router::url(array('controller'=>'peoples','action' => 'clients')); ?>">Aceptar</a>
	      	</div>
	    </div>
	</div>
</div>
<div id="modalScholarship" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        	<h4 class="modal-title">Mensaje de Advertencia</h4>
	      	</div>
	      	<div class="modal-body">
	        	<p id="messageScholarchip"></p>
	      	</div>
	      	<div class="modal-footer">
	      		<input type="button" id="btnApplyScholarship" value="Aceptar" class="btn btn-default" data-dismiss="modal">
	      		<input type="button" id="btnCancelScholarship" value="Cancelar" class="btn btn-danger" data-dismiss="modal">
	      	</div>
	    </div>
	</div>
</div>
<div id="modalItem" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        	<h4 class="modal-title">Mensaje de Advertencia</h4>
	      	</div>
	      	<div class="modal-body">
	      		<p>¿Est&aacute; seguro que desea agregar este producto?</p>
	      	</div>
	      	<div class="modal-footer">
	      		<input type="button" id="addItem" value="Aceptar" class="btn btn-primary" data-dismiss="modal">
	      		<input type="button" value="Cancelar" class="btn btn-warning" data-dismiss="modal">
	      	</div>
	    </div>
	</div>
</div>
<div id="modalItemDelete" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        	<h4 class="modal-title">Mensaje de Advertencia</h4>
	      	</div>
	      	<div class="modal-body">
	      		<p>¿Est&aacute; seguro que desea eliminar este producto?</p>
	      	</div>
	      	<div class="modal-footer">
	      		<input type="button" id="deleteItem" value="Aceptar" class="btn btn-danger" data-dismiss="modal">
	      		<input type="button" value="Cancelar" class="btn btn-warning" data-dismiss="modal">
	      	</div>
	    </div>
	</div>
</div>
<div id="modalBillConfirmation" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        	<h4 class="modal-title">Mensaje de Advertencia</h4>
	      	</div>
	      	<div class="modal-body">
	      		<p>¿Est&aacute; seguro que desea generar la factura?</p>
	      	</div>
	      	<div class="modal-footer">
	      		<input id="btnConfirmation" type="button" value="Aceptar" class="btn btn-primary" data-dismiss="modal">
	      		<input type="button" class="btn btn-default" value="Cancelar" data-dismiss="modal">
	      	</div>
	    </div>
	</div>
</div>
<div id="modalModeBill" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        	<h4 class="modal-title">Formas de pago</h4>
	      	</div>
	      	<div class="row element-margin-top">
		      	<div class="col-md-4 col-md-offset-4">
	      			<input type="button" id="payment" value="Aceptar" class="btn btn-primary" data-dismiss="modal">
	      			<input type="button" id="cancelPayment" value="Cancelar" class="btn btn-warning" data-dismiss="modal">
	      		</div>
	      	</div>
	      	<div class="modal-body">
	      		<p>Seleccione las formas de pago</p>
	      		<form id="mode_bill_form" class="form-horizontal">
	      			<div class="form-group form-group-bill">
						<label class="control-label">Forma de pago:</label>
						<div class="col-md-12 col-padding">
							<select id="mode_bill" name="mode_bill" class="selectpicker form-control" multiple data-live-search="true" data-live-search-placeholder="Buscar">
								<option value="">Seleccione</option>
								<?php foreach ($modes_bills as $mode_bill): ?>
										<option value="<?php echo $mode_bill['ModesBill']['id']; ?>"><?php echo $mode_bill['ModesBill']['name']; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
	      		</form>
	      	</div>
	    </div>
	</div>
</div>
<div id="modalPaymentConfirmation" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        	<h4 class="modal-title">Mensaje de Confirmaci&oacute;n</h4>
	      	</div>
	      	<div class="modal-body">
	      		<p>Coloque el ó los montos a abonar</p>
	      		<form id="payment_form" class="form-horizontal">
	      			<div id="modes_paid"></div>
	      		</form>
	      		<p>Si est&aacute; seguro que desea realizar el pago, presione "Aceptar".</p>
	      	</div>
	      	<div class="modal-footer">
	      		<input type="button" class="btn btn-primary" id="btnConfirmationPaid" value="Aceptar">
	      		<input type="button" class="btn btn-default" id="btnCancelModeBill" value="Cancelar" data-dismiss="modal">
	      	</div>
	    </div>
	</div>
</div>
<div id="modalItemFail" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        	<h4 class="modal-title">Mensaje de Advertencia</h4>
	      	</div>
	      	<div class="modal-body">
	      		<p id="message"></p>
	      	</div>
	      	<div class="modal-footer">
	      		<input type="button" value="Aceptar" class="btn btn-primary" data-dismiss="modal">
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
	/*Library Select*/
	echo $this->Html->script('bootstrap-select.min'); 
	echo $this->Html->script('defaults-es_CL.min'); 

	echo $this->Html->script('bootstrapValidator');
	echo $this->Html->script('client-bill'); 
?>
