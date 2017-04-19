<div class="container">
	<div class="row">
		<div class="col-md-12 text-center title-section">
			<h1>Detalle Pago proveedor</h1>
			<span>
				<a href="<?php echo Router::url(array('controller'=>'payments','action' => 'index')); ?>" title="Regresar">
					<i class="fa fa-reply action" aria-hidden="true"></i>
				</a>
			</span>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="table-responsive">
				<table class="table table-condensed table-bordered table-payment">
					<thead>
						<tr>
							<th>Fecha</th>
							<th>RUC</th>
							<th>Factura</th>
							<th>Proveedor</th>
							<th>Descripci&oacute;n</th>
							<th>Categor&iacute;a</th>
							<th>Valor_<?php echo $payment['Payment']['iva']; ?>%</th>
							<th>Valor_0%</th>
							<th><?php echo $payment['Payment']['iva']; ?>%:IVA</th>
							<th>1%:IRF</th>
							<th>2%:IRF</th>
							<th>8%:IRF</th>
							<th>10%:IRF</th>
							<th>30%:IVA</th>
							<th>70%:IVA</th>
							<th>100%:IVA</th>
							<th>N° Retenci&oacute;n</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo $payment['Payment']['date_payment']; ?></td>
							<td><?php echo $payment['p']['document_number']; ?></td>
							<td><?php echo $payment['Payment']['bill_code']; ?></td>
							<td><?php echo $payment['p']['name']; ?></td>
							<td><?php echo $payment['Payment']['description']; ?></td>
							<td><?php echo $payment['tp']['name']; ?></td>
							<td class="text-center"><?php echo $payment['Payment']['value_14']; ?></td>
							<td class="text-center"><?php echo $payment['Payment']['value_0']; ?></td>
							<td class="text-center"><?php echo $payment['Payment']['amount_iva']; ?></td>
							<td class="text-center"><?php echo $payment['Payment']['retention_source_1']; ?></td>
							<td class="text-center"><?php echo $payment['Payment']['retention_source_2']; ?></td>
							<td class="text-center"><?php echo $payment['Payment']['retention_source_8']; ?></td>
							<td class="text-center"><?php echo $payment['Payment']['retention_source_10']; ?></td>
							<td class="text-center"><?php echo $payment['Payment']['retention_iva_30']; ?></td>
							<td class="text-center"><?php echo $payment['Payment']['retention_iva_70']; ?></td>
							<td class="text-center"><?php echo $payment['Payment']['retention_iva_100']; ?></td>
							<td class="text-center"><?php echo $payment['Payment']['retention_number']; ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>