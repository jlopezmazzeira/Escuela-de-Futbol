<?php
    echo $this->Html->css('bootstrap-datepicker3.min');
    echo $this->Html->css('bootstrap-select.min');
?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div>
			    <form class="well form-horizontal" action="<?php echo Router::url(array('controller'=>'students','action' => 'editStudent')); ?>" method="post" id="student_form" data-bv-submitbuttons="button[type='submit']" data-remote="true" data-toggle="validator" role="form">
					<fieldset>
						<legend class="text-center">Modificar Estudiante</legend>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
								  	<label class="col-md-4 control-label">Nombre</label>
								  	<div class="col-md-8 inputGroupContainer">
								      	<div class="input-group">
								          	<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
								          	<input type="text" id="name" name="name" placeholder="Nombre del estudiante" class="form-control" maxlength="64" value="<?php echo $student['Student']['name']; ?>">
								      	</div>
								 	</div>
								</div>

								<div class="form-group">
								  	<label class="col-md-4 control-label" >Apellidos</label>
								  	<div class="col-md-8 inputGroupContainer">
								        <div class="input-group">
								          	<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
								          	<input type="text" id="lastname" name="lastname" placeholder="Apellidos del estudiante" class="form-control" maxlength="64" value="<?php echo $student['Student']['lastname']; ?>">
								        </div>
								  	</div>
								</div>

								<div class="form-group">
								    <label class="col-md-4 control-label">G&eacute;nero</label>
								    <div class="col-md-8">
								    	<select id="gender" name="gender" class="selectpicker form-control" data-live-search="true" data-live-search-placeholder="Buscar">
								    		<option value="">Seleccione</option>
								        <?php foreach ($genders as $gender) { ?>
								        		<option value="<?php echo $gender['Gender']['id']; ?>" <?php if ($student['Student']['gender_id'] == $gender['Gender']['id']) echo "selected"; ?>><?php echo $gender['Gender']['name']; ?></option>
								        <?php } ?>
								        </select>
								    </div>
								</div>

								<div class="form-group">
								  	<label class="col-md-4 control-label">Fecha de Nacimiento</label>
								  	<div class="col-md-8 inputGroupContainer">
								  		<div class="input-group date" id="birthday_div" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-end-date="0d">
								  			<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i>
										    </span>
										    <input type="text" id="birthday" name="birthday" class="form-control" value="<?php echo $student['Student']['birthday']; ?>">
										</div>
								  	</div>
								</div>
								
								<div class="form-group">
								  	<label class="col-md-4 control-label">Tel&eacute;fono 1</label>
								  	<div class="col-md-8 inputGroupContainer">
								        <div class="input-group">
								            <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
								            <input type="text" id="phone" name="phone" placeholder="0993323389" class="form-control" maxlength="10" value="<?php echo $student['Student']['phone']; ?>">
								        </div>
								  	</div>
								</div>

								<div class="form-group">
								  	<label class="col-md-4 control-label">Tel&eacute;fono 2</label>
								  	<div class="col-md-8 inputGroupContainer">
								        <div class="input-group">
								            <span class="input-group-addon"><i class="glyphicon glyphicon-phone-alt"></i></span>
								            <input type="text" id="home_phone" name="home_phone" placeholder="0993323389" class="form-control" maxlength="10" value="<?php echo $student['Student']['home_phone']; ?>">
								        </div>
								  	</div>
								</div>
								
							</div>
							<div class="col-md-6">
								<div class="form-group">
								  	<label class="col-md-4 control-label"> Correo Electr&oacute;nico</label>
								  	<div class="col-md-8 inputGroupContainer">
								        <div class="input-group">
								            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
								            <input type="text" id="email" name="email" placeholder="ejemplo@gmail.com" class="form-control" maxlength="64" value="<?php echo $student['Student']['email']; ?>">
								        </div>
								  	</div>
								</div>

								<div class="form-group">
								  	<label class="col-md-4 control-label"> Correo Electr&oacute;nico Alterno</label>
								  	<div class="col-md-8 inputGroupContainer">
								        <div class="input-group">
								            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
								           <input type="text" id="alternative_email" name="alternative_email" placeholder="ejemplo@gmail.com" class="form-control" maxlength="64" value="<?php echo $student['Student']['alternative_email']; ?>">
								        </div>
								  	</div>
								</div>

								<div class="form-group">
								  	<label class="col-md-4 control-label">Direcci&oacute;n</label>
								  	<div class="col-md-8 inputGroupContainer">
								        <div class="input-group">
								            <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
								            <input type="text" id="address" name="address" placeholder="Dirección" class="form-control" maxlength="120" value="<?php echo $student['Student']['address']; ?>">
								        </div>
								  	</div>
								</div>

								<div class="form-group">
								  	<label class="col-md-4 control-label">Responsable</label>
								  	<div class="col-md-8 inputGroupContainer">
								      	<div class="input-group">
								          	<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
								          	<input type="text" id="responsable" name="responsable" placeholder="Nombre Responsable" class="form-control" maxlength="128" value="<?php echo $student['Student']['responsable']; ?>">
								      	</div>
								  	</div>
								</div>

								<div class="form-group">
								  	<label class="col-md-4 control-label">Parentesco</label>
								  	<div class="col-md-8 inputGroupContainer">
								    	<div class="input-group">
								          	<span class="input-group-addon"><i class="glyphicon glyphicon-eye-open"></i></span>
								          	<input type="text" id="relation" name="relation" placeholder="Relación con estudiante" class="form-control" maxlength="16" value="<?php echo $student['Student']['relation']; ?>">
								    	</div>
								  	</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<legend>Informaci&oacute;n Adicional <i id="iconInformation" class="glyphicon glyphicon-chevron-right"></i></legend>
							</div>
							<div id="aditionalInformation">
								<div class="col-md-6">
									<div class="form-group">
								        <label class="col-md-4 control-label">Seleccionar categor&iacute;a</label>
								        <div class="col-md-8 inputGroupContainer">
								            <select id="category" name="category" class="selectpicker form-control" data-live-search="true" data-live-search-placeholder="Buscar" required>
								               	<option value="" selected>Seleccione</option>
								                <?php foreach ($categories as $category): ?>
								                    <option value="<?php echo $category['Category']['id']; ?>" <?php if ($student['Student']['category_id'] == $category['Category']['id']) echo "selected"; ?>>
								                        <?php echo $category['Category']['name']; ?>
								                    </option>
								                <?php endforeach; ?>
								            </select>
								        </div>
								    </div>

								    <div class="form-group">
								        <label class="col-md-4 control-label">Seleccionar modalidad</label>
								        <div class="col-md-8 inputGroupContainer">
								            <select id="training_mode" name="training_mode" class="selectpicker form-control" data-live-search="true" data-live-search-placeholder="Buscar" required>
								            	<option value="" selected>Seleccione</option>
								            	<?php foreach ($training_modes as $training_mode) { ?>
								            			<option value="<?php echo $training_mode['TrainingMode']['id']; ?>" <?php if ($student['Student']['training_mode_id'] == $training_mode['TrainingMode']['id']) echo "selected"; ?>><?php echo $training_mode['TrainingMode']['name']; ?></option>
										        <?php } ?>
								            </select>
								        </div>
								    </div>

								    <div class="form-group">
								      	<label class="col-md-4 control-label">Entrenamiento Extra Training</label>
								      	<div class="col-md-8 inputGroupContainer margin-left-checkbox">
								         	<label class="checkbox">
								              	<input type="hidden" id="extra_training_s" name="extra_training" value="0">
								              	<input type="checkbox" id="extra_training" class="checkbox" value="1" <?php if($student['Student']['extra_training'] == 1) echo "checked"; ?>> Extra Training
								         	</label>
								      	</div>
								    </div>

									<div class="form-group">
								      	<label class="col-md-4 control-label">Entrenamiento Fitness</label>
								      	<div class="col-md-8 inputGroupContainer margin-left-checkbox">
								         	<label class="checkbox">
								              	<input type="hidden" id="fitness_s" name="fitness" value="0">
								              	<input type="checkbox" id="fitness" class="checkbox" value="1" <?php if($student['Student']['fitness'] == 1) echo "checked"; ?>> Fitness
								         	</label>
								      	</div>
								    </div>

								</div>
								<div class="col-md-6">

									<div class="form-group">
								        <label class="col-md-4 control-label">Seleccionar descuento</label>
								        <div class="col-md-8 inputGroupContainer">
								            <select id="scholarship" name="scholarship" class="selectpicker form-control" data-live-search="true" data-live-search-placeholder="Buscar">
								               	<option value="" selected>Seleccione</option>
								                <?php foreach ($scholarships as $scholarship): ?>
								                    <option value="<?php echo $scholarship['Scholarship']['id']; ?>" <?php if ($student['Student']['scholarship_id'] == $scholarship['Scholarship']['id']) echo "selected"; ?>>
								                        <?php echo $scholarship['Scholarship']['name']; ?>
								                    </option>
								                <?php endforeach; ?>
								              </select>
								        </div>
								    </div>
								    <div class="form-group">
								        <label class="col-md-4 control-label">Seleccionar ruta</label>
								        <div class="col-md-8 inputGroupContainer">
								            <select id="routes" name="routes" class="selectpicker form-control" data-live-search="true" data-live-search-placeholder="Buscar">
								               	<option value="">Seleccione</option>
								                <?php foreach ($routes as $route): ?>
								                    <option value="<?php echo $route['Route']['id']; ?>" <?php if($route['Route']['id'] == $route_id) echo 'selected'; ?>>
								                        <?php echo $route['Route']['name']; ?>
								                    </option>
								                <?php endforeach; ?>
								            </select>
								        </div>
								    </div>
								    <div class="form-group">
								        <label class="col-md-4 control-label">Seleccionar transportista</label>
								        <div class="col-md-8 inputGroupContainer">
								            <select id="transport" name="transport" class="selectpicker form-control" data-live-search="true" data-live-search-placeholder="Buscar">
								               	<option value="">Seleccione</option>
								                <?php foreach ($transports as $transport): ?>
								                	<option value="<?php echo $transport['t']['id']; ?>" <?php if($transport['t']['id'] == $transport_id) echo 'selected'; ?>>
								                        <?php echo $transport['t']['name']." ".$transport['t']['lastname']; ?>
								                    </option>
								                <?php endforeach; ?>
								            </select>
								        </div>
								    </div>

								    <div class="form-group">
								      	<label class="col-md-4 control-label"> Fecha de inscripci&oacute;n transporte</label>
								      	<div class="col-md-8 inputGroupContainer">
								        	<div id="date_transport_div" class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-end-date="0d">
								        		<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i>
										    </span>
											    <input type="text" id="date_transport" name="date_transport" class="form-control" value="<?php echo $student['Student']['date_transport'] ?>">
											</div>
								      	</div>
								    </div>

								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-12">
								<legend>Informaci&oacute;n del Representante - Datos Fiscales <i id="iconResponsable" class="glyphicon glyphicon-chevron-right"></i></legend>
							</div>
							<div id="responsableInformation">
								<div class="col-md-6">
									<div class="form-group">
									    <label class="col-md-4 control-label">Tipo de Documento</label>
									    <div class="col-md-8">
									        <div class="radio">
									            <label>
									                <input type="radio" id="document_type_p" name="document_type" value="Pasaporte" <?php if($responsable['People']['document_type'] == "Pasaporte") echo "checked"; ?> /> Pasaporte
									            </label>
									        </div>
									        <div class="radio">
									            <label>
									                <input type="radio" id="document_type_c" name="document_type" value="Cedula" <?php if($responsable['People']['document_type'] == "Cedula") echo "checked"; ?> /> C&eacute;dula
									            </label>
									        </div>
									        <div class="radio">
									            <label>
									                <input type="radio" id="document_type_r" name="document_type" value="RUC" <?php if($responsable['People']['document_type'] == "RUC") echo "checked"; ?> /> RUC
									            </label>
									        </div>
									    </div>
									</div>

									<div class="form-group">
									  	<label class="col-md-4 control-label">C&eacute;dula/RUC/Pasaporte</label>
									  	<div class="col-md-8 inputGroupContainer">
									        <div class="input-group">
									            <span class="input-group-addon"><i class="glyphicon glyphicon-book"></i></span>
									            <input type="text" id="document_number" name="document_number" placeholder="1721067252" class="form-control" maxlength="13" value="<?php echo $responsable['People']['document_number']; ?>">
									        </div>
									  	</div>
									</div>

									<div class="form-group" id="divSiblings" hidden>
									    <label class="col-md-4 control-label">N&uacute;mero de Hermanos</label>
									    <div class="col-md-8">
									        <div class="radio" id="rb_sibling_1">
									            <input type="hidden" id="sibling" name="sibling" value="0" required/>
									            <label>
									                <input type="radio" id="sibling_1" name="siblings" value="1" /> 1 Hermano
									            </label>
									        </div>
									        <div class="radio" id="rb_sibling_2">
									            <label>
									                <input type="radio" id="sibling_2" name="siblings" value="2"/> 2 Hermanos  o m&aacute;s
									            </label>
									        </div>
									    </div>
									</div>

								</div>
								<div class="col-md-6">
									<div class="form-group">
									  	<label class="col-md-4 control-label">Nombre</label>
									  	<div class="col-md-8 inputGroupContainer">
									        <div class="input-group">
									            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
									            <input type="text" name="responsable_name" id="responsable_name" placeholder="Pedro Pérez" class="form-control" maxlength="64" value="<?php echo $responsable['People']['name']; ?>">
									        </div>
									  	</div>
									</div>

									<div class="form-group">
										<label class="col-md-4 control-label">Direcci&oacute;n</label>
										<div class="col-md-8 inputGroupContainer">
										    <div class="input-group">
										        <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
										        <input type="text" name="responsable_address" id="responsable_address" placeholder="Dirección" class="form-control" maxlength="128" value="<?php echo $responsable['People']['address']; ?>">
										    </div>
										</div>
									</div>

									<div class="form-group">
									  	<label class="col-md-4 control-label">Tel&eacute;fono(s)</label>
									  	<div class="col-md-8 inputGroupContainer">
									    	<div class="input-group">
									        	<span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
									        	<input type="text" name="responsable_phone" id="responsable_phone" placeholder="0993323389" class="form-control" maxlength="20" value="<?php echo $responsable['People']['phone']; ?>">
									    	</div>
									  	</div>
									</div>

								</div>
							</div>
						</div>
			
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
								  	<label class="col-md-4 control-label"></label>
								  	<div class="col-md-4">
								  		<input type="hidden" id="siblings" name="siblings" value="<?php echo $student['Student']['siblings']; ?>">
								  		<input type="hidden" id="student_id" name="student_id" value="<?php echo $student['Student']['id']; ?>">
								    	<input type="button" id="submit_btn" class="btn btn-primary" value="Modificar">
								    	<a type="button" class="btn btn-warning volver" href="<?php echo Router::url(array('controller'=>'students','action' => 'view/'.$student['Student']['id'])); ?>">Volver</a>
								  	</div>
								</div>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>
