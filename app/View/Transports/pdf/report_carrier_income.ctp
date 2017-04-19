<div class="landscape-bill">
	<div class="row">
		<div class="col-md-12">
			<h1 class="text-center">Reporte de Recaudaci&oacute;n por Transportista</h1>
			<?php if (!empty($date_from) && !empty(!$date_until)): ?>
					<h4 class="text-center">Rango de Fechas: Desde:<?php echo $date_from; ?> - Hasta:<?php echo $date_until; ?></h4>
			<?php endif ?>
		</div>
	</div>
	<?php if (!empty($transport)): ?>
			<div class="row">
				<div class="col-md-6">
					<h4>Transportista</h4>
					<ul>
						<li>Nombre: <?php echo $transport['Transport']['name']; ?></li>
						<li>Tel&eacute;fono: <?php echo $transport['Transport']['phone']; ?></li>
						<li>M&oacute;vil: <?php echo $transport['Transport']['movil']; ?></li>
						<li>Monto Recaudado: <?php echo $paid; ?></li>
						<li>Monto Pendiente: <?php echo $pending; ?></li>
						<li>Monto Total: <?php echo $total; ?></li>
						<li>Gastos Administrativos: <?php echo $retention; ?></li>
					</ul>
				</div>
			</div>
	<?php endif ?>
	<div class="row">
		<div class="col-md-12">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>N°</th>
						<th>Nombre Completo</th>
			          	<th>Correo Electr&oacute;nico</th>
			          	<th>Edad</th>
			          	<th>Categor&iacute;a</th>
			          	<th>Responsable</th>
			          	<?php if (empty($transport)): ?>
			          		<th>Transportista</th>
			          	<?php endif ?>
			          	<th>N° Factura</th>
			          	<th>Fecha</th>
			          	<th>Monto Recaudado</th>
			          	<th>Monto Pendiente</th>
			          	<th>Estatus</th>
					</tr>
				</thead>
				<tbody>
				<?php 
					if ($size_data > 0) {
						foreach ($data as $data_p): 
							$birthday = new DateTime($data_p['s']['birthday']);
			                $to = new DateTime('today');
			                $age = $birthday->diff($to)->y;

			                $format = "EF";
			                $longitud_relleno = 4 - strlen($data_p['s']['id']);  //Calculo el numero de ceros a ser anadidos
			                $relleno = str_repeat("0",$longitud_relleno);  //Relleno de caracteres
			                $code_final = $format.$relleno.$data_p['s']['id'];
			                $status = "Pagada";
				?>
							<tr>
								<td><?php echo $code_final; ?></td>
								<td><?php echo $data_p['s']['name']." ".$data_p['s']['lastname']; ?></td>
					          	<td><?php echo $data_p['s']['email']; ?></td>
					          	<td><?php echo $age; ?></td>
					          	<td><?php echo $data_p['c']['name']; ?></td>
					          	<td><?php echo $data_p['s']['responsable']; ?></td>
					          	<?php if (empty($transport)): ?>
					          		<td><?php echo $data_p['Transport']['name']." ".$data_p['Transport']['lastname']; ?></td>
					          	<?php endif ?>
					          	<td><?php echo $data_p['b']['bill_code'] ?></td>
					          	<td><?php echo $data_p['b']['date_payment'] ?></td>
					          	<td><?php if($data_p['b']['status'] == 1) echo "$".$data_p['db']['cost']; ?></td>
					          	<td></td>
					          	<td><?php echo $status; ?></td>
							</tr>
				<?php 
						endforeach; 
					}
				?>
				<?php 
					if ($size_pending_payments > 0) {
						foreach ($pending_payments as $data_p): 
							$birthday = new DateTime($data_p['s']['birthday']);
			                $to = new DateTime('today');
			                $age = $birthday->diff($to)->y;

			                $format = "EF";
			                $longitud_relleno = 4 - strlen($data_p['s']['id']);  //Calculo el numero de ceros a ser anadidos
			                $relleno = str_repeat("0",$longitud_relleno);  //Relleno de caracteres
			                $code_final = $format.$relleno.$data_p['s']['id'];
			                $status = "Pendiente";
				?>
							<tr>
								<td><?php echo $code_final; ?></td>
								<td><?php echo $data_p['s']['name']." ".$data_p['s']['lastname']; ?></td>
					          	<td><?php echo $data_p['s']['email']; ?></td>
					          	<td><?php echo $age; ?></td>
					          	<td><?php echo $data_p['c']['name']; ?></td>
					          	<td><?php echo $data_p['s']['responsable']; ?></td>
					          	<?php if (empty($transport)): ?>
					          		<td><?php echo $data_p['Transport']['name']." ".$data_p['Transport']['lastname']; ?></td>
					          	<?php endif ?>
					          	<td></td>
					          	<td></td>
					          	<td></td>
					          	<td><?php echo "$".$data_p['do']['cost']; ?></td>
					          	<td><?php echo $status; ?></td>
							</tr>
				<?php 
						endforeach; 
					}
				?>
				</tbody>
			</table>
		</div>
	</div>
	<br> <br> <br> <br> <br>
	<?php if (!empty($transport)): ?>
			<div class="row element-margin-top-firms">
				<div class="col-md-6">
					<p>Firma Autorizada: ___________________</p>
				</div>
				<div class="col-md-6">
					<p>Recibí Conforme: _____________________</p>
				</div>
			</div>
	<?php endif ?>
</div>
