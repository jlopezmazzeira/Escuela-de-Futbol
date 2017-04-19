<?php  

	/**
	* 
	*/
	class CategoriesController extends AppController
	{

		var $uses = array('Category','Student','Flash');
		// Carga la plantilla llamada layout
        function beforeFilter(){
            parent::beforeFilter();
            $this->layout = 'layout';
        }

        public function category(){
        	$categories = $this->Category->find('all', array('conditions' => array('status !=' => 0),'order' => array('id' => 'ASC')));
        	$this->set('categories',$categories);
        }

        public function reportCategory(){
			if($this->request->is('post')){
                $data = $this->request->data;
                $categories = $this->Category->find('list', array('fields'=>array('name')));
                $select_fitness = false;
                $array_data = array();
                $category_id_fitness;

                foreach ($data as $category_id) {
                    foreach ($categories as $id => $name) {
                        if ($category_id == $id && $name == "Fitness") {

                            $select_fitness = true;
                            $category_id_fitness = $category_id;

                        } else if ($category_id == $id && $name != "Fitness") {
                            $teachers = $this->Category->getTeacherByCategory($category_id);
                            $students = $this->Category->getStudentsByCategory($category_id);

                            $category = $students[0]['Category']['name'];
                            $array_data[$category] = array("id" => $category);

                            for ($i=0; $i < count($teachers); $i++) {
                                if($teachers[$i]['p']['name'] != "")
                                    $array_data[$category]["teachers"][] = $teachers[$i]['p'];
                                else
                                    $array_data[$category]["teachers"] = "No posee profesor";
                            }

                            for ($i=0; $i < count($students); $i++) {
                                $array_data[$category]["students"][] = $students[$i]['s'];
                            }
                        }
                    }
                }

                if ($select_fitness) {

                    $array_data["Fitness"] = array("id" => $category_id_fitness);

                    $teachers = $this->Category->getTeacherByCategory($category_id);
                    for ($i=0; $i < count($teachers); $i++) {
                        if($teachers[$i]['p']['name'] != "")
                            $array_data["Fitness"]["teachers"][] = $teachers[$i]['p'];
                        else
                            $array_data["Fitness"]["teachers"] = "No posee profesor";
                    }

                    $students = $this->Student->find('all',array('conditions'=>array('Student.fitness' => 1)));
                    for ($i=0; $i < count($students); $i++) {
                        $array_data["Fitness"]["students"][] = array(
                                                                "id" => $students[$i]['Student']['id'],
                                                                "name" => $students[$i]['Student']['name'],
                                                                "lastname" => $students[$i]['Student']['lastname'],
                                                                "birthday" => $students[$i]['Student']['birthday']
                                                                );
                    }

                }

                $this->pdfConfig = array(
                    'download' => true,
                    'filename' => 'reporte_categorias.pdf'
                );

                $this->set('data_final',$array_data);
            }
        }

		public function index(){
            if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			$this->Category->recursive = -1;
        	$categories = $this->Category->find('all', array('conditions' => array('status !=' => 0),'order' => array('age_min' => 'ASC')));
        	$size = sizeof($categories);
        	$this->set('categories',$categories);
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

    			$category = array('Category' => array( "name" => $data['name'],
	                    "age_min" => $data['age_min'],
	                    "age_max" => $data['age_max'],
	                    "status" => 1,
	                    "date_created" => date('Y-m-d')));
				
				$this->Category->create();

	            if ($this->Category->save($category)) {
                    
                    $description = "Se ha creado la categoría: ".$data['name'];
                    $description .= " con la edad minima: ".$data['age_min'];
                    $description .= " con la edad maxima: ".$data['age_max'];
                    $description .= " y su id correspondiente es : ".$this->Category->id;
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
            $this->Category->recursive = -1;

	        if (!$id) throw new NotFoundException(__('Invalid category'));

	        $category = $this->Category->findById($id);

	        if (!$category) throw new NotFoundException(__('No se encontro la categoría'));

	        if ($this->request->is(array('post', 'put'))) {
	            $this->Category->id = $id;
	            $data = $this->request->data;
	            $category = array ('Category' => array( "name" => $data['name'],
	                                                 "age_min" => $data['age_min'],
	                                                 "age_max" => $data['age_max'],
	                                                 "date_modification" => date('Y-m-d')));

	            if ($this->Category->save($category)) {
                    
                    $description = "Se ha editado la categoría: ".$data['name'];
                    $description .= " con la edad minima: ".$data['age_min'];
                    $description .= " con la edad maxima: ".$data['age_max'];
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

	        if (!$this->request->data) $this->set('category', $category);
		}

		public function delete(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$category_id = $this->data['category_id'];
             	$this->Category->id = $category_id;
                $category = $this->Category->findById($category_id);
             	$students = $this->Student->find('all',array('conditions' => array('category_id' => $category_id)));
             	$delete = true;
             	if (sizeof($students) > 0) $delete = false;
             	else {
             		$this->Category->saveField('status',0);
             		$this->Category->saveField('date_modification',date('Y-m-d'));

                    $description = "Se ha eliminado la categoría: ".$category['Category']['name'];
                    $description .= " con la edad minima: ".$category['Category']['age_min'];
                    $description .= " con la edad maxima: ".$category['Category']['age_max'];
                    $description .= " y su id correspondiente es : ".$category_id;
                    $log = array('Log'=>array(
                        'user_id' => $this->Auth->user('id'),
                        'description' => $description
                    ));
                    $this->SaveLog->saveData($log);

             	}
   				echo json_encode($delete);
			}
		}
	}