<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1 class="text-center">Reporte de Estudiantes por Categor&iacute;a</h1>
			<h4 class="text-center">Fecha <?php echo date('d-m-Y'); ?></h4>
		</div>
	</div>
	<?php foreach ($data_final as $key => $value){
			$teacher  = "";
          	if(is_array($value)){
              	foreach ($value as $index => $teachers) {
                 	if ($index == "teachers") {
                   		if (is_array($teachers)) {
                      		$total = count($teachers) - 1;
                      		for ($i=0; $i < count($teachers); $i++) {
                          		if ($i == 0) {
                              		$teacher .= $teachers[$i]["name"]." ".$teachers[$i]["lastname"]." - ";
                          		} else if($total != $i) {
                              		$teacher .= $teachers[$i]["name"]." ".$teachers[$i]["lastname"]." - ";
                          		} else if($total == $i) {
                              		$teacher .= $teachers[$i]["name"]." ".$teachers[$i]["lastname"];
                          		}
                      		}
                   		} else {
                      		$teacher = $teachers;
                    	}
                 	}
              	}
          	}
	?>
		<div class="row">
			<div class="col-md-12">
				<h2 class="text-center">Categor&iacute;a: <?php echo $key; ?></h2>
				<h2 class="text-center">Profesores: <?php echo $teacher; ?></h2>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>N°</th>
							<th>Nombre</th>
							<th>Apellido</th>
							<th>Edad</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($value as $index => $students){
								if ($index == "students"){
									foreach ($students as $student){
										$birthday = new DateTime($student['birthday']);
					                    $to = new DateTime('today');
					                    $age = $birthday->diff($to)->y;

					                    $format = "EF";
					                    $longitud_relleno = 4 - strlen($student['id']);  //Calculo el numero de ceros a ser anadidos
					                    $relleno = str_repeat("0",$longitud_relleno);  //Relleno de caracteres
					                    $code_final = $format.$relleno.$student['id'];
						?>
											<tr>
						                        <td><?php echo $code_final; ?></td>
						                        <td><?php echo $student['name']; ?></td>
						                        <td><?php echo $student['lastname']; ?></td>
						                        <td><?php echo $age; ?></td>
						                    </tr>
						<?php 
									}
								}
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	<?php } ?>
</div>