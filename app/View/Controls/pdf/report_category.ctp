<div class="landscape-bill">
	<div class="row">
		<div class="col-md-12">
			<h1 class="text-center">Reporte de asistencia por categor&iacute;a</h1>
			<h4 class="text-center">Categor&iacute;a: <?php echo $category; ?></h4>
			<h4 class="text-center">Profesores: <?php echo $teacher; ?></h4>
			<h4 class="text-center">Desde: <?php echo $date_from; ?> - Hasta: <?php echo $date_until; ?></h4>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>N°</th>
						<th>Estudiante</th>
						<?php for ($i=0; $i < count($control); $i++) { ?> 
								<th><?php echo strftime("%d-%m", strtotime($control[$i]['Control']['date_control'])); ?></th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php for ($i=0; $i < count($students); $i++) { ?>
							<tr>
								<td><?php echo $students[$i]['s']['code']; ?></td>
								<td><?php echo $students[$i]['s']['name']." ".$students[$i]['s']['lastname']; ?></td>
								<?php for ($j=0; $j < count($control); $j++) { ?>
										<td>
											<?php for ($k=0; $k < count($assistance); $k++) {
													if ($control[$j]['Control']['id'] == $assistance[$k]['DetailsControl']['control_id'] && $students[$i]['s']['id'] == $assistance[$k]['DetailsControl']['student_id']) {
														if ($assistance[$k]['DetailsControl']['assistance'] == 1) echo "A"; 
														else echo "I";
														break;
													}
												} ?>
										</td>
								<?php } ?>
							</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
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