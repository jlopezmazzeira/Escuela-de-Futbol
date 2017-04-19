<?php  

	/**
	* 
	*/
	class ControlsController extends AppController
	{
		
		var $uses = array('Control','DetailsControl','Student','Category','CategoriesEmployee','Flash');
		// Carga la plantilla llamada layout
        function beforeFilter(){
            parent::beforeFilter();
            $this->layout = 'layout';
        }

		public function index(){
			$user_id = $this->Auth->user('id');
			if ($this->Auth->user('role_id') != 3) {
				return $this->redirect(
		            array('controller' => 'home', 'action' => 'index')
		        );
			}
			$categories_employee = $this->CategoriesEmployee->getCategoriesByUser($user_id);
			$this->set('categories_employee',$categories_employee);
		}

		public function category(){
			$user_id = $this->Auth->user('id');
			$categories = $this->CategoriesEmployee->getCategoriesByUser($user_id);
			$this->set('categories',$categories);	
		}

		public function consult(){
			if ($this->Auth->user('role_id') != 3) {
				return $this->redirect(
		            array('controller' => 'home', 'action' => 'index')
		        );
			}
			$user_id = $this->Auth->user('id');
			$categories_employee = $this->CategoriesEmployee->getCategoriesByUser($user_id);
			$this->set('categories_employee',$categories_employee);
		}

		public function reportCategory(){
			if ($this->request->is('post')) {
        		$data = $this->request->data;
        		$date_from = $data['date_from'];
        		$date_until = $data['date_until'];
        		$category_id = $data['category'];
        		$category = $this->Category->find('first',array('conditions' => array('id' => $category_id)));
        		$teachers = $this->Category->getTeacherByCategory($category_id);

        		$teacher = "";
				if (empty($teachers)) {
					$teacher = "No tiene un profesor asignado";
				} else {
					if (sizeof($teachers) == 1)
						$teacher = $teachers[0]['p']['name']." ".$teachers[0]['p']['lastname'];
					else {
						$size = count($teachers);
						$last = $size - 1;
						for ($i=0; $i < count($teachers); $i++) {
                			if ($i == 0) $teacher .= $teachers[$i]['p']['name']." ".$teachers[$i]['p']['lastname']."-"; 
                			elseif ($i == $last) $teacher .= $teachers[$i]['p']['name']." ".$teachers[$i]['p']['lastname'];
                			else $teacher .= $teachers[$i]['p']['name']." ".$teachers[$i]['p']['lastname']."-";
                		}
					}
				}

				$user_id = $this->Auth->user('id');
				$category_employee = $this->CategoriesEmployee->find('first',array('conditions' => array(
											'category_id' => $category_id,
											'employee_id' => $user_id
										)));
        		
        		$assistance = $this->DetailsControl->getAssistanceByCategoryAndDate($category_employee['CategoriesEmployee']['id']
        										,$date_from,$date_until);
        		
        		$control = $this->Control->find('all',array('conditions' => array(
        											'category_employee_id' => $category_employee['CategoriesEmployee']['id'],
        											'date_control >=' => $date_from, 'date_control <=' => $date_until
        										)));
				$students = $this->Category->getStudentsByCategoryControl($category_id);
				$now = time();
				$to = new DateTime('today');

				for ($i=0; $i < count($students); $i++) { 
					$birthday = new DateTime($students[$i]['s']['birthday']);
                    $to = new DateTime('today');
                    $age = $birthday->diff($to)->y;

                    $formato = "EF";
                    $longitud_relleno = 4 - strlen($students[$i]['s']['id']);  //Calculo el numero de ceros a ser anadidos
                    $relleno = str_repeat("0",$longitud_relleno);  //Relleno de caracteres
                    $code_final = $formato.$relleno.$students[$i]['s']['id'];
                    $students[$i]['s']['code'] = $code_final;
                    $students[$i]['s']['age'] = $age;
				}

				$this->pdfConfig = array(
						'download' => true,
						'orientation' => 'landscape',
						'pageSize' => 'Tabloid',
						'filename' => 'reporte_asistencia_categoria.pdf'
					);

				$this->set('assistance',$assistance);
				$this->set('students',$students);
				$this->set('control',$control);
				$this->set('date_from',$date_from);
				$this->set('date_until',$date_until);
				$this->set('category',$category['Category']['name']);
				$this->set('teacher',$teacher);
        	}
		}

		public function student(){
			$user_id = $this->Auth->user('id');
			$categories = $this->CategoriesEmployee->getCategoriesByUser($user_id);
			$this->set('categories',$categories);
		}

		public function reportStudent(){
			if ($this->request->is('post')) {
        		$data = $this->request->data;
        		$date_from = $data['date_from'];
        		$date_until = $data['date_until'];
        		$student_id = $data['student'];
        		$this->Student->recursive = -1;
        		$student = $this->Student->find('first',array('conditions' => array('id' => $student_id)));
        		$category_id = $data['category'];
        		$category = $this->Category->find('first',array('conditions' => array('id' => $category_id)));
        		$teachers = $this->Category->getTeacherByCategory($category_id);
        		$teacher = "";
				if (empty($teachers)) $teacher = "No tiene un profesor asignado";
				else {
					if (sizeof($teachers) == 1) $teacher = $teachers[0]['p']['name']." ".$teachers[0]['p']['lastname'];
					else {
						$size = count($teachers);
						$last = $size - 1;
						for ($i=0; $i < count($teachers); $i++) {
                			if ($i == 0) $teacher .= $teachers[$i]['p']['name']." ".$teachers[$i]['p']['lastname']."-"; 
                			elseif ($i == $last) $teacher .= $teachers[$i]['p']['name']." ".$teachers[$i]['p']['lastname'];
                			else $teacher .= $teachers[$i]['p']['name']." ".$teachers[$i]['p']['lastname']."-";
                		}
					}
				}

        		$assistance = $this->DetailsControl->getAssistanceByStudentAndDate($student_id,$date_from,$date_until);
				$this->pdfConfig = array(
						'download' => true,
						'orientation' => 'landscape',
						'pageSize' => 'Tabloid',
						'filename' => 'reporte_asistencia_estudiante.pdf'
					);

				$this->set('assistance',$assistance);
				$this->set('date_from',$date_from);
				$this->set('date_until',$date_until);
				$this->set('category',$category['Category']['name']);
				$this->set('student',$student['Student']['name']." ".$student['Student']['lastname']);
				$this->set('teacher',$teacher);
        	}
		}

		public function check(){
			if ($this->Auth->user('role_id') != 3) {
				return $this->redirect(
		            array('controller' => 'home', 'action' => 'index')
		        );
			}
			$user_id = $this->Auth->user('id');
			$categories = $this->CategoriesEmployee->getCategoriesByUser($user_id);
			$this->set('categories',$categories);
		}

		public function printCheck(){
			if ($this->request->is('post')) {
        		$data = $this->request->data;
				$month = $data['month'];
				$category_id = $data['category'];
				$days = cal_days_in_month(CAL_GREGORIAN, $month, date('Y'));

				$students = $this->Category->getStudentsByCategoryControl($category_id);
				$category = $this->Category->find('first',array('conditions' => array('id' => $category_id)));
				$teachers = $this->Category->getTeacherByCategory($category_id);

				$teacher = "";
				if (empty($teachers)) $teacher = "No tiene un profesor asignado";
				else {
					if (sizeof($teachers) == 1) $teacher = $teachers[0]['p']['name']." ".$teachers[0]['p']['lastname'];
					else {
						$size = count($teachers);
						$last = $size - 1;
						for ($i=0; $i < count($teachers); $i++) {
                			if ($i == 0) $teacher .= $teachers[$i]['p']['name']." ".$teachers[$i]['p']['lastname']."-"; 
                			elseif ($i == $last) $teacher .= $teachers[$i]['p']['name']." ".$teachers[$i]['p']['lastname'];
                			else $teacher .= $teachers[$i]['p']['name']." ".$teachers[$i]['p']['lastname']."-";
                		}
					}
				}

				$now = time();
				$to = new DateTime('today');

				for ($i=0; $i < count($students); $i++) { 
					$birthday = new DateTime($students[$i]['s']['birthday']);
                    $to = new DateTime('today');
                    $age = $birthday->diff($to)->y;

                    $formato = "EF";
                    $longitud_relleno = 4 - strlen($students[$i]['s']['id']);  //Calculo el numero de ceros a ser anadidos
                    $relleno = str_repeat("0",$longitud_relleno);  //Relleno de caracteres
                    $code_final = $formato.$relleno.$students[$i]['s']['id'];
                    $students[$i]['s']['code'] = $code_final;
                    $students[$i]['s']['age'] = $age;

                    if ($students[$i]['s']['status'] == 1) $students[$i]['s']['status_str'] = "Activo";
                    else if ($students[$i]['s']['status'] == 2) $students[$i]['s']['status_str'] = "Deudor";
                    else if ($students[$i]['s']['status'] == 5) $students[$i]['s']['status_str'] = "Ausente";
                    
				}

				$months = ["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Juio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
				$this->pdfConfig = array(
						'download' => true,
						'orientation' => 'landscape',
						'pageSize' => 'Tabloid',
						'filename' => 'ficha_asistencia.pdf'
					);

				$this->set('days',$days);
				$this->set('month',$months[$month]);
				$this->set('category',$category['Category']['name']);
				$this->set('students',$students);
				$this->set('teacher',$teacher);
			}
		}

		public function getStudents(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$category_id = $this->data['category_id'];
				$this->Student->recursive = -1;

				$students = $this->Category->getStudentsByCategoryControl($category_id);
				$now = time();
				$to = new DateTime('today');

				for ($i=0; $i < count($students); $i++) { 
					$birthday = new DateTime($students[$i]['s']['birthday']);
                    $to = new DateTime('today');
                    $age = $birthday->diff($to)->y;

                    $formato = "EF";
                    $longitud_relleno = 4 - strlen($students[$i]['s']['id']);  //Calculo el numero de ceros a ser anadidos
                    $relleno = str_repeat("0",$longitud_relleno);  //Relleno de caracteres
                    $code_final = $formato.$relleno.$students[$i]['s']['id'];
                    $students[$i]['s']['code'] = $code_final;
                    $students[$i]['s']['age'] = $age;

                    if ($students[$i]['s']['status'] == 1) $students[$i]['s']['status_str'] = "Activo";
                    else if ($students[$i]['s']['status'] == 2) $students[$i]['s']['status_str'] = "Deudor";
                    else if ($students[$i]['s']['status'] == 5) $students[$i]['s']['status_str'] = "Ausente";
				}

				echo json_encode($students);
			}
		}

		public function add(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$category_id = $this->data['category_id'];
				$date_control = $this->data['date_control'];
				$students = $this->data['students'];
				$user_id = $this->Auth->user('id');
				$this->Control->create();
				$category_employee = $this->CategoriesEmployee->find('first',array('conditions' => array(
											'category_id' => $category_id,
											'employee_id' => $user_id
										)));
				$control = array('Control' => array(
						'category_employee_id' => $category_employee['CategoriesEmployee']['id'],
						'date_control' => $date_control 
					));

				$this->Control->save($control);
				$control_id = $this->Control->id;
				for ($i=0; $i < count($students); $i++) { 

					$this->DetailsControl->create();
					$detail = array('DetailsControl' => array(
								'control_id' => $control_id,
								'student_id' => $students[$i]['s']['id'],
								'assistance' => $students[$i]['s']['assistance'],
								'observation' => $students[$i]['s']['observation']
						));

					$this->DetailsControl->save($detail);
				}

				echo true;
			}
		}

		public function verifyAssistance(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax())  Configure::write('debug', 0);

			if(!empty($this->data)) {
				$category_id = $this->data['category_id'];
				$date_control = $this->data['date_control'];

				$assistance = false;
				$categories_employee_id = $this->CategoriesEmployee->find('all',
											array('conditions' => array('category_id' => $category_id)));

				foreach ($categories_employee_id as $category_employee) {
					$control = $this->Control->find('first', 
										array('conditions' => array(
												'category_employee_id' => $category_employee['CategoriesEmployee']['id'],
												'date_control' => $date_control
											)
											));
					if (!empty($control)) {
						$assistance = true;
						break;
					}
				}

				echo $assistance;
			}
		}

		public function getControl(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)) {
				$category_id = $this->data['category_id'];
				$date_control = $this->data['date_control'];
				$user_id = $this->Auth->user('id');
				$category_employee = $this->CategoriesEmployee->find('first',array('conditions' => array(
											'category_id' => $category_id,
											'employee_id' => $user_id
										)));
				$assistance = $this->DetailsControl->getAssistanceByCategoryAndDateControl($category_employee['CategoriesEmployee']['id']
																						,$date_control);

				for ($i=0; $i < count($assistance); $i++) {

					$birthday = new DateTime($assistance[$i]['s']['birthday']);
                    $to = new DateTime('today');
                    $age = $birthday->diff($to)->y;

                    $formato = "EF";
                    $longitud_relleno = 4 - strlen($assistance[$i]['s']['id']);  //Calculo el numero de ceros a ser anadidos
                    $relleno = str_repeat("0",$longitud_relleno);  //Relleno de caracteres
                    $code_final = $formato.$relleno.$assistance[$i]['s']['id'];
                    $assistance[$i]['s']['code'] = $code_final;
                    $assistance[$i]['s']['age'] = $age;
                    if ($assistance[$i]['s']['status'] == 1) $assistance[$i]['s']['status_str'] = "Activo";
                    else if ($assistance[$i]['s']['status'] == 2) $assistance[$i]['s']['status_str'] = "Deudor";
                    else if ($assistance[$i]['s']['status'] == 5) $assistance[$i]['s']['status_str'] = "Ausente";

				}

				echo json_encode($assistance);
			}
		}

		public function update(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$category_id = $this->data['category_id'];
				$date_control = $this->data['date_control'];
				$students = $this->data['students'];

				for ($i=0; $i < count($students); $i++) { 
					$this->DetailsControl->id = $students[$i]['DetailsControl']['id'];
					$this->DetailsControl->saveField('assistance',$students[$i]['DetailsControl']['assistance']);
					$this->DetailsControl->saveField('observation',$students[$i]['DetailsControl']['observation']);
				}

				echo true;
			}
		}
	}