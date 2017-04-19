<div class="container">
	<div class="row">
		<div class="col-md-12 text-center title-section">
			<h1>Detalle Cierre</h1>
			<span>
				<a href="<?php echo Router::url(array('controller'=>'closings','action' => 'index')); ?>" title="Regresar">
					<i class="fa fa-reply action" aria-hidden="true"></i>
				</a>
			</span>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="wrapper">
			  	<div class="block">
			    	<div class="number">$<?php echo $total_check; ?></div>
			    	<div class="string">Cheque</div>
			  	</div>
			  	<div class="block">
			    	<div class="number">$<?php echo $total_deposit; ?></div>
			    	<div class="string">Deposito</div>
			 	</div>
			  	<div class="block">
			    	<div class="number">$<?php echo $total_cash; ?></div>
			    	<div class="string">Efectivo</div>
			  	</div>
			  	<div class="block">
			    	<div class="number">$<?php echo $total_tdc; ?></div>
			    	<div class="string">Tarjeta de credito</div>
			  	</div>
			  	<div class="block">
			    	<div class="number">$<?php echo $total_transfer; ?></div>
			    	<div class="string">Transferencia</div>
			  	</div>
			  	<div class="block">
			    	<div class="number">$<?php echo $closing['Closing']['total_closing']; ?></div>
			    	<div class="string">Total</div>
			  	</div>
			</div>
		</div>
	</div>
	<?php if ($size_details_closings > 0): ?>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
		  			<table class="table order-table">
		  				<caption class="text-center"><h2>Facturas</h2></caption>
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
		</div>
	<?php endif ?>
	<?php if ($size_receipts_closings > 0): ?>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
		  			<table class="table order-table">
		  				<caption class="text-center"><h2>Recibos</h2></caption>
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
		</div>
	<?php endif ?>
	<?php if ($size_coins_closings): ?>
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<div class="table-responsive">
			  			<table class="table order-table">
			  				<caption class="text-center"><h2>Monedas Utilizadas</h2></caption>
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
			</div>
	<?php endif ?>
</div>