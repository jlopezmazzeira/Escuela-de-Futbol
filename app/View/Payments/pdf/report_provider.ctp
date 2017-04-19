<div class="landscape-bill">
	<div class="row">
		<div class="col-md-12">
			<h1 class="text-center">Reporte de Pagos de Proveedores</h1>
			<?php if ($date_from != "" && $date_until != ""): ?>
					<h4 class="text-center">Desde: <?php echo $date_from; ?> - Hasta: <?php echo $date_until; ?></h4>	
			<?php endif ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Fecha</th>
						<th>RUC</th>
						<th>Factura</th>
						<th>Proveedor</th>
						<th>Descripci&oacute;n</th>
						<th>Categor&iacute;a</th>
						<th>Valor_<?php echo $iva; ?>%</th>
						<th>Valor_0%</th>
						<th><?php echo $iva; ?>%:IVA</th>
						<th>1%:IRF</th>
						<th>2%:IRF</th>
						<th>8%:IRF</th>
						<th>10%:IRF</th>
						<th>30%:IVA</th>
						<th>70%:IVA</th>
						<th>100%:IVA</th>
						<th>Total a pagar</th>
						<th>N° Retenci&oacute;n</th>
						<th>Estatus</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						foreach ($data_report as $data):
							$sub_total = $data['Payment']['value_14'] + $data['Payment']['amount_iva'];
							$total = $sub_total - ($data['Payment']['retention_source_1'] + $data['Payment']['retention_source_2']
								+ $data['Payment']['retention_source_8'] + $data['Payment']['retention_source_10'] + $data['Payment']['retention_iva_30'] + $data['Payment']['retention_iva_70'] + $data['Payment']['retention_iva_100']);

							$status;
							if ($data['Payment']['status'] == 1) {
								$status = "Pagada";
							} elseif ($data['Payment']['status'] == 2) {
								$status = "Anulada";
							} elseif ($data['Payment']['status'] == 3) {
								$status = "Pendiente";
							}
					?>
							<tr>
								<td><?php echo $data['Payment']['date_payment']; ?></td>
								<td><?php echo $data['p']['document_number']; ?></td>
								<td><?php echo $data['Payment']['bill_code']; ?></td>
								<td><?php echo $data['p']['name']; ?></td>
								<td><?php echo $data['Payment']['description']; ?></td>
								<td><?php echo $data['tp']['name']; ?></td>
								<td class="text-center"><?php echo $data['Payment']['value_14']; ?></td>
								<td class="text-center"><?php echo $data['Payment']['value_0']; ?></td>
								<td class="text-center"><?php echo $data['Payment']['amount_iva']; ?></td>
								<td class="text-center"><?php echo $data['Payment']['retention_source_1']; ?></td>
								<td class="text-center"><?php echo $data['Payment']['retention_source_2']; ?></td>
								<td class="text-center"><?php echo $data['Payment']['retention_source_8']; ?></td>
								<td class="text-center"><?php echo $data['Payment']['retention_source_10']; ?></td>
								<td class="text-center"><?php echo $data['Payment']['retention_iva_30']; ?></td>
								<td class="text-center"><?php echo $data['Payment']['retention_iva_70']; ?></td>
								<td class="text-center"><?php echo $data['Payment']['retention_iva_100']; ?></td>
								<td class="text-center"><?php echo $total; ?></td>
								<td><?php echo $data['Payment']['retention_number']; ?></td>
								<td><?php echo $status; ?></td>
							</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>