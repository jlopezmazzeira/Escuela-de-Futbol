<?php  

	/**
	* 
	*/
	class RoutesController extends AppController
	{
		var $uses = array('Route','Transport','RoutesTransport','Student','Flash');
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
			$this->Route->recursive = -1;
        	$routes = $this->Route->find('all', array('conditions' => array('status !=' => 0)));
        	$size = sizeof($routes);
        	$this->set('routes',$routes);
        	$this->set('size',$size);
		}

		public function add(){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			if ($this->request->is('post')) {
	            $data = $this->request->data;

    			$tupla = array('Route' => array( "name" => $data['name'],
	                    "description" => $data['description'],
	                    "cost" => $data['cost'],
	                    "status" => 1,
	                    "date_created" => date('Y-m-d')));
				
				$this->Route->create();

	            if ($this->Route->save($tupla)) {
	            	$description = "Se ha creado la ruta: ".$data['name'];
                    $description .= " con la descripción: ".$data['description'];
                    $description .= " con un costo: ".$data['cost'];
                    $description .= " y su id correspondiente es : ".$this->Route->id;
                    $log = array('Log'=>array(
                        'user_id' => $this->Auth->user('id'),
                        'description' => $description
                    ));
                    $this->SaveLog->saveData($log);

	                $this->Flash->success('Se guardó correctamente');
	                return $this->redirect(array('action' => 'index'));
	            }
	            $this->Flash->danger('Se produjo un error intente nuevamente');
	        }
		}

		public function edit($id = null){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			$this->Route->recursive = -1;

	        if (!$id) throw new NotFoundException(__('Invalid route'));

	        $route = $this->Route->findById($id);

	        if (!$route) throw new NotFoundException(__('No se encontro la ruta'));

	        if ($this->request->is(array('post', 'put'))) {
	            $this->Route->id = $id;
	            $data = $this->request->data;
	            $tupla = array ('Route' => array( "name" => $data['name'],
	                                                 "description" => $data['description'],
	                                                 "cost" => $data['cost'],
	                                                 "date_modification" => date('Y-m-d')));

	            if ($this->Route->save($tupla)) {
	            	$description = "Se ha editado la ruta: ".$data['name'];
                    $description .= " con la descripción: ".$data['description'];
                    $description .= " con un costo: ".$data['cost'];
                    $description .= " y su id correspondiente es : ".$id;
                    $log = array('Log'=>array(
                        'user_id' => $this->Auth->user('id'),
                        'description' => $description
                    ));
                    $this->SaveLog->saveData($log);
	                $this->Flash->success('Se guardo correctamente');
	                return $this->redirect(array('action' => 'index'));
	            }
	            $this->Flash->danger('No se actualizó los datos de la ruta');
	        }

	        if (!$this->request->data) $this->set('route', $route);
		}

		public function delete(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$route_id = $this->data['route_id'];
             	$this->Route->id = $route_id;
            	$route_transport = $this->RoutesTransport->getRoutesTransportByIdRoute($route_id);
            	$route = $this->Route->findById($route_id);
            	$delete = true;
            	if (sizeof($route_transport) == 0) {
            		$this->Route->saveField('status',0);
             		$this->Route->saveField('date_modification',date('Y-m-d'));
             		$description = "Se ha eliminado la ruta: ".$route['Route']['name'];
                    $description .= " con la descripción: ".$route['Route']['description'];
                    $description .= " con un costo: ".$route['Route']['cost'];
                    $description .= " y su id correspondiente es : ".$route_id;
                    $log = array('Log'=>array(
                        'user_id' => $this->Auth->user('id'),
                        'description' => $description
                    ));
                    $this->SaveLog->saveData($log);
            	} else {
            		$route_transport_id = $route_transport['RoutesTransport']['id'];
            		$this->Student->recursive = -1;
            		$students = $this->Student->find('all',array('conditions' => array('routes_transport_id' => $route_transport_id)));
            		if (sizeof($students) > 0) $delete = false;
	             	else {
	             		$this->Route->saveField('status',0);
	             		$this->Route->saveField('date_modification',date('Y-m-d'));
	             		$description = "Se ha eliminado la ruta: ".$route['Route']['name'];
	                    $description .= " con la descripción: ".$route['Route']['description'];
	                    $description .= " con un costo: ".$route['Route']['cost'];
	                    $description .= " y su id correspondiente es : ".$route_id;
	                    $log = array('Log'=>array(
	                        'user_id' => $this->Auth->user('id'),
	                        'description' => $description
	                    ));
	                    $this->SaveLog->saveData($log);
	             		$this->RoutesTransport->delete($route_transport_id);
	             	}
            	}
             	
   				echo json_encode($delete);
			}
		}

		public function assignTransport($id = null){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			if (!$id) throw new NotFoundException(__('Invalid route'));

	        $route = $this->Route->findById($id);

	        if (!$route) throw new NotFoundException(__('No se encontro la ruta'));

			$this->Transport->recursive = 1;
	        $transports = $this->Transport->find('all');

	        if ($this->request->is('post')) {
	        	$selected_carriers = $this->request->data;

	        	$route_transports = $this->RoutesTransport->find('all', array('conditions' => array('route_id' => $id)));
	        	$size = sizeof($route_transports);
		        if($size == 0) {
		        	foreach ($selected_carriers as $transport_id){
		        		$this->RoutesTransport->create();
		        		$tupla = array('RoutesTransport' => array("route_id" => $id, "transport_id" => $transport_id));		
						$this->RoutesTransport->save($tupla);
		        	}
		        	$this->Flash->success('Se han asignado el/los transportista(s) a la ruta');
	                return $this->redirect(array('action' => 'index'));
		        } else {
		        	$this->RoutesTransport->recursive = -1;
		        	$transports = $this->RoutesTransport->find('all', array(
			            'conditions' => array('route_id' => $id),
			            'fields' => array('transport_id')
			        ));

		        	$transports_previous = array();
		        	foreach ($transports as $transport) {
		        		$transports_previous[] = $transport['RoutesTransport']['transport_id'];
		        	}

			        foreach ($selected_carriers as $transport_id){
			        	if (!in_array($transport_id, $transports_previous)) {
			        		$this->RoutesTransport->create();
		        			$tupla = array('RoutesTransport' => array("route_id" => $id, "transport_id" => $transport_id));		
							$this->RoutesTransport->save($tupla);
							$transports_previous[] = $transport_id;		
						}	
			        }


			        $routes_transport_delete = array();
			        foreach ($transports_previous as $transport_id){
			        	if (!in_array($transport_id, $selected_carriers)) {
			        		$route_transport = $this->RoutesTransport->find('first',array('conditions' => array('route_id' => $id, 'transport_id' => $transport_id)));
			        		$route_transport_id = $route_transport['RoutesTransport']['id'];

			        		$students = $this->Student->find('all',array('conditions' => array('routes_transport_id' => $route_transport_id)));
			        		if (empty($students)) {
			    				$this->RoutesTransport->id = $route_transport_id;
			    				$this->RoutesTransport->delete($route_transport_id);    			
			        		} else {
			        			$routes_transport_delete[] = $route_transport_id;
								$this->RoutesTransport->id = $route_transport_id;
			    				$this->RoutesTransport->delete($route_transport_id); 
			        		}
			        	}
			        }

			        if (!empty($routes_transport_delete)) {
			        	$route_transport_id_new = $this->RoutesTransport->find('first', array(
				            'conditions' => array('route_id' => $id),
				            'fields' => array('id'),
				            'limit' => 1
				        ));

			        	$route_transport_id_new = $route_transport_id_new['RoutesTransport']['id'];

			        	foreach ($routes_transport_delete as $route_transport_id) {
				        	$this->Student->updateAll(array("routes_transport_id"=>"'$route_transport_id_new'"),array("routes_transport_id" => $route_transport_id));
				        }
			        }
			        $this->Flash->success('Se han asignado el/los transportista(s) a la ruta');
	                return $this->redirect(array('action' => 'index'));
		        }
	        }

	        $this->RoutesTransport->recursive = -1;
	        $route_transports = $this->RoutesTransport->find('all', array(
	            'conditions' => array('route_id' => $id),
	            'fields'=>array('transport_id')
	        ));

	        $id_transports = array();
	        foreach ($route_transports as $temp):
	            $id_transports[] = $temp['RoutesTransport']['transport_id'];
	        endforeach;

	        $this->set('transports',$transports);
	        $this->set('id_transports', $id_transports);
	        $this->set('route_id',$id);

		}

		public function getTransports(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$route_id = $this->data['route_id'];
            	$route_transport = $this->RoutesTransport->getTransportsByIdRoute($route_id);
   				echo json_encode($route_transport);
			}	
		}

	}