<div class="login">
  <?php echo $this->Html->image('login-w-icon.png'); ?>
</div>
<div class="container-login">
	<h1>Iniciar Sesi&oacute;n</h1>
	<span class="close"><i class="glyphicon glyphicon-remove"></i></span>
	<form id="login_form" action="<?php echo Router::url(array('controller'=>'users','action' => 'login')); ?>" method="POST">
	    <input type="text" id="username" name="data[User][username]" placeholder="Nombre de usuario" required>
	    <input type="password" id="password" name="data[User][password]" placeholder="Contrase&ntilde;a" required>
	    <input type="submit" value="Ingresar">
	    <div id="remember-container">
	      <span id="forgotten">¿Olvid&oacute; Contrase&ntilde;a?</span>
	    </div>
	</form>
</div>

<div class="container-recover-password">
   	<h1>Recuperar</h1>
  	<span class="close"><i class="glyphicon glyphicon-remove"></i></span>
  	<form id="forgotPassword_form" action="<?php echo Router::url(array('controller'=>'users','action' => 'forgotPassword')); ?>" method="post">
    	<input type="email" id="email" name="email" placeholder="Por favor ingrese su correo..." required>
    	<input type="submit" value="Recuperar">
	</form>
</div>

<?php
	echo $this->Html->script('login'); 
	//echo $this->Recaptcha->display();
?>