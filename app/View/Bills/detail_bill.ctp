<div class="container">
	<div class="row">
		<div class="col-md-12 text-center title-section">
			<h1>Detalle Factura</h1>
			<span>
				<a href="<?php echo Router::url(array('controller'=>'bills','action' => 'index')); ?>" title="Regresar">
					<i class="fa fa-reply action" aria-hidden="true"></i>
				</a>
			</span>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-5">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4>Cliente</h4>
					</div>
					<div class="panel-body">
						<ul id="dataResponsable">
							<li>Nombre: <?php echo $bill['p']['name']; ?></li>
							<li>Tipo Documento: <?php echo $bill['p']['document_type']; ?></li>
							<li>N° Documento: <?php echo $bill['p']['document_number']; ?></li>
							<li>Tel&eacute;fono: <?php echo $bill['p']['phone']; ?></li>
							<?php if ($bill['p']['role_id'] != 5): ?>
									<li>Email: <?php echo $bill['p']['email']; ?></li>
							<?php endif ?>
							<li>Direcci&oacute;n: <?php echo $bill['p']['address']; ?></li>
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
							<li>Factura: <?php echo $bill['Bill']['bill_code']; ?></li>
							<li>Fecha: <?php echo $bill['Bill']['date_payment']; ?></li>
							<li>Estatus: <?php if($bill['Bill']['status'] == 1) echo "Pagada"; elseif ($bill['Bill']['status'] == 2) echo "Anulada"; else echo "Pendiente"; ?> </li>
							<?php if ($bill['p']['role_id'] == 5): ?>
								<li>Forma(s) de pago: <?php echo $modes_bill_str; ?></li>
							<?php endif ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<table class="table table-bordered">
					<thead class="background-head-table">
						<tr>
							<?php if ($bill['p']['role_id'] == 5): ?>
										<td class="text-center"><h4>Estudiante</h4></td>
							<?php endif ?>
							<th class="text-center"><h4>Item</h4></th>
							<?php if ($bill['p']['role_id'] == 5): ?>
								<th class="text-center"><h4>Mes</h4></th>
							<?php endif ?>
							<th class="text-center"><h4>Precio unitario</h4></th>
							<th class="text-center"><h4>Cant</h4></th>
							<th class="text-center"><h4>Sub Total</h4></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($details_bill as $detail): 
								//$sub_total_p = $detail['DetailsBill']['cost'] * $detail['DetailsBill']['quantity'];
								//$sub_total_p = round($sub_total_p,2);
						?>
								<tr>
									<?php if ($bill['p']['role_id'] == 5): ?>
											<td><?php echo $detail['s']['name']." ".$detail['s']['lastname']; ?></td>
									<?php endif ?>
									<td><?php echo $detail['DetailsBill']['product']; ?></td>
									<?php if ($bill['p']['role_id'] == 5): ?>
											<td>
												<?php if (!empty($detail['DetailsBill']['month'])): ?>
														<?php echo $months[$detail['DetailsBill']['month']]; ?>	
												<?php endif ?>
											</td>
									<?php endif ?>
									<!--<td><?php echo $detail['DetailsBill']['cost']; ?></td>-->
									<td><?php echo $detail['DetailsBill']['cost_item']; ?></td>
									<td><?php echo $detail['DetailsBill']['quantity']; ?></td>
									<!--<td><?php echo $sub_total_p; ?></td>-->
									<td><?php echo $detail['DetailsBill']['sub_total_item']; ?></td>
								</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="row text-right">
					<div class="col-xs-3 col-xs-offset-7">
						<strong id="item-title-bill">
							<li>Sub Total <?php echo $iva; ?>%:</li>
							<?php if (!empty($scholarship_bill)): ?>
									<li>Descuento(<?php echo $scholarship_bill['ScholarshipsBill']['percentage']; ?>):</li>
							<?php endif ?>
							<li>Sub Total 0%:</li>
							<?php if ($bill['Bill']['credit'] != 0): ?>
									<li>Descuento:</li>
							<?php endif ?>
							<?php if ($exoneration != 0): ?>
									<li>Exoneraci&oacute;n:</li>
							<?php endif ?>
							<?php if ($scholarship != 0): ?>
									<li>Descuento:</li>
							<?php endif ?>
							<li>IVA <?php echo $iva; ?>%:</li>
							<li>Total</li>
						</strong>
					</div>
					<div class="col-xs-2">
						<strong id="item-pay-bill">
							<li><?php echo $sub_total; ?></li>
							<?php if (!empty($scholarship_bill)): ?>
									<li><?php echo $scholarship_bill['ScholarshipsBill']['scholarship_total']; ?></li>
							<?php endif ?>
							<li><?php echo $total_iva_zero; ?></li>
							<?php if ($bill['Bill']['credit'] != 0): ?>
									<li><?php echo $bill['Bill']['credit']; ?></li>
							<?php endif ?>
							<?php if ($exoneration != 0): ?>
									<li><?php echo $exoneration; ?></li>
							<?php endif ?>	
							<?php if ($scholarship != 0): ?>
									<li><?php echo $scholarship; ?></li>
							<?php endif ?>
							<li><?php echo $total_iva; ?></li>
							<li><?php echo $bill['Bill']['total']; ?></li>
						</strong>
					</div>
				</div>
			</div>
		</div>
		<div class="row element-margin-bottom">
			<div class="col-md-12">
				<pre>Observaci&oacute;n</pre>
				<textarea id="observation" name="observation" class="form-control" rows="4" cols="145">
					<?php 
							if(!empty($bill['Bill']['observation'])) 
								echo nl2br($bill['Bill']['observation'])."\n".$observation;
							else  
								echo $observation;
					?>
				</textarea>
			</div>
		</div>
	</div>
</div>
