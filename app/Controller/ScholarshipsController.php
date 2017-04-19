<?php  

	/**
	* 
	*/
	class ScholarshipsController extends AppController
	{

		var $uses = array('Scholarship','Student','Flash');
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
			$this->Scholarship->recursive = -1;
        	$scholarships = $this->Scholarship->find('all', array('conditions' => array('status !=' => 0),'order' => array('id' => 'DESC')));
        	$size = sizeof($scholarships);
        	$this->set('scholarships',$scholarships);
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
	            
    			$tupla = array('Scholarship' => array( "name" => $data['name'],
	                    "alias" => $data['alias'],
	                    "percentage" => $data['percentage'],
	                    "description" => $data['description'],
	                    "status" => 1,
	                    "date_created" => date('Y-m-d')));
				
				$this->Scholarship->create();

	            if ($this->Scholarship->save($tupla)) {
	            	$description = "Se ha creado el descuento: ".$data['name'];
                    $description .= " con el alias: ".$data['alias'];
                    $description .= " el porcentaje: ".$data['percentage'];
                    $description .= " descripción: ".$data['description'];
                    $description .= " y su id correspondiente es : ".$this->Scholarship->id;
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
			$this->Scholarship->recursive = -1;

	        if (!$id) throw new NotFoundException(__('Invalid scholarship'));

	        $scholarship = $this->Scholarship->findById($id);

	        if (!$scholarship) throw new NotFoundException(__('No se encontro el descuento'));

	        if ($this->request->is(array('post', 'put'))) {
	            $this->Scholarship->id = $id;
	            $data = $this->request->data;
	            $tupla = array ('Scholarship' => array( "name" => $data['name'],
	            									 "alias" => $data['alias'],
	                                                 "percentage" => $data['percentage'],
	                                                 "description" => $data['description'],
	                                                 "date_modification" => date('Y-m-d')));

	            if ($this->Scholarship->save($tupla)) {
	            	$description = "Se ha editado el descuento: ".$data['name'];
                    $description .= " con el alias: ".$data['alias'];
                    $description .= " el porcentaje: ".$data['percentage'];
                    $description .= " descripción: ".$data['description'];
                    $description .= " y su id correspondiente es : ".$id;
                    $log = array('Log'=>array(
                        'user_id' => $this->Auth->user('id'),
                        'description' => $description
                    ));
                    $this->SaveLog->saveData($log);

	                $this->Flash->success('Se guardo correctamente');
	                return $this->redirect(array('action' => 'index'));
	            }
	            $this->Flash->danger('No se actualizó los datos del descuento');
	        }

	        if (!$this->request->data) $this->set('scholarship', $scholarship);
		}

		public function delete(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$scholarship_id = $this->data['scholarship_id'];
             	$this->Scholarship->id = $scholarship_id;
             	$students = $this->Student->find('all',array('conditions' => array('scholarship_id' => $scholarship_id)));
             	$delete = true;
             	$scholarship = $this->Scholarship->findById($scholarship_id);
             	if (sizeof($students) > 0) $delete = false;
             	else {
             		$this->Scholarship->saveField('status',0);
             		$this->Scholarship->saveField('date_modification',date('Y-m-d'));

             		$description = "Se ha eliminado el descuento: ".$scholarship['Scholarship']['name'];
                    $description .= " con el alias: ".$scholarship['Scholarship']['alias'];
                    $description .= " el porcentaje: ".$scholarship['Scholarship']['percentage'];
                    $description .= " descripción: ".$scholarship['Scholarship']['description'];
                    $description .= " y su id correspondiente es : ".$scholarship_id;
                    $log = array('Log'=>array(
                        'user_id' => $this->Auth->user('id'),
                        'description' => $description
                    ));
                    $this->SaveLog->saveData($log);
             	}
   				echo json_encode($delete);
			}
		}

		public function getScholarship(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$scholarship_id = $this->data['scholarship_id'];
				$scholarship = $this->Scholarship->findById($scholarship_id);
				echo $scholarship['Scholarship']['percentage'];
			}
		}
	}