<div id="modalScholarship" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        	<h4 class="modal-title">Mensaje de Advertencia</h4>
	      	</div>
	      	<div class="modal-body">
	        	<p class="message"></p>
	      	</div>
	      	<div class="modal-footer">
	      		<input type="button" value="Aceptar" class="btn btn-default" data-dismiss="modal">
	      		<input type="button" id="btnCancelScholarship" value="Cancelar" class="btn btn-danger" data-dismiss="modal">
	      	</div>
	    </div>
	</div>
</div>
<div id="modalConfirmation" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        	<h4 class="modal-title">Mensaje de Advertencia</h4>
	      	</div>
	      	<div class="modal-body">
	        	<p>Se han generados cambios y por ende debe pagar un proporcial. ¿Est&aacute; de acuerdo?</p>
	      	</div>
	      	<div class="modal-footer">
	      		<input type="button" id="btnConfirmation"  value="Aceptar" class="btn btn-primary" data-dismiss="modal">
	      		<input type="button" value="Cancelar" class="btn btn-default" data-dismiss="modal">
	      	</div>
	    </div>
	</div>
</div>
<div id="modalBill" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        	<h4 class="modal-title">Mensaje</h4>
	      	</div>
	      	<div id="body-bill" class="modal-body">
	      		<p id="messageInscription"></p>
	      	</div>
	      	<div class="modal-footer">
	      		<a id="btnMessageSuccess" href="<?php echo Router::url(array('controller'=>'bills','action' => 'printBillStudent/')); ?>" hidden>Aceptar</a>
	      		<a id="btnMessage" class="btn btn-primary" href="<?php echo Router::url(array('controller'=>'students','action' => 'view/'.$student['Student']['id'])); ?>">Aceptar</a>
	      	</div>
	    </div>
	</div>
</div>
<div id="modalWait" class="modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog text-center" role="document">
    	<h2>Por favor espere mientras procesamos la solicitud</h2>
    	<i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
	</div>
</div>
<?php 
	/*Library Datepicker Bootstrap*/
	echo $this->Html->script('bootstrap-datepicker.min');
	echo $this->Html->script('bootstrap-datepicker.es.min');

	/*Library Select*/
	echo $this->Html->script('bootstrap-select.min'); 
	echo $this->Html->script('defaults-es_CL.min');
	echo $this->Html->script('bootstrapValidator');
	
	echo $this->Html->script('edit-student');
?>