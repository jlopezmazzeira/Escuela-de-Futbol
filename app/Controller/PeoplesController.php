<?php  

	/**
	* 
	*/
	class PeoplesController extends AppController
	{
		var $uses = array('People','TypesProvider','TypesAccounting','Flash');
		// Carga la plantilla llamada layout
        function beforeFilter(){
            parent::beforeFilter();
            $this->layout = 'layout';
        }

        public function provider(){
        	$this->People->recursive = -1;
        	$providers = $this->People->find('all', array('conditions' => array('status !=' => 0, 'role_id' => 4)));
        	$this->set('providers',$providers);
        }

		public function clients(){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			$this->People->recursive = -1;
        	$clients = $this->People->find('all', array('conditions' => array('status !=' => 0, 'role_id' => 2)));
        	$size = sizeof($clients);
        	$this->set('clients',$clients);
        	$this->set('size',$size);
		}

		public function providers(){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			$this->People->recursive = -1;
        	$providers = $this->People->find('all', array('conditions' => array('status !=' => 0, 'role_id' => 4)));
        	$size = sizeof($providers);
        	$this->set('providers',$providers);
        	$this->set('size',$size);	
		}

		public function addClient(){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			if ($this->request->is('post')) {
	            $data = $this->request->data;  // Obtengo valores enviados desde mi vista

    			$tupla = array('People' => array( "role_id" => 2,
    					"name" => $data['name'],
	                    "document_number" => $data['document_number'],
	                    "document_type" => $data['document_type'],
	                    "phone" => $data['phone'],
	                    "address" => $data['address'],
	                    "email" => $data['email'],
	                    "status" => 1,
	                    "date_created" => date('Y-m-d')));
				
				$this->People->create();

	            if ($this->People->save($tupla)) {
	                $this->Flash->success('Se guardó correctamente');
	                return $this->redirect(array('action' => 'clients'));
	            }
	            $this->Flash->danger('Se produjo un error intente nuevamente');
	        }
		}

		public function editClient($id = null){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			$this->People->recursive = -1;

	        if (!$id) throw new NotFoundException(__('Invalid client'));

	        $client = $this->People->findById($id);

	        if (!$client) throw new NotFoundException(__('No se encontro el client'));

	        if ($this->request->is(array('post', 'put'))) {
	            $this->People->id = $id;
	            $temp = $this->request->data;
	            $tupla = array ('People' => array( "name" => $temp['name'],
	                                               "document_number" => $temp['document_number'],
	                    							"document_type" => $temp['document_type'],
	                    							"phone" => $temp['phone'],
	                    							"address" => $temp['address'],
	                    							"email" => $temp['email'],
	                                                "date_modification" => date('Y-m-d')));

	            if ($this->People->save($tupla)) {
	                $this->Flash->success('Se guardo correctamente');
	                return $this->redirect(array('action' => 'clients'));
	            }
	            $this->Flash->danger('No se actualizó los datos del producto');
	        }

	        if (!$this->request->data) $this->set('client', $client);
		}

		public function addProvider(){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			if ($this->request->is('post')) {
	            $data = $this->request->data;  // Obtengo valores enviados desde mi vista

    			$tupla = array('People' => array( "role_id" => 4,
    					"name" => $data['name'],
	                    "document_number" => $data['document_number'],
	                    "document_type" => "RUC",
	                    "phone" => $data['phone'],
	                    "type_accounting_id" => $data['type_accounting'],
	                    "type_provider_id" => $data['type_provider'],
	                    "address" => $data['address'],
	                    "email" => $data['email'],
	                    "status" => 1,
	                    "date_created" => date('Y-m-d')));
				
				$this->People->create();

	            if ($this->People->save($tupla)) {
	                $this->Flash->success('Se guardó correctamente');
	                return $this->redirect(array('action' => 'providers'));
	            }
	            $this->Flash->danger('Se produjo un error intente nuevamente');
	        }

	        $types_providers = $this->TypesProvider->find('all');
	        $types_accounting = $this->TypesAccounting->find('all');
	        $this->set('types_providers',$types_providers);
	        $this->set('types_accounting',$types_accounting);
		}
		
		public function editProvider($id = null){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			$this->People->recursive = -1;

	        if (!$id) throw new NotFoundException(__('Invalid provider'));

	        $provider = $this->People->findById($id);

	        if (!$provider) throw new NotFoundException(__('No se encontro el proveedor'));

	        if ($this->request->is(array('post', 'put'))) {
	            $this->People->id = $id;
	            $temp = $this->request->data;
	            $tupla = array ('People' => array( "name" => $temp['name'],
	                                               "document_number" => $temp['document_number'],
	                    							"phone" => $temp['phone'],
	                    							"type_accounting_id" => $temp['type_accounting'],
	                    							"type_provider_id" => $temp['type_provider'],
	                    							"address" => $temp['address'],
	                    							"email" => $temp['email'],
	                                                "date_modification" => date('Y-m-d')));

	            if ($this->People->save($tupla)) {
	                $this->Flash->success('Se guardo correctamente');
	                return $this->redirect(array('action' => 'providers'));
	            }
	            $this->Flash->danger('No se actualizó los datos del producto');
	        }

	        if (!$this->request->data) {
	            $this->set('provider', $provider);
	            $types_providers = $this->TypesProvider->find('all');
		        $types_accounting = $this->TypesAccounting->find('all');
		        $this->set('types_providers',$types_providers);
		        $this->set('types_accounting',$types_accounting);
	        }
		}

		public function delete(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
             	$this->People->id = $this->data['people_id'];
             	$this->People->saveField('status',0);
             	$this->People->saveField('date_modification',date('Y-m-d'));
   				echo json_encode($this->data['people_id']);
			}
		}

	}