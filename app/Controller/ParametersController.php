<?php  

	/**
	* 
	*/
	class ParametersController extends AppController
	{
		
		var $uses = array('Parameter','Flash');
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
			$this->Parameter->recursive = -1;
        	$parameters = $this->Parameter->find('all');
        	$size = sizeof($parameters);
        	$this->set('parameters',$parameters);
        	$this->set('size',$size);
		}

		public function edit($id = null){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			$this->Parameter->recursive = -1;

	        if (!$id) throw new NotFoundException(__('Invalid parameter'));

	        $parameter = $this->Parameter->findById($id);

	        if (!$parameter) throw new NotFoundException(__('No se encontro el parámetro'));

	        if ($this->request->is(array('post', 'put'))) {
	            $this->Parameter->id = $id;
	            $data = $this->request->data;
	            $tupla = array ('Parameter' => array( "name" => $data['name'],
	                                                 "alias" => $data['alias'],
	                                                 "value" => $data['value'],
	                                                 "description" => $data['description'],
	                                                 "date_modification" => date('Y-m-d')));

	            if ($this->Parameter->save($tupla)) {
	            	
	            	$description = "Se ha editado la el parametro: ".$data['name'];
                    $description .= " con el alias de: ".$data['alias'];
                    $description .= " con el valor de: ".$data['value'];
                    $description .= " con la descripcion: ".$data['description'];
                    $description .= " y su id correspondiente es : ".$id;
                    $log = array('Log'=>array(
                        'user_id' => $this->Auth->user('id'),
                        'description' => $description
                    ));
                    $this->SaveLog->saveData($log);

	                $this->Flash->success('Se guardo correctamente');
	                return $this->redirect(array('action' => 'index'));
	            }
	            $this->Flash->danger('No se actualizó los datos de la categoria');
	        }

	        if (!$this->request->data) $this->set('parameter', $parameter);
		}
	}