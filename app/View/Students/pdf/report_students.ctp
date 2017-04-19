<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1 class="text-center">Reporte de Estudiantes</h1>
			<h4 class="text-center">N&uacute;mero de Estudiantes: <?php echo $size; ?></h4>
			<h4 class="text-center">Fecha <?php echo date('d-m-Y'); ?></h4>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>N°</th>
						<th>Nombre Completo</th>
						<th>Email</th>
						<th>Edad</th>
						<th>Categor&iacute;a</th>
						<th>Responsable</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						if ($size > 0) {
							for ($i=0; $i < count($students); $i++) {								
			        ?>
		        				<tr>
									<td><?php echo $students[$i]['code_final']; ?></td>
									<td><?php echo $students[$i]['name']; ?> </td>
									<td><?php echo $students[$i]['email']; ?></td>
									<td><?php echo $students[$i]['age']; ?></td>
									<td><?php echo $students[$i]['category']; ?></td>
									<td><?php echo $students[$i]['responsable']; ?></td>
								</tr>
			        <?php
			        		}
						} else {
					?>
							<tr>
								<td colspan="6" class="text-center">No Existen estudiantes con ese estatus</td>
							</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>