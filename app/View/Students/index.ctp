<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="wrapper">
			  	<div class="block">
			    	<div class="number"><?php echo $active_students; ?></div>
			    	<div class="string">Activos</div>
			  	</div>
			  	<div class="block">
			    	<div class="number"><?php echo $total_students_to_renew; ?></div>
			    	<div class="string">Por revonar matricula</div>
			 	</div>
			  	<div class="block">
			    	<div class="number"><?php echo $debtor_students; ?></div>
			    	<div class="string">Deudores</div>
			  	</div>
			  	<div class="block">
			    	<div class="number"><?php echo $absent_students; ?></div>
			    	<div class="string">Ausentes</div>
			  	</div>
			  	<div class="block">
			    	<div class="number"><?php echo $inactive_students; ?></div>
			    	<div class="string">Inactivos</div>
			  	</div>
			  	<div class="block">
			    	<div class="number"><?php echo $disabled_students; ?></div>
			    	<div class="string">Inhabilitados</div>
			  	</div>
			  	<div class="block">
			    	<div class="number"><?php echo $new_students; ?></div>
			    	<div class="string">Nuevos <?php echo $month; ?></div>
			  	</div>
			  	<div class="block">
			    	<div class="number"><?php echo $total_students; ?></div>
			    	<div class="string">Totales</div>
			  	</div>
			</div>
		</div>
	</div>
</div>
<div class="container">
	<form id="form_report_student" action="<?php echo Router::url(array('controller'=>'students','action' => 'reportStudents', 'ext' => 'pdf')); ?>" method="POST">
		<div class="row">
			<div class="col-md-12 text-center title-section">
				<h1>Lista de Estudiantes</h1>
				<span>
					<a href="<?php echo Router::url(array('controller'=>'students','action' => 'add')); ?>" title="Agregar estudiante"><i class="fa fa-user-plus action" aria-hidden="true"></i></a>
				</span>
				<span>
					<a id="report" href="#" title="Generar reporte"><i class="fa fa-file-pdf-o action" aria-hidden="true"></i></a>
				</span>
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
					<label class="checkbox-inline">
						<input type="checkbox" class="filters" name="student_filter[]" value="Ren">Por Renovar
					</label>
					<label class="checkbox-inline">
						<input type="checkbox" class="filters" name="student_filter[]" value="Act"> Activos
					</label>
					<label class="checkbox-inline">
						<input type="checkbox" class="filters" name="student_filter[]" value="Deu"> Deudores
					</label>
					<label class="checkbox-inline">
						<input type="checkbox" class="filters" name="student_filter[]" value="Aus"> Ausentes	
					</label>
					<label class="checkbox-inline">
						<input type="checkbox" class="filters" name="student_filter[]" value="Inh"> Inhabilitados	
					</label>
					<label class="checkbox-inline">
						<input type="checkbox" class="filters" name="student_filter[]" value="Ina"> Inactivos
					</label>
					<button type="button" id="btnSearch" class="btn btn-primary">Buscar</button>
				  	<button type="button" id="reset" class="btn btn-warning">Limpiar</button>
				</div>
			</div>
		</form>
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
							<th>Acci&oacute;n</th>
						</tr>
	  				</thead>
	  				<tbody id="data_students"></tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<form id="delete_form" action="<?php echo Router::url(array('controller'=>'students','action' => 'delete')); ?>" method="POST">
	<div id="modalDeleteStudent" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
		    <div class="modal-content">
		    	<div class="modal-header">
		        	<h4 class="modal-title">Eliminar Estudiante</h4>
		      	</div>
		      	<div class="modal-body">
		        	<p>¿Está seguro que desea eliminar el estudiante?</p>
		      	</div>
		      	<div class="modal-footer">
		        	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		        	<button type="submit" class="btn btn-danger">Eliminar</button>
		      	</div>
		    </div>
		</div>
	</div>
</form>
<form id="order_form" action="<?php echo Router::url(array('controller'=>'orders','action' => 'generateOrderStudent')); ?>" method="POST">
	<div id="modalGenerateOrderStudent" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
		    <div class="modal-content">
		    	<div class="modal-header">
		        	<h4 class="modal-title">Generar orden de pago</h4>
		      	</div>
		      	<div class="modal-body">
		        	<p>¿Está seguro que desea generar la orden de pago del estudiante?</p>
		      	</div>
		      	<div class="modal-footer">
		      		<button type="submit" class="btn btn-primary">Generar</button>
		        	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		      	</div>
		    </div>
		</div>
	</div>
</form>
<div id="modalMessage" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	      	<div class="modal-body">
	        	<p class="message"></p>
	      	</div>
	      	<div class="modal-footer">
	      		<a href="<?php echo Router::url(array('controller'=>'students','action' => 'index')); ?>" type="button" class="btn btn-default">Cerrar</a>
	      	</div>
	    </div>
	</div>
</div>
<?php 
	echo $this->Html->script('delete-student');
	echo $this->Html->script('filter-student');
?>
