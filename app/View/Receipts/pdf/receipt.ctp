<div class="container">
	<div class="row">
		<div class="col-md-6">
			<h4>Cliente</h4>
			<ul>
				<li>Nombre: <?php echo $receipt['p']['name']; ?></li>
				<li>CI/RUC: <?php echo $receipt['p']['document_number']; ?></li>
				<li>Direcci&oacute;n: <?php echo $receipt['p']['address']; ?></li>
				<li>Tel&eacute;fono: <?php echo $receipt['p']['phone']; ?></li>
				<li>Fecha: <?php echo $receipt['Receipt']['date_payment']; ?></li>
			</ul>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table class="table table-bordered">
				<thead>
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
	<div class="row">
		<div class="col-md-6">
			<table class="table table-bordered">
				<caption class="text-center"><h2>Formas de pago utilizadas</h2></caption>
				<thead>
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
	<br> <br> <br> <br> <br>
	<div class="row element-margin-top-firms">
		<div class="col-md-6">
			<p>Firma Autorizada: ___________________</p>
		</div>
		<div class="col-md-6">
			<p>Recibí  Conforme: _____________________</p>
		</div>
	</div>
</div>
