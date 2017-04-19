<div class="container">
	<div class="row element-margin-top">
		<div class="col-md-6">
			<h4>Cliente</h4>
			<ul>
				<li>Nombre: <?php echo $data_bill['p']['name']; ?></li>
				<li>CI/RUC: <?php echo $data_bill['p']['document_number']; ?></li>
				<li>Direcci&oacute;n: <?php echo $data_bill['p']['address']; ?></li>
				<li>Tel&eacute;fono: <?php echo $data_bill['p']['phone']; ?></li>
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
						<th class="text-center">Estudiante</th>
						<th class="text-center">Item</th>
						<th class="text-center">Descripci&oacute;n</th>
						<th class="text-center">Mes</th>
						<th class="text-center">Sub Total</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($details_bill as $detail): ?>
							<tr>
								<td><?php echo $student['Student']['name']." ".$student['Student']['lastname']; ?> </td>
								<td><?php echo $detail['DetailsBill']['product']; ?></td>
								<td><?php echo $detail['DetailsBill']['description']; ?></td>
								<td class="text-center"><?php echo $months[$detail['DetailsBill']['month']]; ?></td>
								<td class="text-center"><?php echo $detail['DetailsBill']['cost_item']; ?></td>
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
				<?php if($exoneration != 0){ ?>
					<li><strong>Exoneraci&oacute;n:</strong> <?php echo $exoneration; ?></li>
				<?php } ?>
				<?php if($scholarship != 0){ ?>
					<li><strong>Descuento:</strong> <?php echo $scholarship; ?></li>
				<?php } ?>
				<li><strong>IVA <?php echo $iva; ?>%:</strong> <?php echo $total_iva; ?></li>
				<li><strong>Total:</strong> <?php echo $total_payment; ?></li>
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
