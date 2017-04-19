<?php  

	/**
	* 
	*/
	App::uses('Folder', 'Utility');
	class TransportsController extends AppController
	{
		var $uses = array('Transport','RoutesTransport','Student','DetailsBill','Flash');
		// Carga la plantilla llamada layout
        function beforeFilter(){
            parent::beforeFilter();
            $this->layout = 'layout';
        }

        public function carrierIncome(){
        	$this->Transport->recursive = -1;
        	$transports = $this->Transport->find('all', array('conditions' => array('status !=' => 0)));
        	$this->set('transports',$transports);
        }

        public function reportCarrierIncome(){
        	
			if($this->request->is('post')){
                $data_request = $this->request->data;
                $date_from = $data_request['date_from'];
                $date_until = $data_request['date_until'];
                $transport_id = $data_request['transport'];

                $data = "";
                $pending_payments = "";

                if ($date_from != "" && $date_until != "" && $transport_id != "") {
                	$data = $this->Transport->getCarrierIncomeByDatesAndTransport($transport_id,$date_from,$date_until);
                	$pending_payments = $this->Transport->getPendingPaymentsByDatesAndTransport($transport_id,$date_from,$date_until);
                } else if($date_from == "" && $date_until == "" && $transport_id != "") {
                	$data = $this->Transport->getCarrierIncomeByTransport($transport_id);
                	$pending_payments = $this->Transport->getPendingPaymentsByTransport($transport_id);
                } else if($date_from != "" && $date_until != "" && $transport_id == "") {
                	$data = $this->Transport->getCarrierIncomeByDates($date_from,$date_until);
                	$pending_payments = $this->Transport->getPendingPaymentsByDates($date_from,$date_until);
                }
                
                $transport = "";
                $total = 0;
                $pending = 0;
                $paid = 0;
                if ($transport_id != "") {
                	if (!empty($data)) {
                		foreach ($data as $transport) {
		                	$paid += $transport['db']['cost'];
		                	$total += $transport['db']['cost'];
		                }
                	}

                	if (!empty($pending_payments)) {
		                foreach ($pending_payments as $transport) {
		                	$pending += $transport['do']['cost'];
		                	$total += $transport['do']['cost'];
		                }
		            }

		            if (!empty($data) && empty($pending_payments)) {
		            	$transport = array('Transport' => array(
                				'name' => $data[0]['Transport']['name']." ".$data[0]['Transport']['lastname'],
                				'phone' => $data[0]['Transport']['phone'],
                				'movil' => $data[0]['Transport']['movil'],
                		));
		            } elseif (empty($data) && !empty($pending_payments)) {
		            	$transport = array('Transport' => array(
                				'name' => $pending_payments[0]['Transport']['name']." ".$data[0]['Transport']['lastname'],
                				'phone' => $pending_payments[0]['Transport']['phone'],
                				'movil' => $pending_payments[0]['Transport']['movil'],
                		));
		            } elseif (!empty($data) && !empty($pending_payments)) {
		            	$transport = array('Transport' => array(
                				'name' => $data[0]['Transport']['name']." ".$data[0]['Transport']['lastname'],
                				'phone' => $data[0]['Transport']['phone'],
	                			'movil' => $data[0]['Transport']['movil'],
	                	));
		            }
                }

                $retention = $total * 0.05;
                $retention = round($retention,2);
                $total = $total - $retention;
                $this->pdfConfig = array(
					'download' => true,
					'orientation' => 'landscape',
					'pageSize' => 'Tabloid',
					'filename' => 'reporte_ingresos_transportista.pdf'
				);

                $this->set('paid',round($paid,2));
                $this->set('pending',round($pending,2));
                $this->set('total',$total);
                $this->set('retention',$retention);
                $this->set('data',$data);
                $this->set('pending_payments',$pending_payments);
                $this->set('size_data',sizeof($data));
                $this->set('size_pending_payments',sizeof($pending_payments));
                $this->set('transport',$transport);
                $this->set('date_from',$date_from);
                $this->set('date_until',$date_until);
            }
        	
        }

		public function index(){
            if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			$this->Transport->recursive = -1;
        	$transports = $this->Transport->find('all', array('conditions' => array('status !=' => 0)));
        	
        	$route_license = Router::url('/app/webroot/files/transports/license/', true);
        	$route_permission = Router::url('/app/webroot/files/transports/permission/', true);
        	$route_enrollment = Router::url('/app/webroot/files/transports/enrollment/', true);

        	$size = sizeof($transports);
        	$this->set('transports',$transports);
        	$this->set('route_enrollment',$route_enrollment);
        	$this->set('route_permission',$route_permission);
        	$this->set('route_license',$route_license);
        	$this->set('size',$size);
		}

		public function add(){
            if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			if ($this->request->is('post')) {
	            $data = $this->request->data;  // Obtengo valores enviados desde mi vista
				
    			$tupla = array('Transport' => array( "name" => $data['name'],
	                    "lastname" => $data['lastname'],
	                    "phone" => $data['phone'],
	                    "movil" => $data['movil'],
	                    "license" => $data['File']['license']['name'],
	                    "enrollment" => $data['File']['enrollment']['name'],
	                    "permission" => $data['File']['permission']['name'],
	                    "status" => 1,
	                    "date_created" => date('Y-m-d')));
				
				$this->Transport->create();

	            if ($this->Transport->save($tupla)) {
	            	$transport_id = $this->Transport->id;
	                if (!empty($data['File']['license']['name'])) $this->_upload($data['File']['license'],$transport_id,'license');
	                if (!empty($data['File']['enrollment']['name'])) $this->_upload($data['File']['enrollment'],$transport_id,'enrollment');
	                if (!empty($data['File']['permission']['name'])) $this->_upload($data['File']['permission'],$transport_id,'permission');

	                $description = "Se ha creado el transportista: ".$data['name']." ".$data['lastname'];
                    $description .= " su móvil: ".$data['movil'];
                    $description .= " su teléfono: ".$data['phone'];
                    $description .= " y su id correspondiente es : ".$this->Transport->id;
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
			$this->Transport->recursive = -1;

	        if (!$id) throw new NotFoundException(__('Invalid transport'));

	        $transport = $this->Transport->findById($id);

	        if (!$transport) throw new NotFoundException(__('No se encontro el transporte'));

	        if ($this->request->is(array('post', 'put'))) {
	            $this->Transport->id = $id;
	            $data = $this->request->data;
	            $license = $transport['Transport']['license'];
	            $enrollment = $transport['Transport']['enrollment'];
	            $permission = $transport['Transport']['permission'];

	            if (!empty($data['File']['license']['name'])) $license = $data['File']['license']['name'];
	            if (!empty($data['File']['enrollment']['name'])) $enrollment = $data['File']['enrollment']['name'];
	            if (!empty($data['File']['permission']['name'])) $permission = $data['File']['permission']['name'];

	            $transport_edit = array ('Transport' => array( "name" => $data['name'],
	                                                 "lastname" => $data['lastname'],
	                    							 "phone" => $data['phone'],
	                    							 "movil" => $data['movil'],
	                    							 "license" => $license,
									                 "enrollment" => $enrollment,
									                 "permission" => $permission,
	                                                 "date_modification" => date('Y-m-d')));

	            if ($this->Transport->save($transport_edit)) {
	                if (!empty($data['File']['license']['name'])) $this->_upload($data['File']['license'],$id,'license');
		            if (!empty($data['File']['enrollment']['name'])) $this->_upload($data['File']['enrollment'],$id,'enrollment');
		            if (!empty($data['File']['permission']['name'])) $this->_upload($data['File']['permission'],$id,'permission');
	
		            $description = "Se ha editado el transportista: ".$data['name']." ".$data['lastname'];
                    $description .= " su móvil: ".$data['movil'];
                    $description .= " su teléfono: ".$data['phone'];
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

	        if (!$this->request->data) {
	            $this->set('transport', $transport);
	        }
		}

		public function delete(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$transport_id = $this->data['transport_id'];
             	$this->Transport->id = $transport_id;
            	$route_transport = $this->RoutesTransport->getRoutesTransportByIdTransport($transport_id);
            	$transport = $this->Transport->findById($transport_id);
            	$delete = true;
            	if (sizeof($route_transport) == 0) {
            		$this->Transport->saveField('status',0);
             		$this->Transport->saveField('date_modification',date('Y-m-d'));
             		$description = "Se ha eliminado el transportista: ".$transport['Transport']['name']." ".$transport['Transport']['lastname'];
                    $description .= " su móvil: ".$transport['Transport']['movil'];
                    $description .= " su teléfono: ".$transport['Transport']['phone'];
                    $description .= " y su id correspondiente es : ".$transport_id;
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
	             		$this->Transport->saveField('status',0);
	             		$this->Transport->saveField('date_modification',date('Y-m-d'));
	             		$description = "Se ha eliminado el transportista: ".$transport['Transport']['name']." ".$transport['Transport']['lastname'];
	                    $description .= " su móvil: ".$transport['Transport']['movil'];
	                    $description .= " su teléfono: ".$transport['Transport']['phone'];
	                    $description .= " y su id correspondiente es : ".$transport_id;
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

		public function _upload($name_file = array(),$transport_id,$document_type){
            $folder_name = $transport_id;
            $route = WWW_ROOT . 'files' . DS . 'transports' . DS . $document_type . DS . $folder_name;
            $dir = new Folder ($route, true, 0755);
            if (!is_null($dir->path)) {
			    $dir->delete();
			    $dir = new Folder ($route, true, 0755);
			}
            $filename = basename($name_file['name']);
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $filePath = $route . DS . $filename;
            move_uploaded_file($name_file['tmp_name'], $filePath);
        }

	}