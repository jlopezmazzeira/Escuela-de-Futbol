<?php if ($role_id == 1): ?>
<div class="container">
	<div class="row">
		<div class="col-md-12 text-center title-section">
			<h1>Informaci&oacute;n de Cuentas</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="wrapper">
			  	<div class="block">
			    	<div class="number"><?php echo "$".$estimated_amount; ?></div>
			    	<div class="string">Monto estimado <?php echo $months[$month]; ?></div>
			  	</div>
			  	<div class="block">
			    	<div class="number"><?php echo "$".$cumulative_total; ?></div>
			    	<div class="string">Monto percibido <?php echo $months[$month]; ?></div>
			  	</div>
			  	<div class="block">
			    	<div class="number"><?php echo $accumulated_percentage."%"; ?></div>
			    	<div class="string">Porcentaje monto percibido <?php echo $months[$month]; ?></div>
			 	</div>
			  	<div class="block">
			    	<div class="number"><?php echo "$".$remaining_amount; ?></div>
			    	<div class="string">Monto restante <?php echo $months[$month]; ?></div>
			  	</div>
			  	<div class="block">
			    	<div class="number"><?php echo $remaining_amount_percentage."%"; ?></div>
			    	<div class="string">Porcentaje monto restante <?php echo $months[$month]; ?></div>
			  	</div>
			</div>
		</div>
	</div>
	<?php if ($size > 0): ?>
			<div class="row">
				<div class="col-md-12 text-center title-section">
					<h2>Informaci&oacute;n de Cuentas Meses Anteriores</h2>
				</div>
			</div>
			<?php foreach ($takings as $taking): ?>
					<div class="row">
						<div class="col-md-12">
							<div class="wrapper">
							  	<div class="block">
							    	<div class="number"><?php echo "$".$taking['Taking']['estimated_amount']; ?></div>
							    	<div class="string">Monto estimado <?php echo $months[$taking['Taking']['month']]; ?></div>
							  	</div>
							  	<div class="block">
							    	<div class="number"><?php echo "$".$taking['Taking']['cumulative_total']; ?></div>
							    	<div class="string">Monto percibido <?php echo $months[$taking['Taking']['month']]; ?></div>
							  	</div>
							  	<div class="block">
							    	<div class="number"><?php echo $taking['Taking']['accumulated_percentage']."%"; ?></div>
							    	<div class="string">Porcentaje monto percibido <?php echo $months[$taking['Taking']['month']]; ?></div>
							 	</div>
							  	<div class="block">
							    	<div class="number"><?php echo "$".$taking['Taking']['remaining_amount']; ?></div>
							    	<div class="string">Monto restante <?php echo $months[$taking['Taking']['month']]; ?></div>
							  	</div>
							  	<div class="block">
							    	<div class="number"><?php echo $taking['Taking']['remaining_amount_percentage']."%"; ?></div>
							    	<div class="string">Porcentaje monto restante <?php echo $months[$taking['Taking']['month']]; ?></div>
							  	</div>
							</div>
						</div>
					</div>
			<?php endforeach ?>
	<?php endif ?>
</div>
<?php endif ?>