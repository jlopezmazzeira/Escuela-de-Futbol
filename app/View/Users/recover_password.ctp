<div class="container-recover">
    <h1>Recuperar</h1>
    <span class="close"><i class="glyphicon glyphicon-remove"></i></span>

    <form id="forgot_password_form" action="<?php echo Router::url(array('controller'=>'users','action' => 'recoverPassword/'.$token.'/'.$user_id)); ?>" method="post">
      <div class="form-group">
        <div class="col-md-12 col-padding">
          <input type="password" id="password_update" name="password_update" placeholder="Contraseña" maxlength="80">
        </div>
      </div>
      <div class="form-group">
        <div class="col-md-12 col-padding">
          <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Contraseña" maxlength="80">
        </div>
      </div>
      <input type="submit" value="Cambiar contraseña">
  </form>
</div>
<?php
  echo $this->Html->script('bootstrapValidator'); 
  echo $this->Html->script('login'); 
?>