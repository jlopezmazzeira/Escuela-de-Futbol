<?php  

	/**
	* 
	*/
	class CreditsController extends AppController
	{
		  var $uses = array('Credit','Flash');
		  // Carga la plantilla llamada layout
      function beforeFilter(){
        parent::beforeFilter();
        $this->layout = 'layout';
      }

      public function index(){
        if ($this->Auth->user('role_id') != 1) {
            return $this->redirect(
                array('controller' => 'home', 'action' => 'index')
            );
        }
        $credits = $this->Credit->getAllCreditsNotes();
        $size = sizeof($credits);

        $this->set('size',$size);
        $this->set('credits',$credits);
      }

        public function delete(){
        	$this->autoRender = false;
  			
    			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

    			if(!empty($this->data)){
    				$data = $this->data;
    				$credit_id = $data['credit_id'];
    				$this->Credit->id = $credit_id;
    				$this->Credit->delete($credit_id);
    			}
        }
	}