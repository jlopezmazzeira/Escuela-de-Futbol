<?php echo $this->Html->css('bootstrap-select.min'); ?>
<div class="container">
	<div class="row">
		<div class="col-md-12 text-center">
			<h1>Facturar Estudiante</h1>
		</div>
	</div>
</div>
<div class="container">
	<form class="form-horizontal" action="<?php echo Router::url(array('controller'=>'bills','action' => 'add')); ?>" method="post" id="bill_form">
		<div class="row">
			<div class="col-xs-4">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4>Estudiante</h4>
					</div>
					<div class="panel-body">
						<ul id="dataStudent"></ul>
					</div>
				</div>
			</div>
			<div class="col-xs-4">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4>Cliente</h4>
					</div>
					<div class="panel-body">
						<ul id="dataResponsable"></ul>
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
		</div>
		<div class="row">
			<div class="col-md-12">
				<table class="table table-bordered">
					<thead class="background-head-table">
						<tr>
							<th class="text-center"><h4>Item</h4></th>
							<th class="text-center"><h4>Mes</h4></th>
							<th class="text-center"><h4>Sub Total</h4></th>
							<th class="text-center"><h4>Acci&oacute;n</h4></th>
						</tr>
					</thead>
					<tbody id="item-bill"></tbody>
				</table>
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
				    	<input type="button" id="btnSubmit" class="btn btn-primary" value="Guardar">
				    	<input type="button" id="btnCredit" class="btn btn-success" value="Abonar">
				    	<input type="hidden" id="iva" name="iva" value="<?php echo $iva; ?>">
				    	<a type="button" class="btn btn-warning volver" href="<?php echo Router::url(array('controller'=>'students','action' => 'add')); ?>">Volver</a>
				  	</div>
				</div>
			</div>
		</div>
	</form>
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
<div id="modalItemPaid" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-credit" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        	<h4 class="modal-title">Items a pagar</h4>
	      	</div>
	      	<div id="body-bill" class="modal-body">
	      		<p>Los items seleccionados se cancelaran en su totalidad si el monto a abonar es mayor al 
	      			costo del item, sino se le abonara una parte y quedara pendiente el resto.</p>
	      		<form class="form-horizontal">
	      			<div class="form-group">
					  	<label class="col-md-4 control-label">Monto a abonar</label>
					  	<div class="col-md-4 inputGroupContainer">
					        <div class="input-group">
					            <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
					            <input type="text" id="subscriber_amount" name="subscribed_amount" class="form-control" readonly>
					        </div>
					  	</div>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered">
							<thead class="background-head-table">
								<tr>
									<td>Item</td>
									<td>Mes</td>
									<td>Valor total</td>
									<td>Valor abonado</td>
									<td>Valor pendiente</td>
									<td>Seleccionar</td>
								</tr>
							</thead>
							<tbody id="item-paid"></tbody>
						</table>
					</div>
	      		</form>
	      	</div>
	      	<div class="modal-footer">
	      		<input type="button" id="btnConfirmationCredit" value="Aceptar" class="btn btn-primary">
	      		<input type="button" value="Cancelar" class="btn btn-default" data-dismiss="modal">
	      	</div>
	    </div>
	</div>
</div>
<div id="modalItemDelete" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        	<h4 class="modal-title">Mensaje de advertencia</h4>
	      	</div>
	      	<div id="body-bill" class="modal-body">
	      		<p>¿Est&aacute; seguro que desea eliminar este producto?</p>
	      	</div>
	      	<div class="modal-footer">
	      		<input type="button" id="deleteItem" value="Eliminar" class="btn btn-danger" data-dismiss="modal">
	      		<input type="button" value="Cancelar" class="btn btn-default" data-dismiss="modal">
	      	</div>
	    </div>
	</div>
</div>
<div id="modalMessage" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        	<h4 class="modal-title">Mensaje de advertencia</h4>
	      	</div>
	      	<div id="body-bill" class="modal-body">
	      		<p>El monto a abonar debe ser menor o igual al monto total.</p>
	      	</div>
	      	<div class="modal-footer">
	      		<input type="button" value="Aceptar" class="btn btn-default" data-dismiss="modal">
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
<div id="modalBill" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        	<h4 class="modal-title">Mensaje</h4>
	      	</div>
	      	<div id="body-bill" class="modal-body">
	      		<p id="messageInscription"></p>
	      	</div>
	      	<div class="modal-footer">
	      		<a id="btnViewStudent" class="btn btn-primary" href="<?php echo Router::url(array('controller'=>'students','action' => 'view/')); ?>">Aceptar</a>
	      	</div>
	    </div>
	</div>
</div>
<div id="modalBillConfirmation" class="modal fade" tabindex="-1" role="dialog">
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
<?php 
	/*Library Select*/
	echo $this->Html->script('bootstrap-select.min'); 
	echo $this->Html->script('defaults-es_CL.min');

	echo $this->Html->script('bootstrapValidator');
	echo $this->Html->script('bill'); 
?>