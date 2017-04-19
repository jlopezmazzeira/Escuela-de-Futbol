<div class="landscape-bill">
	<div class="row">
		<div class="col-md-12">
			<h1 class="text-center">Reporte de Facturas</h1>
			<?php if (!empty($bill_code_from) && !empty($bill_code_until)): ?>
					<h4 class="text-center">Rango de factura: Desde: <?php echo $bill_code_from; ?> - Hasta: <?php echo $bill_code_until; ?></h4>	
			<?php endif ?>
			<?php if (!empty($date_from) && !empty($date_until)): ?>
					<h4 class="text-center">Desde: <?php echo $date_from; ?> - Hasta: <?php echo $date_until; ?></h4>
			<?php endif ?>
		</div>
	</div>
	<br>
	<br>
	<div class="row">
		<div class="col-md-12">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th width="4%">N°</th>
						<th width="8%">Fecha</th>
						<th width="14%">Cliente</th>
						<th width="14%">Estudiante</th>
						<th width="9%">Factura</th>
						<th width="7%">Valor con tarifa 0%</th>
						<th width="7%">Valor con tarifa 14%</th>
						<th width="5%">IVA</th>
						<th width="5%">Total</th>
						<th width="5%">Abonado</th>
						<th width="10%">Formas de pago</th>
						<th width="3%">Estatus</th>
						<th width="9%">Observaci&oacute;n</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1; 
						foreach ($bills as $bill):
							$status = "";
							if ($bill['status'] == 1) {
								$status = 'Pagada';
							} elseif ($bill['status'] == 2) {
								$status = 'Anulada';
							} elseif ($bill['status'] == 3) {
								$status = 'Pendiente';
							}
					?>
							<tr>
								<td class="text-center"><?php echo $i; ?></td>
								<td class="text-center"><?php echo $bill['date_payment']; ?></td>
								<td><?php echo $bill['client']; ?></td>
								<td><?php echo $bill['student']; ?></td>
								<td><?php echo $bill['bill_code']; ?></td>
								<td class="text-center"><?php echo $bill['sub_total_0']; ?></td>
								<td class="text-center"><?php echo $bill['sub_total_14']; ?></td>
								<td class="text-center"><?php echo $bill['sub_total_iva']; ?></td>
								<td class="text-center"><?php echo $bill['total']; ?></td>
								<td class="text-center"><?php echo $bill['total_amount']; ?></td>
								<td>
									<?php foreach ($bill['modes_bills'] as $mode_bill): ?>
											<?php echo $mode_bill." "; ?>
									<?php endforeach ?>
								</td>
								<td class="text-center"><?php echo $status; ?></td>
								<td><?php echo $bill['observation']; ?></td>	
							</tr>
					<?php 
							$i++; 
						endforeach; 
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>