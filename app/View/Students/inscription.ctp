<?php
    echo $this->Html->css('bootstrap-datepicker3.min');
?>
<div class="container">
	<div class="row">
		<div class="col-md-12 text-center title-section">
			<h1>Lista de Estudiantes Matriculados</h1>
			<span>
				<form id="form_report_student" action="<?php echo Router::url(array('controller'=>'students','action' => 'reportInscription', 'ext' => 'pdf')); ?>" method="POST">
					<a id="report" href="#" title="Generar reporte"><i class="fa fa-file-pdf-o action" aria-hidden="true"></i></a>
					<input type="hidden" id="date_from_report" name="date_from_report">
					<input type="hidden" id="date_until_report" name="date_until_report">
				</form>
			</span>
			<span>
				<a href="<?php echo Router::url(array('controller'=>'home','action' => 'index')); ?>" title="Regresar">
					<i class="fa fa-reply action" aria-hidden="true"></i>
				</a>
			</span>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 text-center">
			<div class="col-md-offset-3 col-md-7">
	            <div id="custom-search-input">
	                <div class="input-group col-md-12">
	                    <input type="text" id="search" class="form-control input-lg light-table-filter" data-table="order-table" placeholder="Buscar" />
	                    <span class="input-group-btn">
	                        <button class="btn btn-info btn-lg" type="button">
	                            <i class="glyphicon glyphicon-search"></i>
	                        </button>
	                    </span>
	                </div>
	            </div>
	        </div>
			<div class="col-md-offset-3 col-md-7 element-margin-bottom">
			  	<form class="form-inline">
			  		<div class="form-group">
    					<div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-end-date="0d">
    						<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i>
							</span>
      						<input type="text" id="date_from" class="form-control input-height">
    					</div>
  					</div>
  					<div class="form-group">
    					<div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-end-date="0d">
    						<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i>
							</span>
      						<input type="text" id="date_until" class="form-control input-height">
    					</div>
  					</div>
  					<button type="button" id="filter_date_range" class="btn btn-primary">Buscar</button>
  					<button type="button" id="reset" class="btn btn-primary">Limpiar</button>
			  	</form>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="table-responsive">
	  			<table class="table order-table">
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
						if ($size > 0) {
							$now = time();
							$to = new DateTime('today');
							foreach ($students as $student):
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
								<td><?php echo $student['Student']['lastname']." ".$student['Student']['name']; ?></td>
								<td><?php echo $student['Student']['email']; ?></td>
								<td><?php echo $age; ?></td>
								<td><?php echo $student['c']['name']; ?></td>
								<td><?php echo $student['Student']['responsable']; ?></td>
								<td><?php echo  $student['Student']['date_created'];?></td>
							</tr>
					<?php 		
							endforeach;
						} else {
					?>
						<tr>
							<td colspan="7" class="text-center">No Existen estudiantes registrados</td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php 
	/*Library Datepicker Bootstrap*/
	echo $this->Html->script('bootstrap-datepicker.min');
	echo $this->Html->script('bootstrap-datepicker.es.min');

	echo $this->Html->script('filter-student-inscription');
?>