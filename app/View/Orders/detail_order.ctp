<div class="container">
	<div class="row">
		<div class="col-md-12 text-center title-section">
			<h1>Detalle Orden de Pago</h1>
			<span>
				<a href="<?php echo Router::url(array('controller'=>'orders','action' => 'index')); ?>" title="Regresar">
					<i class="fa fa-reply action" aria-hidden="true"></i>
				</a>
			</span>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-4">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4>Cliente</h4>
					</div>
					<div class="panel-body">
						<ul id="dataResponsable">
							<li>Nombre: <?php echo $pending_payment['p']['name']; ?></li>
							<li>Tipo Documento: <?php echo $pending_payment['p']['document_type']; ?></li>
							<li>N° Documento: <?php echo $pending_payment['p']['document_number']; ?></li>
							<li>Tel&eacute;fono: <?php echo $pending_payment['p']['phone']; ?></li>
							<li>Direcci&oacute;n: <?php echo $pending_payment['p']['address']; ?></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<pre>Detalles</pre>
				<table class="table table-bordered">
					<thead class="background-head-table">
						<tr>
							<td class="text-center"><h4>Estudiante</h4></td>
							<th class="text-center"><h4>Item</h4></th>
							<th class="text-center"><h4>Descripci&oacute;n</h4></th>
							<th class="text-center"><h4>Mes</h4></th>
							<th class="text-center"><h4>Precio unitario</h4></th>
							<th class="text-center"><h4>Cant</h4></th>
							<th class="text-center"><h4>Sub Total</h4></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($detail_pending as $detail): 
								//$sub_total = $detail['DetailsOrder']['cost'] * $detail['DetailsOrder']['quantity'];
								//$sub_total = round($sub_total,2);
						?>
								<tr>
									<td><?php echo $detail['s']['name']." ".$detail['s']['lastname']; ?></td>
									<td><?php echo $detail['DetailsOrder']['product']; ?></td>
									<td><?php echo $detail['DetailsOrder']['description']; ?></td>
									<td><?php echo $months[$detail['DetailsOrder']['month']]; ?></td>
									<td><?php echo $detail['DetailsOrder']['cost_item']; ?></td>
									<!--<td><?php echo $detail['DetailsOrder']['cost']; ?></td>-->
									<td><?php echo $detail['DetailsOrder']['quantity']; ?></td>
									<td><?php echo $detail['DetailsOrder']['sub_total_item']; ?></td>
									<!--<td><?php echo $sub_total; ?></td>-->
								</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<pre>Secci&oacute;n Totales</pre>
				<div class="row text-right">
					<div class="col-xs-3 col-xs-offset-7">
						<strong id="item-title-bill">
							<li>Sub Total</li>
							<?php if ($total_iva_zero != 0): ?>
									<li>Sub Total 0%</li>	
							<?php endif ?>
							<?php if ($iva != 0): ?>
								<li>IVA <?php echo $iva ?>%</li>
							<?php endif ?>
							<li>Total</li>
						</strong>
					</div>
					<div class="col-xs-2">
						<strong id="item-pay-bill">
							<li><?php echo $sub_total; ?></li>
							<?php if ($total_iva_zero != 0): ?>
									<li><?php echo $total_iva_zero; ?></li>	
							<?php endif ?>
							<?php if ($iva != 0): ?>
								<li><?php echo $total_iva; ?></li>
							<?php endif ?>
							<li><?php echo $pending_payment['Order']['pending_total']; ?></li>
						</strong>
					</div>
				</div>
			</div>
		</div>
		<div class="row element-margin-bottom">
			<div class="col-md-12">
				<pre>Observaci&oacute;n</pre>
				<textarea id="observation" name="observation" class="form-control" rows="4" cols="145">
					<?php echo trim($pending_payment['Order']['observation']); ?>
				</textarea>
			</div>
		</div>
	</div>
</div>