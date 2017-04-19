<div class="container">
	<div class="row">
		<div class="col-md-12 text-center title-section">
			<h1>Informaci&oacute;n de Par&aacute;metros</h1>
			<span>
				<a href="<?php echo Router::url(array('controller'=>'home','action' => 'index')); ?>" title="Regresas">
					<i class="fa fa-reply action" aria-hidden="true"></i>
				</a>
			</span>
		</div>
	</div>
	<div class="row">
		<div class="dashboard">
			<?php 
				if ($size > 0) {
					foreach ($parameters as $parameter):
			?>
						<div class="col-md-4">
					        <div class="widget events-widget">
					            <header class="widget-header">
					                <h2 class="widget-headline"><?php echo $parameter['Parameter']['name']; ?></h2>
					            </header>
					            <div class="widget-body">
					                <h3 class="widget-subHeadline"><b><?php echo $parameter['Parameter']['alias']; ?>: </b> <?php echo $parameter['Parameter']['value']; ?></h3>
					                <p class="widget-subHeadline"><?php echo $parameter['Parameter']['description']; ?></p>
					                <div class="widget-controls">
					                	<a href="<?php echo Router::url(array('controller'=>'parameters','action' => 'edit/'.$parameter['Parameter']['id'])); ?>" title="Editar"><i class="fa fa-pencil icon-bottom action" aria-hidden="true"></i></a>
					                </div>
					            </div>
					        </div>
						</div>
			<?php 		
					endforeach;
				}
			?>
		</div>
	</div>
</div>