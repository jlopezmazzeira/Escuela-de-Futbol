<div class="container">
	<div class="row element-margin-top">
		<div class="col-md-6">
			<h4>Cliente</h4>
			<ul>
				<li>Nombre: <?php echo $data_bill['p']['name']; ?></li>
				<li>CI/RUC: <?php echo $data_bill['p']['document_number']; ?></li>
				<li>Direcci&oacute;n: <?php echo $data_bill['p']['address']; ?></li>
				<li>Tel&eacute;fono: <?php echo $data_bill['p']['phone']; ?></li>
				<?php if ($data_bill['p']['role_id'] != 5): ?>
						<li>Email: <?php echo $data_bill['p']['email']; ?></li>
				<?php endif ?>
				<li>Fecha: <?php echo $data_bill['Bill']['date_payment']; ?></li>
				<li>Estatus: <?php if($data_bill['Bill']['status'] == 1) echo "Pagada"; elseif ($data_bill['Bill']['status'] == 2) echo "Anulada"; else echo "Pendiente"; ?> </li>
			</ul>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table class="table table-bordered">
				<thead>
					<tr>
						<?php if ($data_bill['p']['role_id'] == 5): ?>
								<th class="text-center">Estudiante</th>
						<?php endif ?>
						<th class="text-center">Item</th>
						<?php if ($data_bill['p']['role_id'] == 5): ?>
								<th class="text-center">Mes</th>
						<?php endif ?>
						<th class="text-center">Valor unitario</th>
						<th class="text-center">Cant</th>
						<th class="text-center">Sub Total</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($details_bill as $detail): ?>
							<tr>
								<?php if ($data_bill['p']['role_id'] == 5): ?>
										<td><?php echo $detail['s']['name']." ".$detail['s']['lastname']; ?></td>
								<?php endif ?>
								<td><?php echo $detail['DetailsBill']['product']; ?></td>
								<?php if ($data_bill['p']['role_id'] == 5): ?>
										<td>
											<?php if (!empty($detail['DetailsBill']['month'])): ?>
													<?php echo $months[$detail['DetailsBill']['month']]; ?>	
											<?php endif ?>
										</td>
								<?php endif ?>
								<!--<td class="text-center"><?php echo $detail['DetailsBill']['cost']; ?></td>-->
								<td class="text-center"><?php echo $detail['DetailsBill']['cost_item']; ?></td>
								<td class="text-center"><?php echo $detail['DetailsBill']['quantity']; ?></td>
								<!--<td class="text-center"><?php echo $detail['DetailsBill']['quantity'] * $detail['DetailsBill']['cost']; ?></td>-->
								<td class="text-center"><?php echo $detail['DetailsBill']['sub_total_item']; ?></td>
							</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<div class="col-md-12">
			<ul class="text-right">
				<li><strong>Sub total <?php echo $iva; ?>%:</strong> <?php echo $sub_total; ?></li>
				<?php if (!empty($scholarship_bill)): ?>
						<li>
							<strong>Descuento(<?php echo $scholarship_bill['ScholarshipsBill']['percentage']; ?>): </strong>
							<?php echo $scholarship_bill['ScholarshipsBill']['scholarship_total']; ?>
						</li>
				<?php endif ?>
				<li><strong>Sub total 0%:</strong> <?php echo $iva_zero; ?></li>
				<?php if ($data_bill['Bill']['credit'] != 0): ?>
						<li><strong>Descuento:</strong> <?php echo $data_bill['Bill']['credit']; ?></li>
				<?php endif ?>
				<?php if($exoneration != 0){ ?>
					<li><strong>Exoneraci&oacute;n:</strong> <?php echo $exoneration; ?></li>
				<?php } ?>
				<?php if($scholarship != 0){ ?>
					<li><strong>Descuento:</strong> <?php echo $scholarship; ?></li>
				<?php } ?>
				<li><strong>IVA <?php echo $iva; ?>%:</strong> <?php echo $total_iva; ?></li>
				<li><strong>Total:</strong> <?php echo $data_bill['Bill']['total']; ?></li>
			</ul>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h4 class="element-margin-bottom-closing">Observaci&oacute;n</h4>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<p>
				<?php 
					if(!empty($data_bill['Bill']['observation'])) 
						echo nl2br($data_bill['Bill']['observation'])."\n".$observation;
					else  
						echo $observation;
				?>
			</p>
		</div>
	</div>
	<?php if (!empty($modes_bill_str)): ?>
			<div class="row">
				<div class="col-md-12">
					<p>Forma(s) de pago: <?php echo $modes_bill_str; ?></p>
				</div>
			</div>
	<?php endif ?>
	<br> <br> <br> <br> <br>
	<div class="row element-margin-top-firms">
		<div class="col-md-6">
			<p>Firma Autorizada: ___________________</p>
		</div>
		<div class="col-md-6">
			<p>Recibí Conforme: _____________________</p>
		</div>
	</div>
</div>
