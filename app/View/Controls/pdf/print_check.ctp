<div class="landscape-bill">
	<div class="row">
		<div class="col-md-12">
			<h1 class="text-center">Ficha de Asistencia <?php echo $category; ?></h1>
			<h4 class="text-center">Profesores: <?php echo $teacher; ?></h4>
			<h4 class="text-center">Mes: <?php echo $month; ?></h4>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>N°</th>
						<th>Estudiante</th>
						<th>Estatus</th>
						<?php for ($i=1; $i <= $days; $i++) { ?> 
								<th><?php echo $i; ?></th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php for ($i=0; $i < count($students); $i++) { ?>
							<tr>
								<td><?php echo $students[$i]['s']['code']; ?></td>
								<td><?php echo $students[$i]['s']['name']." ".$students[$i]['s']['lastname']; ?></td>
								<td><?php echo $students[$i]['s']['status_str']; ?></td>
								<?php for ($j=0; $j < $days; $j++) { ?> 
									<td>&nbsp;</td>
								<?php } ?>
							</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row element-margin-top-firms">
		<p><strong>NOTA: Los estudiantes con estatus de deudor y ausente deben dirigirse a administraci&oacute;n.</strong></p>
	</div>
	<div class="row element-margin-top-firms">
		<div class="col-md-6">
			<p>Firma Autorizada: ___________________</p>
		</div>
		<div class="col-md-6">
			<p>Firma Responsable: _____________________</p>
		</div>
	</div>
</div>