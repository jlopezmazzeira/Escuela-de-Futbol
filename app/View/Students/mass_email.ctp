<div class="container">
	<div class="row">
		<div class="col-md-12 text-center title-section">
			<h1>Lista de Estudiantes</h1>
			<span>
				<a href="<?php echo Router::url(array('controller'=>'home','action' => 'index')); ?>" title="Regresar">
					<i class="fa fa-reply action" aria-hidden="true"></i>
				</a>
			</span>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 text-center">
			<h3 id="estudiantes_correo"></h3>
		</div>
		<div class="col-md-12">
			<form class=" well form-horizontal" action="<?php echo Router::url(array('controller'=>'students','action' => 'massEmailAjax')); ?>" method="post" id="email_form">
				<div class="form-group">
                    <label class="col-md-2 control-label">Titulo del mensaje</label>
                    <div class="col-md-8 inputGroupContainer">
                    	<input type="text" id="title_message" name="title_message" class="form-control" maxlength="120" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Mensaje</label>
                    <div class="col-md-8 inputGroupContainer">
                        <textarea id="message" name="message" class="form-control" cols="80" rows="5" required></textarea>
                    </div>
              	</div>
              	<div class="form-group">
              		<label class="col-md-2 control-label"></label>
                    <div class="col-md-4 inputGroupContainer">
                    	<input type="hidden" id="active_students" name="active_students" value="<?php echo $active_students; ?>">
                    	<input type="hidden" id="disabled_students" name="disabled_students" value="<?php echo $disabled_students; ?>">
                    	<input type="hidden" id="debtor_students" name="debtor_students" value="<?php echo $debtor_students; ?>">
                    	<input type="hidden" id="inactive_students" name="inactive_students" value="<?php echo $inactive_students; ?>">
                    	<input type="hidden" id="absent_students" name="absent_students" value="<?php echo $absent_students; ?>">
                    	<input type="hidden" id="total_students" name="total_students" value="<?php echo $size; ?>">
                    	<input type="hidden" id="renew_students" name="renew_students" value="<?php echo $total_students_to_renew; ?>">
                    	<input type="hidden" id="type_filter" name="type_filter" value="all"/>
                    	<input type="submit" id="send" class="btn btn-primary" value="Enviar mensaje">
                    </div>
              	</div>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 text-center">
			<div class="col-md-offset-3 col-md-6">
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
			<div class="col-md-12 element-margin-bottom">
				<label class="radio-inline">
					<input type="radio" class="filters" name="student_filter" value="Ren">Por Renovar
				</label>
				<label class="radio-inline">
					<input type="radio" class="filters" name="student_filter" value="Act"> Activos
				</label>
				<label class="radio-inline">
					<input type="radio" class="filters" name="student_filter" value="Deu"> Deudores
				</label>
				<label class="radio-inline">
					<input type="radio" class="filters" name="student_filter" value="Aus"> Ausentes
				</label>
				<label class="radio-inline">
					<input type="radio" class="filters" name="student_filter" value="Inh"> Inhabilitados	
				</label>
				<label class="radio-inline">
					<input type="radio" class="filters" name="student_filter" value="Ina"> Inactivos
				</label>
			  	<button id="reset" class="btn btn-primary">Limpiar</button>
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
							<th>Estatus</th>
						</tr>
	  				</thead>
					<tbody>
					<?php 
						if ($size > 0) {
							$now = time();
							$to = new DateTime('today');
							foreach ($students as $student):
								$cumple = new DateTime($student['Student']['birthday']);
		                        $to = new DateTime('today');
		                        $age = $cumple->diff($to)->y;

		                        $formato = "EF";
		                        $longitud_relleno = 4 - strlen($student['Student']['id']);  //Calculo el numero de ceros a ser anadidos
		                        $relleno = str_repeat("0",$longitud_relleno);  //Relleno de caracteres
		                        $code_final = $formato.$relleno.$student['Student']['id'];

		                        $date_inscription = strtotime($student['Student']['date_inscription']);
		            			$date_diff = $now - $date_inscription;
		            			$days = floor($date_diff / (60 * 60 * 24));

		                        $status;
		                        $class;
		                        if($student['Student']['status'] == 1) {
		                            $status = "Act";
		                            $class = "active_students";
		                        }
		                        if($student['Student']['status'] == 2){
		                            $status = "Deu";
		                            $class = "debtor_students";
		                        }
		                        if($student['Student']['status'] == 3){
		                            $status = "Inh";
		                            $class = "disabled_students";
		                        }
		                        if($student['Student']['status'] == 4){
		                            $status = "Ina";
		                            $class = "inactive_students";
		                        }
		                        if($student['Student']['status'] == 5){
		                            $status = "Aus";
		                            $class = "absent_students";
		                        }

		                        if($days > 350 && $student['Student']['status'] == 1){
		                           $status = "Ren";
		                           $class = "renew_students";
		                        }
					?>
							<tr <?php if($student['Student']['status'] == 4) echo "class='inactives'"; ?>>
								<td><?php echo $code_final; ?></td>
								<td><?php echo $student['Student']['lastname']." ".$student['Student']['name']; ?>
								</td>
								<td><?php echo $student['Student']['email']; ?></td>
								<td><?php echo $age; ?></td>
								<td><?php echo $student['c']['name']; ?></td>
								<td><?php echo $student['Student']['responsable']; ?></td>
								<td class="<?php echo $class; ?>"><b><?php echo $status;?></b></td>
							</tr>
					<?php 		
							endforeach;
						} else {
					?>		
							<tr>
								<td colspan="8" class="text-center">No Existen estudiantes registrados</td>
							</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div id="modalWait" class="modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog text-center" role="document">
    	<h2>Por favor espere mientras enviamos los emails</h2>
    	<i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
	</div>
</div>
<div id="modalMessage" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        	<h4 class="modal-title">Mensaje</h4>
	      	</div>
	      	<div class="modal-body">
	      		<p id="message_email"></p>
	      	</div>
	      	<div class="modal-footer">
	      		<a href="<?php echo Router::url(array('controller'=>'students','action' => 'massEmail')); ?>" type="button" class="btn btn-default">Aceptar</a>
	      	</div>
	    </div>
	</div>
</div>
<?php 
	echo $this->Html->script('bootstrapValidator');
	echo $this->Html->script('mass-email'); 
?>