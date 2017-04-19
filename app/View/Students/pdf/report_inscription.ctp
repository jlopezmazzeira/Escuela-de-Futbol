<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1 class="text-center">Estudiantes Matriculados</h1>
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
						<th>Correo Electr&oacute;nico</th>
						<th>Edad</th>
						<th>Categor&iacute;a</th>
						<th>Responsable</th>
						<th>Fecha de Inscripci&oacute;n</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						if ($size > 0){
							$now = time();
							$to = new DateTime('today');
							foreach ($students as $student){
								$birthday = new DateTime($student['Student']['birthday']);
		                        $to = new DateTime('today');
		                        $age = $birthday->diff($to)->y;

		                        $format = "EF";
		                        $longitud_relleno = 4 - strlen($student['Student']['id']);  //Calculo el numero de ceros a ser anadidos
		                        $relleno = str_repeat("0",$longitud_relleno);  //Relleno de caracteres
		                        $code_final = $format.$relleno.$student['Student']['id'];
					?>
								<tr>
									<td><?php echo $code_final; ?></td>
									<td><?php echo $student['Student']['lastname']." ".$student['Student']['name']; ?> </td>
									<td><?php echo $student['Student']['email']; ?></td>
									<td><?php echo $age; ?></td>
									<td><?php echo $student['c']['name']; ?></td>
									<td><?php echo $student['Student']['responsable']; ?></td>
									<td><?php echo $student['Student']['date_created']; ?></td>
								</tr>
					<?php 	
							}
						} else {
					?>
							<tr>
								<td colspan="7" class="text-center">No existen estudiantes registrados durante el mes</td>
							</tr>
					<?php 
						}
					?>
				</tbody>
			</table>
		</div>	
	</div>
</div>