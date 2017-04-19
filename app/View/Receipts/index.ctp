<?php
    echo $this->Html->css('bootstrap-datepicker3.min');
?>
<div class="container">
	<div class="row">
		<div class="col-md-12 text-center title-section">
			<h1>Recibos de pago</h1>
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
						<th>Factura</th>
						<th>N° de Recibo</th>
						<th>Fecha</th>
						<th>Monto abonado</th>
						<th>Acci&oacute;n</th>
					</tr>
  				</thead>
  				<tbody>
				<?php 
					if ($size > 0) {
						$i = 1;
						foreach ($receipts as $receipt):
				?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $receipt['b']['bill_code']; ?></td>
							<td><?php echo $receipt['Receipt']['receipt_code']; ?></td>
							<td><?php echo $receipt['Receipt']['date_payment']; ?></td>
							<td><?php echo "$".$receipt[0]['total_payment']; ?></td>
							<td>
		                        <a href="<?php echo Router::url(array('controller'=>'receipts','action' => 'detailReceipt/'.$receipt['Receipt']['id'])); ?>" title="Ver detalle"><i class="fa fa-list action"></i></a>
							</td>
						</tr>
				<?php 		
						$i++;
						endforeach;
					} else {
				?>
					<tr>
						<td colspan="6" class="text-center">No existen pagos registrados</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php 
	/*Library Datepicker Bootstrap*/
	echo $this->Html->script('bootstrap-datepicker.min');
	echo $this->Html->script('bootstrap-datepicker.es.min');

	echo $this->Html->script('receipt-payment');
?>