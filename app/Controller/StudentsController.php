<?php  

	/**
	* 
	*/
	App::uses('Folder', 'Utility');
	class StudentsController extends AppController
	{
		var $uses = array('Student','Gender','TrainingMode','Category','People','Scholarship',
						  'Route','RoutesTransport','Initiator','Bill','DetailsBill','Parameter',
						  'Order','Credit','DisabledsStudent','DetailsOrder','DetailsControl','Flash');
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
			$this->Student->recursive = -1;

        	$students = $this->Student->getStudents();
        	$active_students = $this->Student->getStudentsByStatus(1);
        	$debtor_students = $this->Student->getStudentsByStatus(2);
        	$disabled_students = $this->Student->getStudentsByStatus(3);
        	$inactive_students = $this->Student->getStudentsByStatus(4);
        	$absent_students = $this->Student->getStudentsByStatus(5);
        	$new_students = $this->Student->getStudentsNews();
        	$size = sizeof($students);
        	$total_students = sizeof($students) - sizeof($inactive_students);
        	$total_students_to_renew = 0;
	        $now = time();
	        
	        $date_maxim = $this->Parameter->getValueParameter(9);
	        $date_maxim = trim($date_maxim);
	        $day = date('d');
	        $validator = $this->Initiator->find('first',array('conditions' => array('id' => 2)));
	        $validator = $validator['Initiator']['value'];
	        
	        if(($day+1) == $date_maxim && $validator == 0) $this->_generateOrders();
	        elseif($day > $date_maxim && $validator == 1) {
	            $this->Initiator->id = 2;
	            $this->Initiator->saveField('value',0);
	        }

	        foreach($students as $student):

	            $date_inscription = strtotime($student['Student']['date_inscription']);
	            $date_diff = $now - $date_inscription;
	            $days = floor($date_diff / (60 * 60 * 24));
	            if($days > 350 && $student['Student']['status'] == 1) $total_students_to_renew++;

	        endforeach;

	        $months = ["CERO","Enero","Febrero","Marzo","Abril","Mayo","Junio","Juio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
			$month = date('m');
			$month = intval($month);

        	$this->set('students',$students);
        	$this->set('size',$size);
        	$this->set('active_students',sizeof($active_students));
        	$this->set('disabled_students',sizeof($disabled_students));
        	$this->set('debtor_students',sizeof($debtor_students));
        	$this->set('inactive_students',sizeof($inactive_students));
        	$this->set('absent_students',sizeof($absent_students));
        	$this->set('new_students',sizeof($new_students));
        	$this->set('total_students',$total_students);
        	$this->set('month',$months[$month]);
        	$this->set('total_students_to_renew',$total_students_to_renew);
		}

		public function add(){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			$genders = $this->Gender->find('all');
			$this->TrainingMode->recursive = -1;
			$training_modes = $this->TrainingMode->find('all',array('conditions' => array('id !=' => 1)));
			$this->Category->recursive = -1;
			$categories = $this->Category->find('all',array('conditions' => array('status !=' => 0)));
			$this->Scholarship->recursive = -1;
			$scholarships = $this->Scholarship->find('all',array('conditions' => array('status !=' => 0)));
			$this->Route->recursive = -1;
			$routes = $this->Route->find('all',array('conditions' => array('status !=' => 0)));
			$iva = $this->Parameter->getValueParameter(1);
			$iva = round($iva,2);
			$this->set('genders',$genders);
			$this->set('training_modes',$training_modes);
			$this->set('categories',$categories);
			$this->set('scholarships',$scholarships);
			$this->set('routes',$routes);
			$this->set('iva',$iva);
		}

		public function addStudentAjax(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$data = $this->data;
					
				$responsable = array('Responsable' => array(
					"name" => $data['responsable_name'],
					"document_number" => $data['document_number'],
					"document_type" => $data['document_type'],
					"address" => $data['responsable_address'],
					"phone" => $data['responsable_phone'],
					"date_created" => date('Y-m-d')));
				
				$routes_transport_id = "";
				if($data['transport'] != "" && $data['routes'] != "")
					$routes_transport_id = $this->_getRouteTransport($data['routes'],$data['transport']);
				
				$student = array('Student' => array( 
						"document_number" => $data['document_number'],
						"name" => $data['name'],
						"lastname" => $data['lastname'],		
	                    "gender_id" => $data['gender'],
	                    "birthday" => $data['birthday'],
	                    "email" => $data['email'],
	                    "alternative_email" => $data['alternative_email'],
	                    "phone" => $data['phone'],
	                    "home_phone" => $data['home_phone'],
	                    "address" => $data['address'],
	                    "responsable" => $data['responsable'],
	                    "relation" => $data['relation'],
	                    "training_mode_id" => $data['training_mode'],
	                    "category_id" => $data['category'],
	                    "fitness" => $data['fitness_s'],
	                    "extra_training" => $data['extra_training_s'],
	                    "routes_transport_id" => $routes_transport_id,
	                    "scholarship_id" => $data['scholarship'],
	                    "date_inscription" => $data['date_inscription'],
	                    "date_transport" => $data['date_transport'],
	                    "exoneration" => $data['exoneration']));
				
				$date_explode = explode("-", $data['date_inscription']);
				$cost_pension = 0;

				$months = ["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Juio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
				$items = array();
				$IVA = $this->Parameter->getValueParameter(1);

				$cost_inscription = $this->CalculateAmount->calculateAmountInscription();
				
				$new_date = strtotime ('+1 year', strtotime($data['date_inscription']));
				$new_date = date ('Y-m-j', $new_date );
				$new_date_explode = explode("-", $new_date); 
				$period = $date_explode[0]." - ".$new_date_explode[0];

				$message = "Matrícula periodo ". $period.".\n ";
				$item = array('Product' => array('name' => 'Matricula','cost' => $cost_inscription, 'status' => 'pending', 'paid' => 0, 
								'message' => $message, 'month' => 0, 'month_str' => '','iva' => $IVA, 
								'exoneration' => $data['exoneration'], 'scholarship' => 0, 'year' => $date_explode[0])); 
				array_push($items, $item);
				
				if(!empty($data['training_mode'])){
					$parameter_id = ($data['training_mode'] == "2") ? 6 : 3;
					$month_str = intval($date_explode[1]);
					$cost = $this->CalculateAmount->calculateAmountItem($parameter_id,$date_explode[2]);
					
					$scholarship = 0;
					
					if(!empty($data['scholarship'])){
						$scholarship = $this->Scholarship->getValueScholarship($data['scholarship']);
						$scholarship = $cost * ($scholarship / 100);
						$scholarship = round($scholarship,2);
					} else {
						//Discount by siblings
						$siblings = $this->Student->getSiblingsActive($data['document_number']);
						
						$multiplier = 1;
						if ($siblings == 1)	$multiplier = 0.9;
						else if($siblings > 1) $multiplier = 0.8;
						
						$cost = $cost * $multiplier;
					}

					$cost = round($cost,2);

					$message = "Pensión desde fecha ". $data['date_inscription'].".\n ";
					$item = array('Product' => array('name' => 'Pension','cost' => $cost, 'status' => 'pending', 'paid' => 0, 
								'message' => $message, 'month' => $date_explode[1], 'month_str' => $months[$month_str], 
								'iva' => $IVA, 'scholarship' => $scholarship, 'year' => $date_explode[0]));
					array_push($items, $item);
				}
				
				if (!empty($routes_transport_id)) {
					$date_explode = explode("-", $data['date_transport']);
					$month_str = intval($date_explode[1]);
					$cost = $this->CalculateAmount->calculateAmountTransport($routes_transport_id,$date_explode[2]);
					$message = "Transporte desde fecha " . $data['date_transport'].".\n ";
					$item = array('Product' => array('name' => 'Transporte','cost' => $cost, 'status' => 'pending', 'paid' => 0, 
								'message' => $message, 'month' => $date_explode[1], 'month_str' => $months[$month_str], 
								'iva' => 0,'scholarship' => 0, 'year' => $date_explode[0]));
					array_push($items, $item);

				}

				$name_category = $this->Category->getNameCategory($data['category']);
				
				if ($data['fitness_s'] == 1 || $name_category == "FITNESS") {
					$scholarship = 0;
					$cost =	$this->CalculateAmount->calculateAmountItem(5,$date_explode[2]);
					if($name_category == "FITNESS" && !empty($data['scholarship'])){
						$scholarship = $this->Scholarship->getValueScholarship($data['scholarship']);
						$scholarship = $cost * ($scholarship / 100);
					}
					$cost = round($cost,2);
					$month_str = intval($date_explode[1]);
					$message = "Valor de la modalidad fitness desde fecha ". $data['date_inscription'].".\n ";
					$item = array('Product' => array('name' => 'Fitness','cost' => $cost, 'status' => 'pending', 'paid' => 0, 
								'message' => $message, 'month' => $date_explode[1], 'month_str' => $months[$month_str], 
								'iva' => $IVA,'scholarship' => $scholarship, 'year' => $date_explode[0]));
					array_push($items, $item);

				}

				if ($data['extra_training_s'] == 1) {
					$cost = $this->CalculateAmount->calculateAmountItem(7,$date_explode[2]);
					$month_str = intval($date_explode[1]);
					$message = "Valor de la modalidad extra training desde fecha ". $data['date_inscription'].".\n ";
					$item = array('Product' => array('name' => 'Extra Training','cost' => $cost, 'status' => 'pending', 'paid' => 0, 
								'message' => $message, 'month' => $date_explode[1], 'month_str' => $months[$month_str], 
								'iva' => $IVA,'scholarship' => 0, 'year' => $date_explode[0]));
					array_push($items, $item);
				}

				$data_new = array();
				$data_new['Student'] = $student['Student'];
				$data_new['Responsable'] = $responsable['Responsable'];
				$data_new['Items'] = $items;
				echo json_encode($data_new);
			}
		}

		public function edit($id = null){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			if (!$id) throw new NotFoundException(__('Invalid student'));

	        $this->Student->recursive = -1;
	        $student = $this->Student->findById($id);

	        if (!$student) throw new NotFoundException(__('No se encontro el estudiante'));

			$this->Student->id = $id;
	        $responsable = $this->People->find('first',array('conditions' => array('document_number' => $student['Student']['document_number'], 'role_id' => 5)));

			$this->set('student',$student);
			$this->set('responsable',$responsable);
			$genders = $this->Gender->find('all');
			$this->TrainingMode->recursive = -1;
			$training_modes = $this->TrainingMode->find('all',array('conditions' => array('id !=' => 1)));
			$this->Category->recursive = -1;
			$categories = $this->Category->find('all',array('conditions' => array('status !=' => 0)));
			$this->Scholarship->recursive = -1;
			$scholarships = $this->Scholarship->find('all',array('conditions' => array('status !=' => 0)));
			$this->Route->recursive = -1;
			$routes = $this->Route->find('all',array('conditions' => array('status !=' => 0)));
			$route_id = (empty($student['Student']['routes_transport_id'])) ? "" : $this->RoutesTransport->getRouteByIdRouteTransport($student['Student']['routes_transport_id']);
			$transport_id = (empty($student['Student']['routes_transport_id'])) ? "" : $this->RoutesTransport->getTransportByIdRouteTransport($student['Student']['routes_transport_id']);
			$transports = (empty($student['Student']['routes_transport_id'])) ? "" : $this->RoutesTransport->getTransportsByIdRouteTransport($student['Student']['routes_transport_id']);
			$this->set('genders',$genders);
			$this->set('training_modes',$training_modes);
			$this->set('categories',$categories);
			$this->set('scholarships',$scholarships);
			$this->set('routes',$routes);
			$this->set('route_id',$route_id);
			$this->set('transports',$transports);
			$this->set('transport_id',$transport_id);
		}

		public function editStudent(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);
  			if(!empty($this->data)){
				$data = $this->data;
				$this->Student->recursive = -1;
				$student_old = $this->Student->findById($data['student_id']);

				$previous_document_number = $student_old['Student']['document_number'];
				$responsable = $this->People->find('first',array('conditions' => array('document_number' => $data['document_number'], 'role_id' => 5)));
				$responsable_id;

				if (empty($responsable)) {
					$responsable = array('People' => array(
									"name" => $data['responsable_name'],
									"document_number" => $data['document_number'],
									"document_type" => $data['document_type'],
									"address" => $data['responsable_address'],
									"phone" => $data['responsable_phone'],
									"date_created" => date('Y-m-d')));
					$this->People->create();
					$this->People->save($responsable);
					$responsable_id = $this->People->id;
				} else  {
					$responsable_id = $responsable['People']['id'];
					$this->People->id = $responsable_id;
					$responsable = array('People' => array(
									"name" => $data['responsable_name'],
									"document_number" => $data['document_number'],
									"document_type" => $data['document_type'],
									"address" => $data['responsable_address'],
									"phone" => $data['responsable_phone'],
									"date_modification" => date('Y-m-d')));
					$this->People->save($responsable);
				}
				
				$routes_transport_id = "";
				if(!empty($data['transport']) && !empty($data['routes']))
					$routes_transport_id = $this->_getRouteTransport($data['routes'],$data['transport']);

				$student = array('Student' => array(
	            		"responsable_id" => $responsable_id,
						"document_number" => $data['document_number'],
						"name" => $data['name'],
						"lastname" => $data['lastname'],		
	                    "gender_id" => $data['gender'],
	                    "birthday" => $data['birthday'],
	                    "email" => $data['email'],
	                    "alternative_email" => $data['alternative_email'],
	                    "phone" => $data['phone'],
	                    "home_phone" => $data['home_phone'],
	                    "address" => $data['address'],
	                    "responsable" => $data['responsable'],
	                    "relation" => $data['relation'],
	                    "training_mode_id" => $data['training_mode'],
	                    "category_id" => $data['category'],
	                    "fitness" => $data['fitness'],
	                    "extra_training" => $data['extra_training'],
	                    "routes_transport_id" => $routes_transport_id,
	                    "scholarship_id" => $data['scholarship'],
	                    "date_transport" => $data['date_transport'],
	                    "date_modification" => date('Y-m-d')));
				
				$bill_id = 0;
				$cost_transport = 0;
				if (!empty($routes_transport_id) && is_null($student_old['Student']['routes_transport_id'])) {
					$date_explode = explode('-', $data['date_transport']);
					$cost_transport = $this->CalculateAmount->calculateAmountTransport($routes_transport_id,$date_explode[2]);
				}

				$cost_extra_training = 0;
				$date_explode = explode('-', date('Y-m-d'));
				if ($student_old['Student']['extra_training'] == 0 && $data['extra_training'] == 1)
					$cost_extra_training = $this->CalculateAmount->calculateAmountItem(7,$date_explode[2]);

				$cost_fitness = 0;
				if ($student_old['Student']['fitness'] == 0 && $data['fitness'] == 1)
					$cost_fitness = $this->CalculateAmount->calculateAmountItem(5,$date_explode[2]);

				$this->Student->id = $data['student_id'];
				$this->Student->save($student);

				$siblings = $this->Student->getSiblings($data['document_number'],$data['student_id']);
				//Update Siblings
				$this->Student->updateAll(array("siblings"=>"'$siblings'"),array("Student.document_number" => $data['document_number']));
                if($data['document_number'] != $previous_document_number){
                	$students = $this->Student->getStudentsByDocumentNumber($previous_document_number);
                	$siblings = $students - 1;
                	$this->Student->updateAll(array("siblings"=>"'$siblings'"),array("Student.document_number" => $previous_document_number));

                }

                if ($cost_extra_training != 0 || $cost_fitness != 0 || $cost_transport != 0) {
					$IVA = $this->Parameter->getValueParameter(1);
					//$sub_total = $cost_extra_training + $cost_fitness;
					//$sub_total_iva = $sub_total * ($IVA / 100);
					//$total = $sub_total + $sub_total_iva + $cost_transport;
					//$total = round($total,2);
					$total = $cost_extra_training + $cost_fitness + $cost_transport;
					$bill_code = $this->BillData->generateBillCode();
					$observation = "Se ha generado la factura por motivos de suscripciones de nuevos items \n";
					$bill_id = $this->BillData->saveBill($responsable_id,$bill_code,1,date('Y-m-d'),$total,0,$observation,1);
					$this->BillData->savePayment($bill_id,2,$total);

					if ($cost_extra_training != 0) {
						$product = "Extra Training";
						$description = "Pago de extra training";
						$date_explode = explode('-', date('Y-m-d'));
						$transport_id = null;
						$this->BillData->saveDetailBill($bill_id,$data['student_id'],$product,$description,
							$cost_extra_training,0,0,1,$date_explode[1],$date_explode[0],$transport_id, $IVA);
					}

					if ($cost_fitness != 0) {
						$product = "Fitness";
						$description = "Pago de fitness";
						$date_explode = explode('-', date('Y-m-d'));
						$transport_id = null;
						$this->BillData->saveDetailBill($bill_id,$data['student_id'],$product,$description,
							$cost_fitness,0,0,1,$date_explode[1],$date_explode[0],$transport_id, $IVA);
					}

					if ($cost_transport != 0) {
						$product = "Transporte";
						$description = "Pago de transporte";
						$date_explode = explode('-', $data['date_transport']);
						$route = $this->Route->getRoute($routes_transport_id);
						$transport_id = $route['t']['id'];

						$this->BillData->saveDetailBill($bill_id,$data['student_id'],$product,$description,
							$cost_transport,0,0,1,$date_explode[1],$date_explode[0],$transport_id, 0);
					}

					$this->BillData->updateCodeInitiator(1);

				}

				echo $bill_id;
			}
		}

		public function view($id = null){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			if (!$id) throw new NotFoundException(__('Invalid student'));

	        $student = $this->Student->findById($id);

	        if (!$student) throw new NotFoundException(__('No se encontro el estudiante'));

			$this->Student->recursive = -1;
	        $this->Student->id = $id;

	        $assistance = $this->DetailsControl->getAbsentByStudent($id);
	        
	        $student = $this->Student->getStudentById($id);
	        $status = $student['Student']['status'];
		    $description_status = "";
		    if($status == 1) $description_status = "Activo";
		    else if($status == 2) $description_status = "Deudor";
		    else if($status == 3) $description_status = "Inhabilitado";
		    else if($status == 4) $description_status = "Inactivo";
		    else if($status == 5) $description_status = "Inactivo";

		    $multiplier = 1;

	        $schorlarship = 0;
	        $siblings = 0;
	        if(!empty($student['Student']['scholarship_id']))
	        	$schorlarship = $this->Scholarship->getValueScholarship($student['Student']['scholarship_id']);
	        else {
	        	$siblings = $this->Student->getSiblings($student['Student']['document_number'], $id);
		        if($siblings == 1) $multiplier = 0.9;
		        else if ($siblings > 1) $multiplier = 0.8;		
	        }

	        $transport = 0;
	        $route = "";
	        if(!empty($student['Student']['routes_transport_id'])){
	        	$this->Route->recursive = -1;
	        	$route_transport = $this->Route->getRoute($student['Student']['routes_transport_id']);
	        	$transport = $route_transport['Route']['cost'];
	        	$route = $route_transport['Route']['name'];
	        }

	        $sub_total = 0;
	        $total_payment = 0;
	        $monthly_payment = 0;
	        $message_rate = "";

	        $IVA = $this->Parameter->getValueParameter(1);

	        $fitness = ($student['Student']['fitness'] != 0) ? $this->Parameter->getValueParameter(5) : 0;
    		//$fitness = $fitness + ($fitness * $IVA / 100);
    		$fitness = round($fitness,2);
	        
	        $extra_training = ($student['Student']['extra_training'] != 0) ? $this->Parameter->getValueParameter(7) : 0;
	        //$extra_training = $extra_training + ($extra_training * $IVA / 100);
    		$extra_training = round($extra_training,2);

	        if ($student['Student']['training_mode_id'] == 2) {
	        	$once_week = $this->Parameter->getValueParameter(6);
	        	$message_rate = "Una vez por semana + IVA";
	        	$monthly_payment = $once_week;
	    		$sub_total = $this->CalculateAmount->calculateRate($once_week,$multiplier,$schorlarship);
	    		//$sub_total = $sub_total + (($sub_total * $IVA) / 100);
	    		$sub_total = round($sub_total,2);	
	        } else if($student['Student']['training_mode_id'] == 3){
	        	$monthly_rate = $this->Parameter->getValueParameter(3);
	        	$monthly_payment = $monthly_rate;
	        	$message_rate = "Tarifa + IVA";
	        	$sub_total = $this->CalculateAmount->calculateRate($monthly_rate,$multiplier,$schorlarship);
	        	//$sub_total = $sub_total + (($sub_total * $IVA) / 100);
	        	$sub_total = round($sub_total,2);
	        }
	        //$monthly_payment = $monthly_payment + (($monthly_payment * $IVA) / 100);
	        $total_payment = $sub_total + $fitness + $extra_training + $transport;

    		$discipline = "exercise.png";
    		$message_discipline = "¿Está seguro de inhabilitar la modalidad fitness?";
	        if($student['Student']['fitness'] == 0){
	            $discipline = "jugador.png";
	            $message_discipline = "¿Está seguro de habilitar la modalidad fitness?";
	        }

	        $icon_status="inh1.png";
	        $message_status = "¿Está seguro de habilitar al estudiante?";
	        if($student['Student']['status'] == 1) {
	            $icon_status="hab1.png";
	            $message_status = "¿Está seguro de inhabilitar al estudiante?";
	        }

	        $title_image = "Cargar C&eacute;dula";
	        $route_img = "";
	        if (!empty($student['Student']['document_img'])) {
	        	$title_image = "Visualizar documento";
	        	$route_img = Router::url('/app/webroot/files/students/document/'.$id, true);
	        }
	        $this->set('monthly_payment', round($monthly_payment,2));
	        $this->set('message_rate',$message_rate);
	        $this->set('sub_total', $sub_total);
	        $this->set('extra_training', $extra_training);
	        $this->set('fitness', $fitness);
	        $this->set('transport', $transport);
	        $this->set('total_payment', round($total_payment,2));
	        $this->set('schorlarship', $schorlarship);
	        $this->set('route', $route);
	        $this->set('student', $student);
	        $this->set('description_status', $description_status);
	        $this->set('discipline', $discipline);
	        $this->set('message_discipline', $message_discipline);
	        $this->set('icon_status', $icon_status);
	        $this->set('message_status', $message_status);
	        $this->set('title_image',$title_image);
	        $this->set('siblings',$siblings);
	        $this->set('route_img',$route_img);
	        $this->set('assistance',$assistance);
		}

		public function uploadImage($id = null){
			if (!$id) throw new NotFoundException(__('Invalid student'));

	        $student = $this->Student->findById($id);

	        if (!$student) throw new NotFoundException(__('No se encontro el estudiante'));

			$this->Student->recursive = -1;
	        $this->Student->id = $id;
	        if ($this->request->is('post')){
	        	$data = $this->request->data;
	            if ($this->Student->saveField('document_img',$data['File']['document']['name'])) {
	            	$folder_name = $id;
		            $route = WWW_ROOT . 'files' . DS . 'students' . DS . 'document' . DS . $folder_name;
		            $dir = new Folder ($route, true, 0755);
		            if (!is_null($dir->path)) {
					    $dir->delete();
					    $dir = new Folder ($route, true, 0755);
					}
		            $filename = basename($data['File']['document']['name']);
		            $extension = pathinfo($filename, PATHINFO_EXTENSION);
		            $filePath = $route . DS . $filename;
		            move_uploaded_file($data['File']['document']['tmp_name'], $filePath);
	            	$this->Student->saveField('date_modification', date('Y-m-d'));
	                $this->Flash->success('Se he guardado el documento correctamente');
	            	$this->redirect(array('controller'=>'students','action' => 'view/'.$id));
	            } else $this->Flash->danger('No se ha podido guardar el documento del estudiante');
	            
	        }
		}

		public function assignScholarship($id = null){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			if (!$id) throw new NotFoundException(__('Invalid student'));

	        $this->Student->recursive = -1;
	        $student = $this->Student->findById($id);

	        if (!$student) throw new NotFoundException(__('No se encontro el estudiante'));

	        $this->Student->id = $id;
	        
	        if ($this->request->is('post')) {
	            if ($this->Student->saveField('scholarship_id', $this->request->data['scholarship_id'])) {
	            	$this->Student->saveField('date_modification', date('Y-m-d'));
	                $this->Flash->success('Se asignó correctamente el descuento al estudiante');
	            	$this->redirect(array('controller'=>'students','action' => 'view/'.$id));
	            }else $this->Flash->danger('No se asignó el descuento al estudiante');
	        }

			$student = $this->Student->find('first', array('conditions' => array('id' => $id)));
			$scholarships = $this->Scholarship->find('all');
			$this->set('student',$student);
			$this->set('scholarships',$scholarships);
		}

		public function assignCategory($id = null){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			if (!$id) throw new NotFoundException(__('Invalid student'));

	        $this->Student->recursive = -1;
	        $student = $this->Student->findById($id);

	        if (!$student) throw new NotFoundException(__('No se encontro el estudiante'));

	        $this->Student->id = $id;

	        if ($this->request->is('post')) {
	            if ($this->Student->saveField('category_id', $this->request->data['category_id'])) {
	            	$this->Student->saveField('date_modification', date('Y-m-d'));
	                $this->Flash->success('Se asignó correctamente la categoria al estudiante');
	            	$this->redirect(array('controller'=>'students','action' => 'view/'.$id));
	            } else $this->Flash->danger('No se asignó la categoria al estudiante');
	        }
	        $this->Category->recursive = -1;
	        $categories = $this->Category->find('all', array('conditions' => array('status !=' => 0)));
			$this->set('categories',$categories);

			$student = $this->Student->find('first', array('conditions' => array('id' => $id)));
			$this->set('student',$student);
		}

		public function assignStatus($id = null){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			if (!$id) throw new NotFoundException(__('Invalid student'));

	        $this->Student->recursive = -1;
	        $student = $this->Student->findById($id);

	        if (!$student) throw new NotFoundException(__('No se encontro el estudiante'));

	        $this->Student->id = $id;

	        if ($this->request->is('post')){
	        	$status_previus = $student['Student']['status'];
	        	$status_new = $this->request->data['status'];

	        	if ($status_previus != $status_new) {
	        		$payments_pending = $this->DetailsOrder->getOrdersPending($id);
	        		
	        		if ($status_new == 1 && $payments_pending > 0) {
	        			$this->Flash->danger('El estudiante posee deudas, su estatus debe ser deudor!');
	        			$this->redirect(array('controller'=>'students','action' => 'assignStatus/'.$id));
	        		}

	        		if ($status_previus == 1 && $status_new == 3) {
		        		$month = date('n');
		        		$year = date('Y');
		        		$day = date('d');
		        		$amount_credit_note = 0;

		        		$last_bill_paid = $this->DetailsBill->getLastBillPaid($id);
		        		$last_items_paid = $this->DetailsBill->find('all',array('conditions' => array(
		        									'bill_id' => $last_bill_paid, 'student_id' => $id)));
		        		foreach ($last_items_paid as $item) {
		        			if (($month > $item['DetailsBill']['month'] || $month == $item['DetailsBill']['month']) && 
		        				($item['DetailsBill']['product'] == 'Pension' || $item['DetailsBill']['product'] == 'Fitness'
		        				|| $item['DetailsBill']['product'] == 'Transporte' || $item['DetailsBill']['product'] == 'Extra Training')) {
		        				$days = 30 - $day;
		        				$cost_item = $item['DetailsBill']['cost'] / $days;
		        				$cost_item = $cost_item + ($cost_item * ($item['DetailsBill']['iva'] / 100));
		        				$amount_credit_note = $amount_credit_note + $cost_item;
		        			} 
		        		}

		        		if ($amount_credit_note != 0) {
		        			$amount_credit_note = $amount_credit_note - ($amount_credit_note * 0.50);
		        			$amount_credit_note = round($amount_credit_note,2);
		        			$credit_note = $this->Credit->find('first',array('conditions' => array('people_id' => $student['Student']['people_id'])));
							if (empty($credit_note)) {
								$credit = array('Credit' => array(
		        						'people_id' => $student['Student']['people_id'],
		        						'amount' => $amount_credit_note,
		        						'date_created' => date('Y-m-d')
		        				));
			        			$this->Credit->create();
			        			$this->Credit->save($credit);	
							} else {
								$this->Credit->id = $credit_note['Credit']['id'];
								$amount_credit_note = $amount_credit_note + $credit_note['Credit']['amount'];
								$this->Credit->saveField('amount',$amount_credit_note);
								$this->Credit->saveField('date_created',date('Y-m-d'));
							}
		        		}
		        	}
		            if ($this->Student->saveField('status',$status_new)) {
		            	$this->Student->saveField('observation', $this->request->data['observation']);
		            	$this->Student->saveField('date_modification', date('Y-m-d'));
		            	if ($status_new == 1 && $status_previus == 3) {
		            		$disabled_student = array(
			            		'DisabledsStudent' => array(
			            			'student_id' => $id,
			            			'date_disabled' => $this->request->data['date_payment']
			            		));
			            	$this->DisabledsStudent->create();
			            	$this->DisabledsStudent->save($disabled_student);
		            	}
		                $this->Flash->success('Se asignó correctamente el estatus al estudiante');
		            	$this->redirect(array('controller'=>'students','action' => 'view/'.$id));
		            } else $this->Flash->danger('No se asignó el estatus al estudiante');
	        	} else $this->Flash->danger('Debe asignar un estatus distinto al actual');
	            
	        }
			$student = $this->Student->find('first', array('conditions' => array('id' => $id)));
			$this->set('student',$student);	
		}

		public function delete(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
             	$this->Student->id = $this->data['student_id'];
             	$this->Student->recursive = -1;
             	$student = $this->Student->findById($this->data['student_id']);
             	$siblings = $this->Student->getSiblings($student['student']['document_number'],$this->data['student_id']);
             	if($siblings > 0) {
             		$siblings = $siblings - 1;
             		//Update Siblings
					$this->Student->updateAll(array("siblings"=>"'$siblings'"),array("Student.document_number" => $student['student']['document_number']));
					
					$bills = $this->DetailsBill->find('all',array(
													'conditions' => array('student_id' => $student['Student']['id']),
													'fields' => array('bill_id'),
													'group' => 'bill_id'));
					$this->DetailsBill->deleteAll(array('student_id' => $student['Student']['id']));
					foreach ($bills as $bill) {
						$details_bill = $this->DetailsBill->find('all',array(
																'conditions' => array('bill_id' => $bill['DetailsBill']['bill_id'])));

						if (empty($details_bill)) {
							//delete Bills
							$this->Bill->id = $bill['DetailsBill']['bill_id'];
             				$this->Bill->delete($bill['DetailsBill']['bill_id']);
             				$this->InvoicesPayment->deleteAll(array('bill_id' => $bill['DetailsBill']['bill_id']));
						}
					}

					$orders = $this->DetailsOrder->find('all',array(
													'conditions' => array('student_id' => $student['Student']['id']),
													'fields' => array('order_id'),
													'group' => 'order_id'));
					$this->DetailsOrder->deleteAll(array('student_id' => $student['Student']['id']));
					foreach ($orders as $order) {
						$details_order = $this->DetailsOrder->find('all',array(
																'conditions' => array('order_id' => $order['DetailsOrder']['order_id'])));

						if (empty($details_order)) {
							//delete Order
							$this->Order->id = $order['DetailsOrder']['order_id'];
             				$this->Order->delete($order['DetailsOrder']['order_id']);
						}
					}

             	} else {
             		$bills = $this->Bill->find('all',array('conditions' => array('people_id' => $student['Student']['people_id'])));
             		foreach ($bills as $bill) {
             			//delete Payments
             			$this->InvoicesPayment->deleteAll(array('bill_id' => $bill['Bill']['id']));
             		}
             		//delete Bills
             		$this->Bill->deleteAll(array('people_id' => $student['Student']['people_id']));
             		$this->DetailsBill->deleteAll(array('student_id' => $student['Student']['id']));
					//delete Orders
					$this->Order->deleteAll(array('people_id' => $student['Student']['people_id']));
					$this->DetailsOrder->deleteAll(array('student_id' => $student['Student']['id']));
					//delete Credit
					$this->Credit->delete(array('people_id' => $student['Student']['people_id']));
					//delete People
					$this->People->id = $student['Student']['people_id'];
					$this->People->delete($student['Student']['people_id']);
             	}
             	//delete Control
				$this->DetailsControl->deleteAll(array('student_id' => $student['Student']['id']));
             	//delete Student
             	$this->DisabledsStudent->delete(array('student_id' => $student['Student']['id']));
				$this->Student->delete($student['Student']['id']);
   				echo json_encode($this->data['student_id']);
			}
		}

		public function massEmail(){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			$this->Student->recursive = -1;
        	$students = $this->Student->getStudents();
        	$size = sizeof($students);
        	$active_students = $this->Student->getStudentsByStatus(1);
        	$disabled_students = $this->Student->getStudentsByStatus(2);
        	$debtor_students = $this->Student->getStudentsByStatus(3);
        	$inactive_students = $this->Student->getStudentsByStatus(4);
        	$absent_students = $this->Student->getStudentsByStatus(5);
        	$total_students_to_renew = 0;
        	$now = time();
        	foreach($students as $student):

	            $date_inscription = strtotime($student['Student']['date_inscription']);
	            $date_diff = $now - $date_inscription;
	            $days = floor($date_diff / (60 * 60 * 24));
	            if($days > 350  && $student['Student']['status'] == 1) $total_students_to_renew++;

	        endforeach;

        	$this->set('students',$students);
        	$this->set('size',$size);
        	$this->set('active_students',sizeof($active_students));
        	$this->set('disabled_students',sizeof($disabled_students));
        	$this->set('debtor_students',sizeof($debtor_students));
        	$this->set('inactive_students',sizeof($inactive_students));
        	$this->set('absent_students',sizeof($absent_students));
        	$this->set('total_students_to_renew',$total_students_to_renew);
		}

		public function massEmailAjax(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$data = $this->data;
				$student_status = $data['student_status'];
				$students = "";
				$email_send_success = 0;
				$size = 0;
				$total_students_to_renew = 0;

	            if($student_status == "Act") $students = $this->Student->getStudentsByStatus(1);
	            else if($student_status == "Deu") $students = $this->Student->getStudentsByStatus(2);
	            else if($student_status == "Inh") $students = $this->Student->getStudentsByStatus(3);
	            else if($student_status == "Ina") $students = $this->Student->getStudentsByStatus(4);
	            else if($student_status == "Aus") $students = $this->Student->getStudentsByStatus(5);
	            else $students = $this->Student->getStudents();

	            $now = time();
	        	$size = sizeof($students);
	            foreach($students as $student):
	                $email = $student['Student']['email'];
	                if(empty($email)) $email = $estudiante['Student']['alternative_email'];

	                if ($student_status == "Ren") {
	                	$date_inscription = strtotime($student['Student']['date_inscription']);
			            $date_diff = $now - $date_inscription;
			            $days = floor($date_diff / (60 * 60 * 24));
			            if($days > 350) {
			                if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)){
			                    App::uses('CakeEmail', 'Network/Email');	                    
			                    $Email = new CakeEmail('gmail');
			                    $Email->from('gefecuador@gmail.com');
			                    $Email->to($email);
			                    $Email->subject($data['title_message']);
			                    if ($Email->send($data['message'])) $email_send_success++;

			                    $Email->reset();
			                }
			                $total_students_to_renew++;
			            }
			            $size = $total_students_to_renew;
	                } else {
	                	if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)){
		                    App::uses('CakeEmail', 'Network/Email');	                    
		                    $Email = new CakeEmail('gmail');
		                    $Email->from('gefecuador@gmail.com');
		                    $Email->to($email);
		                    $Email->subject($data['title_message']);
		                    if ($Email->send($data['message'])) $email_send_success += 1;

		                    $Email->reset();
		                }
	                }
	           
	            endforeach;
	            $data_array = array('email_send_success' => $email_send_success, 'size' => $size);
	            echo json_encode($data_array);
			}
		}

		public function getSiblings(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$document_number = $this->data['document_number'];
				$this->Student->recursive = -1;
				$siblings = $this->Student->find('all', array('conditions' => array('Student.document_number' => $document_number)));
				$size = sizeof($siblings);
				$this->People->recursive = -1;
				$responsable = $this->People->find('first', array('conditions' => array('People.document_number' => $document_number, 'role_id' => 5)));
   				$responsable['People']['siblings'] = $size;
   				echo json_encode($responsable);
			}
		}

		public function getDataStudent(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()){
     			Configure::write('debug', 0);
  			}

			if(!empty($this->data)){
				$student_id = $this->data['student_id'];
				$this->Student->recursive = -1;
				$this->Student->id = $this->data['student_id'];
				$student = $this->Student->find('first', array('conditions' => array('id' => $student_id)));
   				echo json_encode($student);
			}
		}
		
		public function getStudents(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			$status = $this->data['status'];
			$this->Student->recursive = -1;
			$students = array();
			if(empty($status)){
				$data_student = $this->_processStudents($this->Student->getStudents());
				array_push($students, $data_student);
			} else {
				for ($i=0; $i < count($status); $i++) {
					if($status[$i]['status'] == 'Ren'){
						$data_student = $this->_processStudents($this->Student->getStudentsRenovate());
						array_push($students, $data_student);	
					} else {
						// 1 - Active, 2 - Debtor, 3 - Disabled, 4 - inactive
						$status_students;
						if($status[$i]['status'] == 'Act') $status_students = 1;
	                	else if($status[$i]['status'] == 'Deu') $status_students = 2;
	                	else if($status[$i]['status'] == 'Inh') $status_students = 3;
	                	else if($status[$i]['status'] == 'Ina') $status_students = 4;
	                	else if($status[$i]['status'] == 'Aus') $status_students = 5;
						
						$students_status = $this->Student->getStudentsByStatus($status_students);
						if (!empty($students_status)) {
							$data_student = $this->_processStudents($students_status);
							array_push($students, $data_student);
						}
					}
				}
			}
			echo json_encode($students);
		}

		public function inscription(){
			$this->Student->recursive = -1;
			$students = $this->Student->getStudentsNews();
			$size = sizeof($students);
			$this->set('size',$size);
			$this->set('students',$students); 
		}

		public function reportStudents(){
			
			if ($this->request->is('post')) {
	            $type_filter = $this->request->data;
	            $status = 0;
	            $size = 0;
	            $students = array();
	            $size = 0;
				if(empty($type_filter)){
					$data_student = $this->_processStudents($this->Student->getStudents());
					array_push($students, $data_student);
				} else {
					for ($i=0; $i < count($type_filter['student_filter']); $i++) {
						if($type_filter['student_filter'][$i] == 'Ren'){
							$data_student = $this->_processStudents($this->Student->getStudentsRenovate());
							array_push($students, $data_student);	
						} else {
							// 1 - Active, 2 - Debtor, 3 - Disabled, 4 - inactive
							$status_students;
							if($type_filter['student_filter'][$i] == 'Act') $status_students = 1;
		                	else if($type_filter['student_filter'][$i] == 'Deu') $status_students = 2;
		                	else if($type_filter['student_filter'][$i] == 'Inh') $status_students = 3;
		                	else if($type_filter['student_filter'][$i] == 'Ina') $status_students = 4;
		                	else if($type_filter['student_filter'][$i] == 'Aus') $status_students = 5;
							
							$students_status = $this->Student->getStudentsByStatus($status_students);
							if (!empty($students_status)) {
								$data_student = $this->_processStudents($students_status);
								array_push($students, $data_student);
							}
						}
					}
				}

				$students = $this->_processDataStudents($students);
				usort($students, $this->_buildSorter('name'));

	            $this->pdfConfig = array(
	                'download' => true,
	                'filename' => "Reporte_estudiantes.pdf"
	            );

	            $this->set('size',sizeof($students));
	            $this->set('students',$students);
	        }
		
		}

		public function reportInscription(){
			if ($this->request->is('post')) {
				$data = $this->request->data;
				$this->Student->recursive = -1;
				$students = "";
				if(empty($data['date_from_report']) && empty($data['date_until_report']))
					$students = $this->Student->getStudentsNews();
				else
					$students = $this->Student->getStudentsNewsByDateInscription($data['date_from_report'],$data['date_until_report']);
				
				$size = sizeof($students);

				$this->pdfConfig = array(
					'download' => true,
					'filename' => 'reporte_estudiantes_matriculados.pdf'
				);

				$this->set('size',$size);
				$this->set('students',$students);
			}
		}

        public function _getRouteTransport($route_id,$transport_id){
        	$route_transport = $this->RoutesTransport->find('first',array('conditions' => array('route_id' => $route_id, 'transport_id' => $transport_id)));
		    $route_transport_id = $route_transport['RoutesTransport']['id'];
		    return $route_transport_id;
		}

		public function _generateOrders(){
			$status = array(1,2);
			$this->Student->recursive = -1;
			$students = $this->Student->find('all',array('conditions' => array('status' => $status)));
			$month = date('n');
			$year = date('Y');
			$IVA = $this->Parameter->getValueParameter(1);

			foreach ($students as $student) {
				$items_student = array();
				$cost_transport = 0;
				$cost_fitness = 0;
				$cost_pension = 0;
				$cost_extra_training = 0;
				$sub_total = 0;
				$sub_total_iva = 0;
				$total_payment = 0;
				
				$assistance = $this->DetailsControl->getAssistanceByStudent($student['Student']['id']);

				if ($assistance == 0) {
					$this->Student->id = $student['Student']['id'];
					$this->Student->saveField('status',5);
					$this->Student->saveField('date_modification',date('Y-m-d'));
				} else {
					$siblings = $this->Student->getSiblings($student['Student']['document_number'], $student['Student']['id']);

					$multiplier = 1;
		        	$schorlarship = 0;
		        	if(!empty($student['Student']['scholarship_id']))
		        		$schorlarship = $this->Scholarship->getValueScholarship($student['Student']['scholarship_id']);
		        	else {
			        	if($siblings == 1) $multiplier = 0.9;
			        	else if ($siblings > 1) $multiplier = 0.8;
		        	}
		        	
		        	if (!empty($student['Student']['training_mode_id'])) {
						$cost_pension = $this->CalculateAmount->amountItem($student['Student']['training_mode_id']);
						$cost_pension = $this->CalculateAmount->calculateRate($cost_pension,$multiplier,$schorlarship);
						
						$item = array('student_id' => $student['Student']['id'],'product' => 'Pension', 'quantity' => 1,
				        			  'cost' => $cost_pension,'iva' => $IVA, 'description' => 'Pago pendiente del mes',
				        			  'transport_id' => null, 'month' => $month, 'year' => $year);
				        
						$last_month_paid = $this->DetailsBill->getLastMonthPaid('Pension',$student['Student']['id']);

						if ($student['Student']['status'] == 1) {
			        		if ($month > $last_month_paid['DetailsBill']['month']) array_push($items_student,$item);
		        			elseif ($month < $last_month_paid['DetailsBill']['month'] && $year > $last_month_paid['DetailsBill']['year'])
		        				array_push($items_student,$item);
						} elseif ($student['Student']['status'] == 2) array_push($items_student,$item);
							
					}

		        	if ($student['Student']['extra_training'] != 0) {
						$extra_training = $this->CalculateAmount->amountItem(7);

						$item = array('student_id' => $student['Student']['id'],'product' => 'Extra Training', 'quantity' => 1,
				        			  'cost' => $extra_training,'iva' => $IVA, 'description' => 'Pago pendiente del mes',
				        			  'transport_id' => null, 'month' => $month, 'year' => $year);
				        
						$last_month_paid = $this->DetailsBill->getLastMonthPaid('Extra Training',$student['Student']['id']);

						if ($student['Student']['status'] == 1) {
			        		if ($month > $last_month_paid['DetailsBill']['month']) array_push($items_student,$item);
		        			elseif ($month < $last_month_paid['DetailsBill']['month'] && $year > $last_month_paid['DetailsBill']['year'])
		        				array_push($items_student,$item);
						} elseif ($student['Student']['status'] == 2) array_push($items_student,$item);
							
					}

		        	if ($student['Student']['fitness'] != 0) {
						$cost_fitness = $this->CalculateAmount->amountItem(5);
						if(empty($student['Student']['training_mode_id']) && !empty($student['Student']['scholarship_id']))
							$cost_fitness = $cost_fitness - ((($cost_fitness * $schorlarship) /100));

						$item = array('student_id' => $student['Student']['id'],'product' => 'Fitness', 'quantity' => 1,
				        			  'cost' => $cost_fitness,'iva' => $IVA, 'description' => 'Pago pendiente del mes',
				        			  'transport_id' => null, 'month' => $month, 'year' => $year);
				        
						$last_month_paid = $this->DetailsBill->getLastMonthPaid('Fitness',$student['Student']['id']);

						if ($student['Student']['status'] == 1) {
			        		if ($month > $last_month_paid['DetailsBill']['month']) array_push($items_student,$item);
		        			elseif ($month < $last_month_paid['DetailsBill']['month'] && $year > $last_month_paid['DetailsBill']['year'])
		        				array_push($items_student,$item);
						} elseif ($student['Student']['status'] == 2) array_push($items_student,$item);
							
					}
					
					if (!empty($student['Student']['routes_transport_id'])) {
						$cost_transport = $this->CalculateAmount->amountTransport($student['Student']['routes_transport_id']);
						$last_month_paid = $this->DetailsBill->getLastMonthPaid('Transporte',$student['Student']['id']);
						$transport_id = $this->RoutesTransport->getTransportByIdRouteTransport($student['Student']['routes_transport_id']);
						
						$item = array('student_id' => $student['Student']['id'],'product' => 'Transporte', 'quantity' => 1,
				        			  'cost' => $cost_transport,'iva' => 0, 'description' => 'Pago pendiente del mes',
				        			  'transport_id' => $transport_id, 'month' => $month, 'year' => $year);
				        
						if ($student['Student']['status'] == 1) {
			        		if ($month > $last_month_paid['DetailsBill']['month']) array_push($items_student,$item);
		        			elseif ($month < $last_month_paid['DetailsBill']['month'] && $year > $last_month_paid['DetailsBill']['year'])
		        				array_push($items_student,$item);
						} elseif ($student['Student']['status'] == 2) array_push($items_student,$item);
					}	
					
					if (sizeof($items_student) > 0) {
						$total_payment = $cost_pension + $cost_fitness + $cost_extra_training + $cost_transport;
						//$sub_total = $cost_pension + $cost_fitness + $cost_extra_training;
						//$sub_total_iva = $sub_total + ($sub_total * ($IVA / 100));
						//$total_payment = $sub_total_iva + $cost_transport;
						//$total_payment = round($total_payment,2);
						$observation = "Se genero la orden de pago por la deuda del mes";
						$this->_generateOrderStudent($student,$items_student,$total_payment,$observation);
					}
				}
							
			}
			$this->Initiator->id = 2;
	        $this->Initiator->saveField('value',1);
		}

		public function _generateOrderStudent($student,$items_student,$total_payment,$observation){
			$pendings_payments = $this->Order->getPendingPaymentsByStudent($student['Student']['id']);
			$orders_pending = $this->DetailsOrder->getOrdersPending($student['Student']['id']);
			if ($orders_pending >= 3) {
				$this->Student->id = $student['Student']['id'];
				$this->Student->saveField('status',3);
				$this->Student->saveField('date_modification',date('Y-m-d'));
				$disabled_student = array(
            		'DisabledsStudent' => array(
            			'student_id' => $student['Student']['id'],
            			'date_disabled' => date('Y-m-d') 
            		));
            	$this->DisabledsStudent->create();
            	$this->DisabledsStudent->save($disabled_student);
			} else {
				if (sizeof($pendings_payments) == 0) {
					$order_id = $this->BillData->saveOrder($student['Student']['people_id'],$total_payment,$observation);
					for ($i=0; $i < count($items_student); $i++) { 
						$this->BillData->saveDetailOrder($order_id,$student['Student']['id'],$items_student[$i]['product'],
														 $items_student[$i]['description'],$items_student[$i]['cost'],$items_student[$i]['quantity'],
														 $items_student[$i]['month'],$items_student[$i]['year'],
														 $items_student[$i]['transport_id'],$items_student[$i]['iva']);
					}
					$this->Student->id = $student['Student']['id'];
					$this->Student->saveField('status',2);
					$this->Student->saveField('date_modification',date('Y-m-d'));
				} else {
					$last_order = $this->Order->getLastPendingPaymentByStudent($student['Student']['id']);
					$this->Order->id = $last_order['Order']['id'];
					$total_pending = $total_payment + $last_order['Order']['pending_total'];
					$this->Order->saveField('pending_total',$total_pending);
					for ($i=0; $i < count($items_student); $i++) { 
						$this->BillData->saveDetailOrder($last_order['Order']['id'],$student['Student']['id'],$items_student[$i]['product'],
														 $items_student[$i]['description'],$items_student[$i]['cost'],$items_student[$i]['quantity'],
														 $items_student[$i]['month'],$items_student[$i]['year'],
														 $items_student[$i]['transport_id'],$items_student[$i]['iva']);
					}
				}
			}
		}

		public function _buildSorter($clave) {
		    return function ($a, $b) use ($clave) {
		        return strnatcmp($a[$clave], $b[$clave]);
		    };
		}
		
		public function _processDataStudents($students){
			$array_students = [];

			for ($i=0; $i < count($students); $i++) { 
				for ($j=0; $j < count($students[$i]); $j++) { 
					$student = array('code_final' => $students[$i][$j]['Student']['code_final'],
									 'name' => $students[$i][$j]['Student']['lastname']." ".$students[$i][$j]['Student']['name'],
									 'email' => $students[$i][$j]['Student']['email'],
									 'age' => $students[$i][$j]['Student']['age'],
									 'category' => $students[$i][$j]['c']['name'],
									 'responsable' => $students[$i][$j]['Student']['responsable']);

					array_push($array_students, $student);
				}
			}

			return $array_students;
		}

		public function _processStudents($students){
			$now = time();
			$to = new DateTime('today');
			for ($j=0; $j < count($students); $j++) {
				$birthday = new DateTime($students[$j]['Student']['birthday']);
                $to = new DateTime('today');
                $age = $birthday->diff($to)->y;
				$students[$j]['Student']['age'] = $age;

                $formato = "EF";
                $longitud_relleno = 4 - strlen($students[$j]['Student']['id']);  //Calculo el numero de ceros a ser anadidos
                $relleno = str_repeat("0",$longitud_relleno);  //Relleno de caracteres
                $code_final = $formato.$relleno.$students[$j]['Student']['id'];
				$students[$j]['Student']['code_final'] = $code_final;
				$students[$j]['Student']['class_renew'] = "";
                $students[$j]['Student']['imagen'] = "view.png";
                
                $date_inscription = strtotime($students[$j]['Student']['date_inscription']);
    			$date_diff = $now - $date_inscription;
    			$days = floor($date_diff / (60 * 60 * 24));
                $class_renew = "";
                if($days > 350 && $students[$j]['Student']['status'] == 1) {
                  	$class_renew = "efecto";
                  	$students[$j]['Student']['class_renew'] = $class_renew;
                	$students[$j]['Student']['imagen'] = "renovar.png";
                }

                if($students[$j]['Student']['status'] == 1) {
                    $students[$j]['Student']['status_str'] = "Act";
                    $students[$j]['Student']['status_class'] = "active_students";
                }
                if($students[$j]['Student']['status'] == 2){
                    $students[$j]['Student']['status_str'] = "Deu";
                    $students[$j]['Student']['status_class'] = "debtor_students";
                }
                if($students[$j]['Student']['status'] == 3){
                	$students[$j]['Student']['status_str'] = "Inh";
                    $students[$j]['Student']['status_class'] = "disabled_students";
                }
                if($students[$j]['Student']['status'] == 4){
                    $students[$j]['Student']['status_str'] = "Ina";
                    $students[$j]['Student']['status_class'] = "inactive_students";
                }
                if($students[$j]['Student']['status'] == 5){
                    $students[$j]['Student']['status_str'] = "Aus";
                    $students[$j]['Student']['status_class'] = "absent_students";
                }

                if($class_renew == 'efecto'){
                   	$students[$j]['Student']['status_str'] = "Ren";
                	$students[$j]['Student']['status_class'] = "renew_students";
                }
      		}
      		return $students;
		}

	}