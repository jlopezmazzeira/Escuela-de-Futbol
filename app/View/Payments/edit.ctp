<?php
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
			<form class="form-horizontal" action="<?php echo Router::url(array('controller'=>'payments','action' => 'edit/'.$payment['Payment']['id'])); ?>" method="POST" id="edit_provider_bill_form">
				<div class="row">
					<div class="col-xs-5">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h4>Proveedor</h4>
							</div>
							<div class="panel-body">
								<ul id="dataProvider">
									<li>Nombre: <?php echo $payment['p']['name']; ?></li>
									<li>Documento: <?php echo $payment['p']['document_type'].":".$payment['p']['document_number']; ?></li>
									<li>Direcci&oacute;n: <?php echo $payment['p']['address']; ?></li>
									<li>Tel&eacute;fono: <?php echo $payment['p']['phone']; ?></li>
									<li>Email: <?php echo $payment['p']['email']; ?></li>
									<li>Tipo: <?php echo $payment['t']['name']." - ".$payment['ta']['name']; ?></li>
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
												<input type="text" id="bill_code" name="bill_code" class="form-control" value="<?php echo $payment['Payment']['bill_code']; ?>" readonly>
											</div>
										</div> 
									</li>
									<li>
										<div class="form-group form-group-bill">
											<label class="control-label">N° Retenci&oacute;n:</label>
											<div class="col-md-12 col-padding">
												<input type="text" id="retention_number" name="retention_number" class="form-control" value="<?php echo $payment['Payment']['retention_number']; ?>" readonly>
											</div>
										</div> 
									</li>
									<li>
										<div class="form-group form-group-bill">
											<label class="control-label">Fecha:</label>
											<div class="col-md-12 col-padding">
												<input type="text" id="date_payment" name="date_payment" class="form-control" value="<?php echo $payment['Payment']['date_payment']; ?>" readonly>
											</div>
										</div>
									</li>
									<li>
										<div class="form-group form-group-bill">
											<label class="control-label">Forma de pago:</label>
											<div class="col-md-12 col-padding">
												<input type="text" id="mode_bill" name="mode_bill" class="form-control" value="<?php echo $payment['mb']['name']; ?>" readonly>
											</div>
										</div>
									</li>
									<li>
										<div class="form-group form-group-bill">
											<label class="control-label">Tipo:</label>
											<div class="col-md-12 col-padding">
												<input type="text" name="" id="" class="form-control" value="<?php echo $payment['tp']['name']; ?>" readonly>
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
													<option value="2">Anulada</option>
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
													<span class="input-group-addon">Valor <?php echo $payment['Payment']['iva']; ?>%</span>
													<input type="text" id="value_14" name="value_14" class="input-sm form-control" value="<?php echo $payment['Payment']['value_14']; ?>" readonly>
												</div>
											</div>
										</td>
										<td>
											<div class="form-group form-group-bill">
												<div class="input-group">
													<span class="input-group-addon">Valor 0%:</span>
													<input type="text" id="value_0" name="value_0" class="input-sm form-control"  value="<?php echo $payment['Payment']['value_0']; ?>" readonly>
												</div>
											</div>
										</td>
										<td>
											<div class="input-group">
												<span class="input-group-addon">Sub-total:</span>
												<input type="text" id="sub_total" name="sub_total" class="input-sm form-control"  value="<?php echo $sub_total; ?>" readonly>
											</div>
										</td>
										<td>
											<div class="input-group">
												<span class="input-group-addon">IVA:</span>
												<input type="text" id="iva_c" name="iva_c" class="input-sm form-control"  value="<?php echo $sub_total_iva; ?>" readonly>
											</div>
										</td>
										<td>
											<div class="input-group">
												<span id="irf" class="input-group-addon">IRF_ <?php echo $payment['Payment']['percentage_retention_source'] ?>%</span>
												<input type="text" id="total_retention" name="total_retention" class="input-sm form-control"  value="<?php echo $total_retention; ?>" readonly>
											</div>
										</td>
										<td>
											<div class="input-group">
												<span id="iri" class="input-group-addon">IRI_ <?php echo $payment['Payment']['percentage_retention_iva'] ?>%</span>
												<input type="text" id="total_iva" name="total_iva" class="input-sm form-control"  value="<?php echo $total_iva; ?>" readonly>
											</div>
										</td>
									</tr>
									<tr>
										<td colspan="3">
											<div class="input-group">
												<span class="input-group-addon">Descripci&oacute;n:</span>
												<textarea id="description" name="description" class="input-sm form-control" rows="3" cols="145"><?php echo $payment['Payment']['description']; ?></textarea>
											</div>
										</td>
										<td>
											<div class="form-group form-group-bill">
												<div class="input-group">
													<span class="input-group-addon">Total a pagar:</span>
													<input type="text" id="total_payment" name="total_payment" class="input-sm form-control"  value="<?php echo $total; ?>" readonly>
												</div>
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
						<textarea id="observation" name="observation" class="form-control" rows="4" cols="145"><?php echo $payment['Payment']['observation']; ?></textarea>
					</div>
				</div>
				<div class="row element-margin-bottom element-padding">
					<div class="col-md-12">
						<div class="form-group">
						  	<label class="col-md-4 control-label"></label>
						  	<div class="col-md-4">
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
<?php
	/*Library Select*/
	echo $this->Html->script('bootstrap-select.min'); 
	echo $this->Html->script('defaults-es_CL.min');

	echo $this->Html->script('bootstrapValidator');
	echo $this->Html->script('provider-bill');
?>