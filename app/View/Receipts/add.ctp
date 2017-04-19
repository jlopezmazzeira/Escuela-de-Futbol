<?php echo $this->Html->css('bootstrap-select.min'); ?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div>
				<form class="well form-horizontal" action="<?php echo Router::url(array('controller'=>'receipts','action' => 'addPayment')); ?>" method="post" id="receipt_form">
				    <fieldset>
				    	<legend class="text-center">Nuevo abono</legend>
					    <div class="form-group">
					      	<label class="col-md-4 control-label">Factura</label>
					      	<div class="col-md-4 inputGroupContainer">
					      		<div class="input-group">
					      			<span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
					      			<input type="text" id="bill_code" name="bill_code" class="form-control" readonly value="<?php echo $bill['Bill']['bill_code']; ?>">
					        	</div>
					      	</div>
					    </div>
					    <div class="form-group">
					      	<label class="col-md-4 control-label">Monto Total</label>
					      	<div class="col-md-4 inputGroupContainer">
					      		<div class="input-group">
					      			<span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
					      			<input type="text" id="total_payment" name="total_payment" class="form-control" readonly value="<?php echo $bill['Bill']['total']; ?>">
					        	</div>
					      	</div>
					    </div>
					    <div class="form-group">
					      	<label class="col-md-4 control-label">Monto Abonado</label>
					      	<div class="col-md-4 inputGroupContainer">
					      		<div class="input-group">
					      			<span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
					      			<input type="text" class="form-control" readonly value="<?php echo $payment; ?>">
					        	</div>
					      	</div>
					    </div>
					    <div class="form-group">
					      	<label class="col-md-4 control-label">Monto Pendiente</label>
					      	<div class="col-md-4 inputGroupContainer">
					      		<div class="input-group">
					      			<span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
					      			<input type="text" id="total_pending" name="total_pending" class="form-control" readonly value="<?php echo $total_pending; ?>">
					        	</div>
					      	</div>
					    </div>
					    <div class="form-group">
					      	<label class="col-md-4 control-label">N° Recibo</label>
					      	<div class="col-md-4 inputGroupContainer">
					      		<div class="input-group">
					      			<span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
					      			<input type="text" id="receipt_code" name="receipt_code" class="form-control" readonly value="<?php echo $receipt_code; ?>">
					        	</div>
					      	</div>
					    </div>
					    <div class="form-group">
					      	<label class="col-md-4 control-label">Forma de pago</label>
				        	<div class="col-md-4 inputGroupContainer">
			        			<select id="mode_bill" name="mode_bill" class="selectpicker form-control" multiple data-live-search="true" data-live-search-placeholder="Buscar">
									<option value="">Seleccione</option>
									<?php foreach ($modes_bills as $mode_bill): ?>
											<option value="<?php echo $mode_bill['ModesBill']['id']; ?>"><?php echo $mode_bill['ModesBill']['name']; ?></option>
									<?php endforeach; ?>
								</select>
					      	</div>
					    </div>
				    	<div class="form-group">
				      		<label class="col-md-4 control-label"></label>
				      		<div class="col-md-4">
				      			<input type="hidden" id="bill_id" name="bill_id" value="<?php echo $bill['Bill']['id']; ?>">
				        		<input type="button" id="payment" class="btn btn-primary" value="Abonar">
				          		<a type="button" class="btn btn-warning volver" href="<?php echo Router::url(array('controller'=>'bills','action' => 'index')); ?>">Volver</a>
				      		</div>
				    	</div>
				    </fieldset>
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
	      	<div class="row element-margin-top">
		      	<div class="col-md-4 col-md-offset-4">
	      			<input id="btnConfirmation" type="button" value="Aceptar" class="btn btn-primary">
	      			<input type="button" class="btn btn-default" value="Cancelar" data-dismiss="modal">
	      		</div>
	      	</div>
	      	<div class="modal-body">
	      		<p>Coloque el ó los montos a abonar</p>
	      		<form id="payment_form" class="form-horizontal">
	      			<div id="modes_paid"></div>
	      		</form>
	      		<p>Si est&aacute; seguro que desea realizar el abono, presione "Aceptar".</p>
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
	      		<p id="message"></p>
	      	</div>
	      	<div class="modal-footer">
	      		<input type="button" value="Aceptar" class="btn btn-default" data-dismiss="modal">
	      	</div>
	    </div>
	</div>
</div>
<div id="modalPaymentSuccess" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        	<h4 class="modal-title">Mensaje</h4>
	      	</div>
	      	<div class="modal-body">
	      		<p>
	      			Se ha registrado el abono de la factura satisfactoriamente.
	      			Si desea descargar el recibo de pago presione el siguiente enlace 
	      			<a id="receipt_payment" href="<?php echo Router::url(array('controller'=>'receipts','action' => 'receipt/')); ?>" target="_blank">Descargar Recibo de pago</a>
	      		</p>
	      	</div>
	      	<div class="modal-footer">
	      		<a type="button" class="btn btn-primary" href="<?php echo Router::url(array('controller'=>'receipts','action' => 'index')); ?>">Aceptar</a>
	      	</div>
	    </div>
	</div>
</div>
<div id="modalWait" class="modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog text-center" role="document">
    	<h2>Por favor espere mientras procesamos la solicitud</h2>
    	<i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
	</div>
</div>
<?php 
    /*Library Select*/
	echo $this->Html->script('bootstrap-select.min'); 
	echo $this->Html->script('defaults-es_CL.min');

    echo $this->Html->script('bootstrapValidator');
    echo $this->Html->script('receipt-payment');
?>