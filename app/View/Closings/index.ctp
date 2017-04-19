<?php
    echo $this->Html->css('bootstrap-datepicker3.min');
?>
<div class="container">
	<div class="row">
		<div class="col-md-12 text-center title-section">
			<h1>Cierres</h1>
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
		<div class="table-responsive">
  			<table class="table order-table">
  				<thead>
  					<tr>
						<th>N°</th>
						<th>Fecha inicio</th>
						<th>Fecha fin</th>
						<th>Hora</th>
						<th>Total</th>
						<th>Acci&oacute;n</th>
					</tr>
  				</thead>
  				<tbody>
				<?php 
					if ($size > 0) {
						$i = 1;
						foreach ($closings as $closing):
				?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $closing['Closing']['date_closing_start']; ?></td>
							<td><?php echo $closing['Closing']['date_closing_end']; ?></td>
							<td><?php echo $closing['Closing']['hour']; ?></td>
							<td><?php echo "$".$closing['Closing']['total_closing']; ?></td>
							<td>
		                        <a href="<?php echo Router::url(array('controller'=>'closings','action' => 'detailClosing/'.$closing['Closing']['id'])); ?>" title="Ver detalle"><i class="fa fa-list action"></i></a>
		                        <a href="<?php echo Router::url(array('controller'=>'closings','action' => 'closeDay/'.$closing['Closing']['id'], 'ext' => 'pdf')); ?>" title="Imprimir" target="_blank"><i class="fa fa-file-pdf-o action"></i></a>
	                        	<a href="#" title="Revertir cierre" onclick='revert(<?php echo $closing['Closing']['id']; ?>)'><i class="fa fa-history action delete"></i></a>
							</td>
						</tr>
				<?php 		
						$i++;
						endforeach;
					} else {
				?>
					<tr>
						<td colspan="5" class="text-center">No existen cierres registrados</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<form id="revert_close_form" action="<?php echo Router::url(array('controller'=>'closings','action' => 'revertClose')); ?>" method="POST">
<div id="modalRevertClose" class="modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        	<h4 class="modal-title">Mensaje de Advertencia</h4>
	      	</div>
	      	<div class="modal-body">
	        	<p>¿Est&aacute; seguro que desea revertir el cierre?</p>
	      	</div>
	      	<div class="modal-footer">
	      		<button type="submit" class="btn btn-primary">Aceptar</button>
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
	      		<a href="<?php echo Router::url(array('controller'=>'closings','action' => 'index')); ?>" type="button" class="btn btn-primary">Aceptar</a>
	      	</div>
	    </div>
	</div>
</div>
<div id="modalWait" class="modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
	<div class="col-md-12 text-center">
	    <h2>Por favor espere mientras procesamos la solicitud</h2>
	    <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
	</div>
</div>
<?php 
	/*Library Datepicker Bootstrap*/
	echo $this->Html->script('bootstrap-datepicker.min');
	echo $this->Html->script('bootstrap-datepicker.es.min');

	echo $this->Html->script('closing');
?>