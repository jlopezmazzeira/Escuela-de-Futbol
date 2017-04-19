<div class="container">
	<div class="row">
		<div class="col-md-12 text-center title-section">
			<h1>Detalle Recibo de pago</h1>
			<span>
				<a href="<?php echo Router::url(array('controller'=>'receipts','action' => 'index')); ?>" title="Regresar">
					<i class="fa fa-reply action" aria-hidden="true"></i>
				</a>
			</span>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="table-responsive">
				<table class="table">
					<thead class="background-head-table">
						<tr>
							<th>N° de Recibo</th>
							<th>N° de Factura</th>
							<th>Fecha de pago</th>
							<th>Monto abonado</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo $receipt['Receipt']['receipt_code']; ?></td>
							<td><?php echo $receipt['b']['bill_code']; ?></td>
							<td><?php echo $receipt['Receipt']['date_payment']; ?></td>
							<td><?php echo $receipt[0]['total_payment']; ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="table-responsive">
				<table class="table">
					<caption class="text-center"><h2>Formas de pago utilizadas</h2></caption>
					<thead class="background-head-table">
						<tr>
							<th>Forma de pago</th>
							<th>Monto pagado</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($payments_receipts as $payment): ?>
								<tr>
									<td><?php echo $payment['mb']['name']; ?></td>
									<td><?php echo $payment['ip']['subscribed_amount']; ?></td>
								</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>