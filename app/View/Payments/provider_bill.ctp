<?php
    echo $this->Html->css('bootstrap-datepicker3.min');
    echo $this->Html->css('bootstrap-select.min');
?>
<div class="container">
	<div class="row">
		<div class="col-md-12 text-center title-section">
			<h1>Factura Proveedor</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<form class="form-horizontal" action="<?php echo Router::url(array('controller'=>'payments','action' => 'add')); ?>" method="POST" id="provider_bill_form">
				<div class="row">
					<div class="col-xs-5">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h4>Proveedor</h4>
							</div>
							<div class="panel-body">
								<ul id="dataProvider">
									<li>Nombre: <?php echo $provider['People']['name']; ?></li>
									<li>Documento: <?php echo $provider['People']['document_type'].":".$provider['People']['document_number']; ?></li>
									<li>Direcci&oacute;n: <?php echo $provider['People']['address']; ?></li>
									<li>Tel&eacute;fono: <?php echo $provider['People']['phone']; ?></li>
									<li>Email: <?php echo $provider['People']['email']; ?></li>
									<li>Tipo: <?php echo $provider['tp']['name']." - ".$provider['ta']['name']; ?></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-xs-5 col-xs-offset-2">
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
												<input type="text" id="bill_code" name="bill_code" class="form-control">
											</div>
										</div> 
									</li>
									<li>
										<div class="form-group form-group-bill">
											<label class="control-label">N° Retenci&oacute;n:</label>
											<div class="col-md-12 col-padding">
												<input type="text" id="retention_number" name="retention_number" class="form-control">
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
											<label class="control-label">Forma de pago:</label>
											<div class="col-md-12 col-padding">
												<select id="mode_bill" name="mode_bill" class="selectpicker form-control" data-live-search="true" data-live-search-placeholder="Buscar">
													<option value="">Seleccione</option>
													<?php foreach ($modes_bills as $mode_bill): ?>
															<option value="<?php echo $mode_bill['ModesBill']['id']; ?>"><?php echo $mode_bill['ModesBill']['name']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
									</li>
									<li>
										<div class="form-group form-group-bill">
											<label class="control-label">Tipo:</label>
											<div class="col-md-12 col-padding">
												<select id="type_payment" name="type_payment" class="selectpicker form-control" data-live-search="true" data-live-search-placeholder="Buscar">
													<option value="">Seleccione</option>
													<?php foreach ($types_payments as $type_payment): ?>
															<option value="<?php echo $type_payment['TypesPayment']['id']; ?>"><?php echo $type_payment['TypesPayment']['name']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
									</li>
									<li id="li-retention" <?php echo $visibility_types_retentions; ?>>
										<div class="form-group form-group-bill">
											<label class="control-label">Impuesto de Retenci&oacute;n:</label>
											<div class="col-md-12 col-padding">
												<select id="type_retention" name="type_retention" class="selectpicker form-control" data-live-search="true" data-live-search-placeholder="Buscar">
													<option value="">Seleccione</option>
													<?php foreach ($types_retentions as $type_retention): ?>
															<option value="<?php echo $type_retention['TypesRetentionsSource']['id']; ?>"><?php echo $type_retention['TypesRetentionsSource']['value']; ?></option>
													<?php endforeach; ?>
												</select>
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
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive table-condensed table-bordered">
							<table class="table">
								<tbody>
									<tr>
										<td>
											<div class="form-group form-group-bill">
												<div class="input-group">
													<span class="input-group-addon">Valor <?php echo $iva; ?>%</span>
													<input type="text" id="value_14" name="value_14" class="input-sm form-control">
												</div>
											</div>
										</td>
										<td>
											<div class="form-group form-group-bill">
												<div class="input-group">
													<span class="input-group-addon">Valor 0%:</span>
													<input type="text" id="value_0" name="value_0" class="input-sm form-control">
												</div>
											</div>
										</td>
										<td>
											<div class="input-group">
												<span class="input-group-addon">Sub-total:</span>
												<input type="text" id="sub_total" name="sub_total" class="input-sm form-control" readonly>
											</div>
										</td>
										<td>
											<div class="input-group">
												<span class="input-group-addon">IVA:</span>
												<input type="text" id="iva_c" name="iva_c" class="input-sm form-control" readonly>
											</div>
										</td>
										<td>
											<div class="input-group">
												<span id="irf" class="input-group-addon">IRF</span>
												<input type="text" id="total_retention" name="total_retention" class="input-sm form-control" readonly>
											</div>
										</td>
										<td>
											<div class="input-group">
												<span id="iri" class="input-group-addon">IRI</span>
												<input type="text" id="total_iva" name="total_iva" class="input-sm form-control" readonly>
											</div>
										</td>
									</tr>
									<tr>
										<td colspan="3">
											<div class="input-group">
												<span class="input-group-addon">Descripci&oacute;n:</span>
												<textarea id="description" name="description" class="input-sm form-control" rows="3" cols="145"></textarea>
											</div>
										</td>
										<td>
											<div class="form-group form-group-bill">
												<div class="input-group">
													<span class="input-group-addon">Total a pagar:</span>
													<input type="text" id="total_payment" name="total_payment" class="input-sm form-control" readonly>
												</div>
											</div>
										</td>
										<td colspan="2">
											<div class="input-group">
												<input type="button" id="calculate" name="calculate" class="btn btn-primary" value="Calcular">
												<input type="button" id="reset" name="reset" class="btn btn-warning" value="Restablecer">
											</div>
										</td>
									</tr>
								</tbody>	
							</table>
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
						  		<input type="hidden" id="provider_id" name="provider_id" value="<?php echo $provider['People']['id']; ?>">
						  		<input type="hidden" id="iva" name="iva" value="<?php echo $iva; ?>">
						  		<input type="hidden" id="retention_iva" name="retention_iva">
						  		<input type="hidden" id="retention_source" name="retention_source">
						    	<input type="submit" class="btn btn-primary" value="Guardar">
						    	<a type="button" class="btn btn-warning volver" href="<?php echo Router::url(array('controller'=>'peoples','action' => 'providers')); ?>">Volver</a>
						  	</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<div id="modalTypePaymentFail" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        	<h4 class="modal-title">Mensaje de Advertencia</h4>
	      	</div>
	      	<div id="body-bill" class="modal-body">
	      		<p id="item_fail"></p>
	      	</div>
	      	<div class="modal-footer">
	      		<input type="button" value="Aceptar" class="btn btn-primary" data-dismiss="modal">
	      	</div>
	    </div>
	</div>
</div>
<?php 
	/*Library Datepicker Bootstrap*/
	echo $this->Html->script('bootstrap-datepicker.min');
	echo $this->Html->script('bootstrap-datepicker.es.min');
	
	/*Library Select*/
	echo $this->Html->script('bootstrap-select.min'); 
	echo $this->Html->script('defaults-es_CL.min');

	echo $this->Html->script('bootstrapValidator');
	echo $this->Html->script('provider-bill');
?>