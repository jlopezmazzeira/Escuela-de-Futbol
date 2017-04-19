<div class="landscape-bill">
	<div class="row">
		<div class="col-md-12">
			<h1 class="text-center">Cierre</h1>
			<h4 class="text-center">Desde: <?php echo $closing['Closing']['date_closing_start']; ?> - Hasta: <?php echo $closing['Closing']['date_closing_end']; ?></h4>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Cheque</th>
						<th>Deposito</th>
						<th>Efectivo</th>
						<th>Tarjeta de credito</th>
						<th>Transferencia</th>
						<th>Total</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>$<?php echo $total_check; ?></td>
						<td>$<?php echo $total_deposit; ?></td>
						<td>$<?php echo $total_cash; ?></td>
						<td>$<?php echo $total_tdc; ?></td>
						<td>$<?php echo $total_transfer; ?></td>
						<td>$<?php echo $closing['Closing']['total_closing']; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<?php if ($size_details_closings > 0): ?>
		<div class="row">
			<div class="col-md-12">
				<h1 class="text-center element-margin-title">Facturas</h1>
	  			<table class="table table-bordered">
	  				<thead>
	  					<tr>
							<th>N°</th>
							<th>Factura</th>
							<th>Cliente</th>
							<th>Fecha</th>
							<th>Total</th>
							<th>Forma de pago</th>
							<th>Observaci&oacute;n</th>
							<th>Estatus</th>
						</tr>
	  				</thead>
	  				<tbody>
					<?php 
						$i = 1;
						foreach ($details_closings as $detail_closing):
							$status = "";
							if ($detail_closing['b']['status'] == 1) {
								$status = "Pagada";
							} else if($detail_closing['b']['status'] == 2){
								$status = "Anulada";
							}
					?>
							<tr>
								<td><?php echo $i; ?></td>
								<td><?php echo $detail_closing['b']['bill_code']; ?></td>
								<td><?php echo $detail_closing['p']['name']; ?></td>
								<td><?php echo $detail_closing['b']['date_payment']; ?></td>
								<td><?php echo "$".$detail_closing['ip']['subscribed_amount']; ?></td>
								<td><?php echo $detail_closing['mb']['name']; ?></td>
								<td><?php echo $detail_closing['ip']['observation']; ?></td>
								<td><?php echo $status; ?></td>
							</tr>
					<?php 		
							$i++;
						endforeach;
					?>
					</tbody>
				</table>
			</div>
		</div>
	<?php endif ?>
	<?php if ($size_receipts_closings > 0): ?>
		<div class="row">
			<div class="col-md-12">
				<h1 class="text-center element-margin-title">Recibos</h1>
	  			<table class="table table-bordered">
	  				<thead>
	  					<tr>
							<th>N°</th>
							<th>Recibo</th>
							<th>Factura</th>
							<th>Cliente</th>
							<th>Fecha</th>
							<th>Total</th>
							<th>Forma de pago</th>
							<th>Observaci&oacute;n</th>
						</tr>
	  				</thead>
	  				<tbody>
					<?php 
						$i = 1;
						foreach ($receipts_closings as $receipt_closing):
					?>
							<tr>
								<td><?php echo $i; ?></td>
								<td><?php echo $receipt_closing['r']['receipt_code']; ?></td>
								<td><?php echo $receipt_closing['b']['bill_code']; ?></td>
								<td><?php echo $receipt_closing['p']['name']; ?></td>
								<td><?php echo $receipt_closing['r']['date_payment']; ?></td>
								<td><?php echo "$".$receipt_closing['ip']['subscribed_amount']; ?></td>
								<td><?php echo $receipt_closing['mb']['name']; ?></td>
								<td><?php echo $receipt_closing['ip']['observation']; ?></td>
							</tr>
					<?php 		
							$i++;
						endforeach;
					?>
					</tbody>
				</table>
			</div>
		</div>
	<?php endif ?>
	<?php if ($size_coins_closings): ?>
			<div class="row">
				<div class="col-md-6">
					<h1 class="text-center element-margin-title">Monedas usadas</h1>
		  			<table class="table table-bordered">
		  				<thead>
		  					<tr>
								<th>N°</th>
								<th>Nominaci&oacute;n</th>
								<th>Cantidad</th>
								<th>Sub-total</th>
							</tr>
		  				</thead>
		  				<tbody>
						<?php 
							$i = 1;
							$total = 0;
							foreach ($coins_closing as $coins_closing):
								$sub_total = $coins_closing['CoinsClosing']['nomination'] * $coins_closing['CoinsClosing']['quantity'];
								$sub_total = round($sub_total,2); 
								$total += $sub_total;
						?>
								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo "$".$coins_closing['CoinsClosing']['nomination']; ?></td>
									<td><?php echo $coins_closing['CoinsClosing']['quantity']; ?></td>
									<td><?php echo $sub_total; ?></td>
								</tr>
						<?php 		
								$i++;
							endforeach;
						?>
							<tr>
								<td colspan="3"><strong>Total</strong></td>
								<td><?php echo $total; ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
	<?php endif ?>
</div>