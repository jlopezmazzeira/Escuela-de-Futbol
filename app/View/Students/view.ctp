<div class="container">
	<div class="row">
		<div class="col-md-12 text-center title-section">
			<h1>Informaci&oacute;n del Estudiante </h1>
			<span>
				<?php 
					echo $this->Html->Link($this->Html->image('category.png'),
				 		array('action' => 'assignCategory',$student['Student']['id']),
				 		array('escape' => false, 'title' => 'Asignar Categor&iacute;a'));
				?>
			</span>
			<span>
				<?php echo $this->Html->Link($this->Html->image('discount.png'),
				 		array('action' => 'assignScholarship',$student['Student']['id']),
				 		array('escape' => false,'title' => 'Asignar una Beca'));
				?>
			</span>
			<span>
				<?php echo $this->Html->Link($this->Html->image('settings.png'),
					 	array('action' => 'assignStatus',$student['Student']['id']),
					 	array('escape' => false,'title' => 'Cambiar Status'));
				?>
			</span>
			<span>
				<a href="<?php echo Router::url(array('controller'=>'students','action' => 'index')); ?>" title="Regresar">
					<i class="fa fa-reply action" aria-hidden="true"></i>
				</a>
			</span>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="wrapper">
			  	<div class="block">
			    	<div class="number"><?php if($monthly_payment == 0) echo "N/A"; else echo "$".$monthly_payment; ?></div>
			    	<div class="string"><?php echo $message_rate; ?></div>
			  	</div>
			  	<div class="block">
			    	<div class="number"><?php if($schorlarship == 0) echo "N/A"; else echo "$".$sub_total; ?></div>
			    	<div class="string">Tarifa + Dcto <?php echo $schorlarship; ?> %</div>
			  	</div>
			    <div class="block">
			    	<div class="number"><?php if($transport == 0) echo "N/A"; else echo "$".$transport; ?></div>
			    	<div class="string">Transporte: <?php echo $route; ?></div>
			  	</div>
			    <div class="block">
			    	<div class="number"><?php if($extra_training == 0) echo "N/A"; else echo "$".$extra_training; ?></div>
			    	<div class="string">Modalidad Extra Training</div>
			  	</div>
			    <div class="block">
			    	<div class="number"><?php if($fitness == 0) echo "N/A"; else echo "$".$fitness; ?></div>
			    	<div class="string">Modalidad Fitness</div>
			  	</div>
			  	<div class="block">
			    	<div class="number"><?php echo "$".$total_payment; ?></div>
			    	<div class="string">Valor Mensual</div>
			  </div>
			</div>
		</div>
	</div>
	<div class="row element-margin-bottom">
		<div>
			<div class="col-md-4 element-padding-right">
				<div class="item-student">
					<div class="item-student-header">
						<?php echo $this->Html->image('player.png', array('alt' => 'icono')); ?>
						<h2><?php echo $student['Student']['name']." ".$student['Student']['lastname']; ?></h2>
					</div>
					<div class="item-student-body">
						<h3>Fecha de Matricula</h3>
						<p id="date_inscription_es"><?php echo $student['Student']['date_inscription'] ?></p>
						<div class="item-student-control">
							<div class="sub-item">
								<a href="<?php echo Router::url(array('controller'=>'students','action' => 'edit/'.$student['Student']['id'])); ?>" title="Editar"><i class="fa fa-pencil icon-bottom action" aria-hidden="true"></i></a>
							</div>
							<div class="sub-item">
			    				<a href="#" id="enlace" title="inasistencias" class="EventsWidget-count"><?php echo $assistance ?></a>
			    			</div>
			    			<div class="sub-item">
		                		<?php echo $this->Html->image($icon_status); ?>
		                	</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 element-padding-right">
				<div class="item-student color-orange">
					<div class="item-student-body color-orange">
						<p><b>Categor&iacute;a</b>: <?php if(empty($student['c']['name'])) echo "No posee"; else echo $student['c']['name']; ?></p>
				        <p><b>Email</b>: <?php if(empty($student['Student']['email'])) echo "No posee"; else echo $student['Student']['email']; ?></p>
				        <p><b>Telf 1:</b> <?php if(empty($student['Student']['phone'])) echo "No posee"; else echo $student['Student']['phone']; ?></p>
				        <p><b>Ruta:</b> <?php echo $route; ?></p>  
				        <p><b>Direcci&oacute;n:</b> <?php echo $student['Student']['address']; ?></p>
				      	<p><b>N° Hermanos:</b> <?php echo $siblings; ?></p>
				      	<p><b>Estatus:</b> <?php echo $description_status; ?></p>
					</div>
				</div>
			</div>
			<div class="col-md-4 element-padding-right">
				<div class="item-student color-orange">
					<div class="item-student-body color-orange">
						<h2><?php echo $title_image; ?></h2>
							<?php if (empty($student['Student']['document_img'])){ ?>
									<label for="file-input">
										<i class="fa fa-cloud-upload fa-5x icon-upload" aria-hidden="true"></i>
									</label>
							<?php } else { ?>
										<i id="image" file="<?php echo $route_img.'/'.$student['Student']['document_img'] ?>" class="fa fa-picture-o fa-5x icon-upload" aria-hidden="true"></i>
							<?php } ?>
						<form method="post" enctype="multipart/form-data" id="form_upload"  action="<?php echo Router::url(array('controller'=>'students','action' => 'uploadImage/'.$student['Student']['id'])); ?>">
						    <input type="file" id="file-input" name="data[File][document]" class="input-upload" />
						</form>
						<div class="item-student-control">
							<?php if (!empty($student['Student']['document_img'])){ ?>
									<label for="file-input">
										<h2 class="upload-image">Actualizar</h2>
									</label>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<?php if($student['Student']['exonerated'] == 1){ ?>
					<div class="alert alert-info" role="alert">
						<strong>Notificaci&oacute;n: </strong> Estudiante Exonerado!
					</div>
			<?php } ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<?php if(!empty($student['Student']['observation'])){ ?>
					<div class="alert alert-warning" role="alert">
						<strong>Observaci&oacute;n: </strong><?php echo $student['Student']['observation']; ?>
					</div>
			<?php } ?>
		</div>
	</div>
	<div class="row">
		<?php if($student['Student']['status'] == 1){ ?>
					<a title="Facturar" href="<?php echo Router::url(array('controller'=>'bills','action' => 'studentBill/'.$student['Student']['id'])); ?>">
						<h3 class="title-bill">Facturar Estudiante <i class="fa fa-list-alt" aria-hidden="true"></i></h3>
					</a>
		<?php } elseif($student['Student']['status'] == 2){ ?>
					<a title="Facturar" href="<?php echo Router::url(array('controller'=>'orders','action' => 'pendingPayments/'.$student['Student']['id'])); ?>">
						<h3 class="title-bill">Facturar Estudiante <i class="fa fa-list-alt" aria-hidden="true"></i></h3>
					</a>
		<?php } ?>
	</div>
</div>
<div id="modalMessage" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	      	<div class="modal-body">
	        	<p class="message"></p>
	      	</div>
	      	<div class="modal-footer">
	      		<a href="<?php echo Router::url(array('controller'=>'students','action' => 'view/'.$student['Student']['id'])); ?>" type="button" class="btn btn-default">Cerrar</a>
	      	</div>
	    </div>
	</div>
</div>
<?php echo $this->Html->script('view-student'); ?>