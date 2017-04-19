<div class="landscape-bill">
	<div class="row">
		<div class="col-md-12">
			<h1 class="text-center">Reporte de asistencia por estudiante</h1>
			<h4 class="text-center">Estudiante: <?php echo $student; ?> - Categor&iacute;a: <?php echo $category; ?></h4>
			<h4 class="text-center">Profesores: <?php echo $teacher; ?></h4>
			<h4 class="text-center">Desde: <?php echo $date_from; ?> - Hasta: <?php echo $date_until; ?></h4>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table class="table table-bordered">
				<thead>
					<tr>
						<?php for ($i=0; $i < count($assistance); $i++) { ?> 
								<th><?php echo strftime("%d-%m", strtotime($assistance[$i]['c']['date_control'])); ?></th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<tr>
						<?php for ($i=0; $i < count($assistance); $i++) { ?>		
							<td>
								<?php 
									if ($assistance[$i]['DetailsControl']['assistance'] == 1):
										echo "A";
									else:
										echo "I";
									endif; 
								?>
							</td>
						<?php } ?>
					</tr>
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