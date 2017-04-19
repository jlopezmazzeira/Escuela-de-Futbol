<div class="container element-padding">
	<div class="row">
		<div class="col-md-12">
			<h1 class="text-center">Reporte de Cuentas por Cobrar</h1>
			<h4 class="text-center">Desde: <?php echo $date_from; ?> - Hasta: <?php echo $date_until; ?></h4>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>N°</th>
						<th>Cliente</th>
						<th>N° Documento</th>
						<th>Tel&eacute;fono</th>
						<th>Factura</th>
						<th>Total</th>
						<th>Monto Abonado</th>
						<th>Monto Adeudado</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						foreach ($accounts_receivable as $account): 
					?>
							<tr>
								<td><?php echo $i; ?></td>
								<td><?php echo $account['p']['name']; ?></td>
								<td><?php echo $account['p']['document_number']; ?></td>
								<td><?php echo $account['p']['phone']; ?></td>
								<td><?php echo $account['Bill']['bill_code']; ?></td>
								<td><?php echo $account['Bill']['total']; ?></td>
								<td><?php echo $account['Bill']['payment']; ?></td>
								<td><?php echo $account['Bill']['total_pending']; ?></td>
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