<?php  

	/**
	* 
	*/
	class BillsController extends AppController
	{
		var $uses = array('Bill','DetailsBill','ModesBill','TypesBill','Parameter','Scholarship','RoutesTransport',
						  'Category','Student','People','Route','Product','Closing','DetailsClosing','Initiator',
						  'Order','InvoicesPayment','Credit','Receipt','DisabledsStudent','ScholarshipsBill','Flash');

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
			$bills = $this->Bill->getBills();
			$payments = $this->InvoicesPayment->getAllInvoicesPayment();
			$this->set('bills',$bills);
			$this->set('payments',$payments);
			$this->set('size',sizeof($bills));
		}

		public function detailBill($bill_id = ''){
			if ($this->Auth->user('role_id') != 1) {
				return $this->redirect(
		            array('controller' => 'home', 'action' => 'index')
		        );
			}
			if (!$bill_id) {
	            throw new NotFoundException(__('Invalid bill'));
	        }

	        $bill = $this->Bill->findById($bill_id);

	        if (!$bill) {
	            throw new NotFoundException(__('No se encontro la factura'));
	        }

			$bill = $this->Bill->getDataBill($bill_id);
			$details_bill = $this->DetailsBill->getDataDetail($bill_id);
			$total_iva_zero = 0;
			$total_iva = 0;
			$sub_total = 0;
			$iva = 0;
			$exoneration = 0;
			$scholarship = 0;
			$modes_bill = $this->InvoicesPayment->getInvoicesPaymentBill($bill_id);
			$observation = "";
			$size_modes_bill = sizeof($modes_bill) - 1;
			$modes_bill_str = "";
			for ($i=0; $i < count($modes_bill); $i++) { 
				if ($i == $size_modes_bill) {
					$modes_bill_str .= $modes_bill[$i]['m']['name'];
					$observation .= (!empty($modes_bill[$i]['InvoicesPayment']['observation'])) ? $modes_bill[$i]['InvoicesPayment']['observation'].".\n" : "";
				} else {
					$modes_bill_str .= $modes_bill[$i]['m']['name'].",";
					$observation .= (!empty($modes_bill[$i]['InvoicesPayment']['observation'])) ? $modes_bill[$i]['InvoicesPayment']['observation'].".\n" : "";
				}
			}

			/*foreach ($details_bill as $detail) {
				if ($detail['DetailsBill']['iva'] == 0)
					$total_iva_zero += ($detail['DetailsBill']['cost'] * $detail['DetailsBill']['quantity']);
				else{
					$sub_total += ($detail['DetailsBill']['cost'] * $detail['DetailsBill']['quantity']);
					$iva = $detail['DetailsBill']['iva'];
				}

				if ($detail['DetailsBill']['scholarship'] != 0) $scholarship = round($detail['DetailsBill']['scholarship'],2);
				if ($detail['DetailsBill']['exoneration'] != 0) $exoneration += round($detail['DetailsBill']['exoneration'],2);
			}*/

			$exoneration_iva = 0;
			for ($i=0; $i < count($details_bill); $i++) { 
				if ($details_bill[$i]['DetailsBill']['iva'] == 0) {
					$total_iva_zero += ($details_bill[$i]['DetailsBill']['cost'] * $details_bill[$i]['DetailsBill']['quantity']);
					$details_bill[$i]['DetailsBill']['cost_item'] = $details_bill[$i]['DetailsBill']['cost'];
					$details_bill[$i]['DetailsBill']['sub_total_item'] = $details_bill[$i]['DetailsBill']['cost'] * $details_bill[$i]['DetailsBill']['quantity'];
				} else {
					$iva = $details_bill[$i]['DetailsBill']['iva'];
					$cost_item = $details_bill[$i]['DetailsBill']['cost'] / (1 + ( $details_bill[$i]['DetailsBill']['iva'] / 100));
					$cost_item = round($cost_item,2);
					$details_bill[$i]['DetailsBill']['cost_item'] = $cost_item;
					$details_bill[$i]['DetailsBill']['sub_total_item'] = $cost_item * $details_bill[$i]['DetailsBill']['quantity'];
					$sub_total += ($cost_item * $details_bill[$i]['DetailsBill']['quantity']);
					$sub_total_item = $cost_item * $details_bill[$i]['DetailsBill']['quantity'];
					//$total_iva += ($details_bill[$i]['DetailsBill']['cost'] * $details_bill[$i]['DetailsBill']['quantity']) - $sub_total_item;
					
					$scholarship_iva = 0;
					if ($details_bill[$i]['DetailsBill']['scholarship'] != 0){
						$scholarship = $details_bill[$i]['DetailsBill']['scholarship'] / (1 + ( $details_bill[$i]['DetailsBill']['iva'] / 100));;
						$scholarship = round($scholarship,2);
						$scholarship_iva = $details_bill[$i]['DetailsBill']['scholarship'] - $scholarship;
						$scholarship_iva = round($scholarship_iva,2);
						//$scholarship = round($details_bill[$i]['DetailsBill']['scholarship'],2);
					}

					
					if ($details_bill[$i]['DetailsBill']['exoneration'] != 0){
						//debug($details_bill[$i]['DetailsBill']['exoneration']);
						$exoneration_cal = $details_bill[$i]['DetailsBill']['exoneration'] / (1 + ($details_bill[$i]['DetailsBill']['iva'] / 100));
						$exoneration_cal = round($exoneration_cal,2); 
						$exoneration += $exoneration_cal;
						//debug($exoneration);
						$exoneration_iva += $details_bill[$i]['DetailsBill']['exoneration'] - $exoneration_cal;
						//$exoneration_iva += $details_bill[$i]['DetailsBill']['exoneration'] - $exoneration;
						//debug($exoneration_iva);
						//$exoneration_iva += $exoneration_iva;
						//$exoneration = $exoneration + ;
					}
					//if ($detail['DetailsBill']['exoneration'] != 0) $exoneration = round($detail['DetailsBill']['exoneration'],2);
					//$exoneration_iva = round($exoneration_iva,2);
					//$exoneration = round($exoneration,2);
					//debug($exoneration_iva);
					$total_iva += ($details_bill[$i]['DetailsBill']['cost'] * $details_bill[$i]['DetailsBill']['quantity']) - $sub_total_item - $scholarship_iva;
				}

				//if ($details_bill[$i]['DetailsBill']['scholarship'] != 0) $scholarship = round($details_bill[$i]['DetailsBill']['scholarship'],2);
				//if ($details_bill[$i]['DetailsBill']['exoneration'] != 0) $exoneration += round($details_bill[$i]['DetailsBill']['exoneration'],2);
			}

			$scholarship_bill = $this->ScholarshipsBill->find('first',array('conditions' => array('bill_id' => $bill_id)));
			$scholarship_total = (!empty($scholarship_bill)) ? $scholarship_bill['ScholarshipsBill']['scholarship_total'] : 0;
			$scholarship_iva =  $scholarship_total * ($iva / 100);
			$scholarship_iva = round($scholarship_iva,2);
			//$exoneration_iva = $exoneration * ($iva / 100);
			//debug($exoneration_iva);
			//$exoneration_iva = round($exoneration_iva,2);
			$total_iva = $total_iva - $scholarship_iva - $exoneration_iva;
			//$total_iva = ($sub_total - $exoneration - $scholarship - $bill['Bill']['credit'] - $scholarship_total) * ($iva / 100);
			$total_iva = round($total_iva,2);
			$sub_total = round($sub_total,2);

			$months = ["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Juio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];

			$this->set('bill',$bill);
			$this->set('iva',$iva);
			$this->set('details_bill',$details_bill);
			$this->set('total_iva',$total_iva);
			$this->set('scholarship',$scholarship);
			$this->set('exoneration',$exoneration);
			$this->set('total_iva_zero',$total_iva_zero);
			$this->set('months',$months);
			$this->set('sub_total',$sub_total);
			$this->set('modes_bill_str',$modes_bill_str);
			$this->set('observation',$observation);
			$this->set('scholarship_bill',$scholarship_bill);
		}
		
		public function studentBill($student_id = ''){
			if ($this->Auth->user('role_id') != 1) {
				return $this->redirect(
		            array('controller' => 'home', 'action' => 'index')
		        );
			}
			if (!$student_id) throw new NotFoundException(__('Invalid student'));

	        $this->Student->recursive = -1;
	        $student = $this->Student->findById($student_id);

	        if (!$student) throw new NotFoundException(__('No se encontro el estudiante'));

			$bill_code = $this->BillData->generateBillCode();
			$responsable = $this->Student->find('first',array('conditions' => array('Student.id' => $student_id),'fields' => array('Student.people_id')));
			$this->People->recursive = -1;
			$responsable = $this->People->getResponsable($responsable['Student']['people_id']);
			$credit_note = $this->Credit->find('first',array('conditions' => array('people_id' => $responsable['People']['id'])));
			$students = $this->Student->find('all',array('conditions' => array('document_number' => $responsable['People']['document_number'], 'status' => 1),
														'fields' => array('id','name','lastname')));
			$iva = $this->Parameter->getValueParameter(1);
			$iva = round($iva,2);
			$modes_bills = $this->ModesBill->find('all');
			$this->Scholarship->recursive = -1;
			$scholarships = $this->Scholarship->find('all',array('conditions' => array('status !=' => 0)));
			$this->set('scholarships',$scholarships);	
			$this->set('bill_code',$bill_code);
			$this->set('iva',$iva);
			$this->set('responsable',$responsable);
			$this->set('students',$students);
			$this->set('modes_bills',$modes_bills);
			$this->set('credit_note',$credit_note);
		}

		public function addStudentBill(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$item = $this->data['item'];
				$items = $this->data['items'];
				$total_payment = (empty($item['credit'])) ? $item['total'] : $item['credit'];
				$total_payment = round($total_payment,2);
				$code_itinial = $this->Parameter->getValueParameter(4);
				$bill_code = ($total_payment != 0) ? $item['bill_code'] : $code_itinial.'0000000';
				$observation = $item['observation'];

				/*if ($item['credit_notes'] != 0){
					$amount_credit_note = round($item['credit_notes'],2);
					$observation .= "Se ha creado una nota de crédito por el monto de $".$amount_credit_note.".\n ";	
				}*/

				$credit_amount = 0;

				if ($item['credit_note_amount'] != 0) {
					$credit_amount = $item['credit_note_amount'];
					$credit_amount = round($credit_amount,2);
        			$this->Credit->id = $item['credit_note_id'];
        			$this->Credit->delete($item['credit_note_id']);
				}

				$bill_id = $this->BillData->saveBill($item['responsable'],$bill_code,2,$item['date_payment'],$total_payment,$credit_amount,$observation,1);
				
				if($item['scholarship_total'] != 0) {
					$scholarship_total = $item['scholarship_total'];
					$this->ScholarshipsBill->create();
					$scholarship_bill = array('bill_id' => $bill_id, 
										'percentage' => $item['scholarship_str'], 
										'scholarship_total' => $item['scholarship_total']);
					$this->ScholarshipsBill->save($scholarship_bill);
				}

				if ($item['credit_notes'] != 0) {
					$amount_credit_note = round($item['credit_notes'],2);
        			$credit_note = $this->Credit->find('first',array('conditions' => array('people_id' => $item['responsable'])));
					if (empty($credit_note)) {
						$credit = array('Credit' => array(
        						'people_id' => $item['responsable'],
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

				if($total_payment != 0){
					$modes_bill = $item['modes_bill'];
					for ($i=0; $i < count($modes_bill); $i++) {
	                    $total_payment = round($modes_bill[$i]['amount'],2);   
	                    $this->BillData->savePayment($bill_id,$modes_bill[$i]['mode_bill'],$total_payment,$modes_bill[$i]['observation']);
	                }
				}

				$total_credit = $item['credit'];
				$items_pending = array();
				for($i=0; $i < count($items); $i++){
					if ($items[$i]['status'] == 'pending') $items_pending[] = $items[$i];
					else {
						$product = $items[$i]['product'];
						$cost = $items[$i]['cost'];
						$quantity = $items[$i]['quantity'];
						$description = $items[$i]['description'];
						$month = $items[$i]['month'];
						$year = $items[$i]['year'];
						$transport_id = "";
						$iva = $items[$i]['iva'];
						$student_id = $items[$i]['student_id'];
						$exoneration = 0;

						if($items[$i]['exoneration'] != 0)
							$exoneration = $items[$i]['cost'];

						if ($product == 'Transporte') {
							$sub_prefix = explode("-", $items[$i]['prefix']);
							$transport_id = $sub_prefix[2];
							$iva = 0;
						}

						if ($total_credit != 0 && $items[$i]['status'] == 'subscriber') {
							$pending = $items[$i]['total'] - $items[$i]['paid'];
							//$pending = $pending / (1 + $iva /100);
							//$cost_pending = $pending / $quantity;
							//$cost_pending = round($cost_pending,2);
							//$items[$i]['cost'] = $cost_pending;
							$items[$i]['cost'] = $pending;
							$items[$i]['description'] = "Deuda pendiente del item de la factura ".$item['bill_code'];
							$description = "Se realizo un abono del item";

							//$paid = $items[$i]['paid'] / (1 + $iva /100);
							//$cost = $paid / $quantity;
							//$cost = round($cost,2);
							$cost = round($items[$i]['paid'],2);
							$items_pending[] = $items[$i]; 
						}

						if($student_id != ""){
							$this->BillData->saveDetailBill($bill_id,$student_id,$product,$description,$cost,$exoneration,0,$quantity,$month,
								$year,$transport_id,$iva);

							if ($product == 'Matricula') {
								$this->Student->recursive = -1;
								$this->Student->id = $student_id;
								$student = $this->Student->findById($student_id);
								$exonerated = ($exoneration != 0) ? 1: 0;
								$date_inscription = $student['Student']['date_inscription'];
								$date_inscription_new = strtotime('+1 year',strtotime($date_inscription));
								$date_inscription_new = date( 'Y-m-d',$date_inscription_new );
								$this->Student->saveField('date_inscription',$date_inscription_new);
								$this->Student->saveField('exonerated',$exonerated);
								$this->Student->saveField('date_modification',date('Y-m-d'));
							}

							$disabled_student = $this->DisabledsStudent->find('first', array('conditions' => array('student_id' => $student_id)));
							if (!empty($disabled_student)) {
								$this->DisabledsStudent->id = $disabled_student['DisabledsStudent']['id'];
        						$this->DisabledsStudent->delete($disabled_student['DisabledsStudent']['id']);
        						$this->Student->id = $student_id;
								$this->Student->saveField('observation',null);
		             			$this->Student->saveField('date_modification',date('Y-m-d'));				
							}
						}

					}			
				}
				
				if(!empty($item['credit'])){
					$total_payment_order = $item['total'] - $item['credit'];
					if ($total_payment_order != 0) {
						$total_payment_order = round($total_payment_order,2);

						$observation = "Se genero una orden de pago por la deuda pendiente de la factura ".$item['bill_code']."\n";
						$order_id = $this->BillData->saveOrder($item['responsable'],$total_payment_order,$observation);
						
						for($i=0; $i < count($items_pending); $i++){
							$iva = $items_pending[$i]['iva'];
							$transport_id = null;

							if ($items_pending[$i]['product'] == 'Transporte') {
								$sub_prefix = explode("-", $items[$i]['prefix']);
								$transport_id = $sub_prefix[2];
								$iva = 0;
							}

							$this->BillData->saveDetailOrder($order_id,$items_pending[$i]['student_id'],$items_pending[$i]['product'],
								$items_pending[$i]['description'],$items_pending[$i]['cost'],$items_pending[$i]['quantity'],
								$items_pending[$i]['month'],$items_pending[$i]['year'],$transport_id,$iva);

							$this->Student->id = $student_id;
							$this->Student->saveField('status',2);
							$this->Student->saveField('observation','El estudiante posee una deuda');
	             			$this->Student->saveField('date_modification',date('Y-m-d'));

						}
					}
				}
				if($total_payment != 0) $this->BillData->updateCodeInitiator(1);
				echo $bill_id;
			}
		}
		
		public function clientBill($client_id = ''){
			if ($this->Auth->user('role_id') != 1) {
				return $this->redirect(
		            array('controller' => 'home', 'action' => 'index')
		        );
			}
			if (!$client_id) throw new NotFoundException(__('Invalid client'));

	        $client = $this->People->findById($client_id);

	        if (!$client) throw new NotFoundException(__('No se encontro el cliente'));

			$modes_bills = $this->ModesBill->find('all');
			$products = $this->Product->find('all', array('fields' => array('id','name')));
			$bill_code = $this->BillData->generateBillCode();
			$iva = $this->Parameter->getValueParameter(1);
			$iva = round($iva,2);
			$this->Scholarship->recursive = -1;
			$scholarships = $this->Scholarship->find('all',array('conditions' => array('status !=' => 0)));
			$this->set('scholarships',$scholarships);
			$this->set('bill_code',$bill_code);
			$this->set('client',$client);
			$this->set('modes_bills',$modes_bills);
			$this->set('products',$products);
			$this->set('iva',$iva);
		}	

		public function addClientBill(){	
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$data = $this->data;
				$data_bill = $data['data_bill'];
				$products = $data['products'];

				$total_payment = $data_bill['total_payment'];
				$total_payment = round($total_payment,2);

				$bill_id = $this->BillData->saveBill($data_bill['client_id'],$data_bill['bill_code'],6,
											$data_bill['date_payment'],$total_payment,0,$data_bill['observation'],
											$data_bill['status_bill']);				
				$iva = $data_bill['iva'];

				if($data_bill['status_bill'] == 1) {
					$modes_bill = $data_bill['modes_bill'];
					for ($i=0; $i < count($modes_bill); $i++) {
	                    $total_payment = round($modes_bill[$i]['amount'],2);   
	                    $this->BillData->savePayment($bill_id,$modes_bill[$i]['mode_bill'],$total_payment,$modes_bill[$i]['observation']);
	                }
				}

				if($data_bill['scholarship_total'] != 0) {
					$scholarship_total = $data_bill['scholarship_total'];
					$this->ScholarshipsBill->create();
					$scholarship_bill = array('bill_id' => $bill_id, 
											'percentage' => $data_bill['scholarship_str'], 
											'scholarship_total' => $data_bill['scholarship_total']);
					$this->ScholarshipsBill->save($scholarship_bill);
				}

				if($data_bill['status_bill'] == 3) {
					$this->People->id = $data_bill['client_id'];
					$this->People->saveField('status', 2);
					$this->People->saveField('date_modification',date('Y-m-d'));
				}
					
				$student_id = null;
				$month = null;
				$year = null;
				$transport_id = null;
				for ($i=0; $i < count($products); $i++) { 
					$product = $products[$i]['product'];
					$description = $products[$i]['description'];
					$cost = $products[$i]['cost'];
					$quantity = $products[$i]['quantity'];
					if ($products[$i]['iva'] == 'false') $iva = 0;
					$this->BillData->saveDetailBill($bill_id,$student_id,$product,$description,$cost,0,0,$quantity,
										$month,$year,$transport_id,$iva);
				}
				$this->BillData->updateCodeInitiator(1);
				echo $bill_id;
			}
		}

		public function billInscription(){
			if ($this->Auth->user('role_id') != 1) {
				return $this->redirect(
		            array('controller' => 'home', 'action' => 'index')
		        );
			}
			$modes_bills = $this->ModesBill->find('all');
			$iva = $this->Parameter->getValueParameter(1);
			$iva = round($iva,2);
			$bill_code = $this->BillData->generateBillCode();
			$this->Scholarship->recursive = -1;
			$scholarships = $this->Scholarship->find('all',array('conditions' => array('status !=' => 0)));
			$this->set('scholarships',$scholarships);
			$this->set('bill_code',$bill_code);
			$this->set('modes_bills',$modes_bills);
			$this->set('iva',$iva);
		}

		public function add(){

			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$data = $this->data;
				$this->People->recursive = -1;
				$responsable = $this->People->find('first',array('conditions' => array('document_number' => $data['responsable']['document_number'], 'role_id' => 5)));
				$responsable_id;
				if (empty($responsable)) {
					$responsable = array('People' => array(
									"role_id" => 5,
									"name" => $data['responsable']['name'],
									"document_number" => $data['responsable']['document_number'],
									"document_type" => $data['responsable']['document_type'],
									"address" => $data['responsable']['address'],
									"phone" => $data['responsable']['phone'],
									"date_created" => date('Y-m-d')));
					$this->People->create();
					$this->People->save($responsable);
					$responsable_id = $this->People->id;
				} else  {
					$responsable_id = $responsable['People']['id'];
				}

				$status = (empty($data['bill_data']['credit'])) ? 1 : 2;
				$exonerated = ($data['student']['exoneration'] != 0) ? 1 : 0;
				$student = array('Student' => array(
						"people_id" => $responsable_id, 
						"document_number" => $data['student']['document_number'],
						"name" => $data['student']['name'],
						"lastname" => $data['student']['lastname'],		
	                    "gender_id" => $data['student']['gender_id'],
	                    "birthday" => $data['student']['birthday'],
	                    "email" => $data['student']['email'],
	                    "alternative_email" => $data['student']['alternative_email'],
	                    "phone" => $data['student']['phone'],
	                    "home_phone" => $data['student']['home_phone'],
	                    "address" => $data['student']['address'],
	                    "responsable" => $data['student']['responsable'],
	                    "relation" => $data['student']['relation'],
	                    "training_mode_id" => $data['student']['training_mode_id'],
	                    "category_id" => $data['student']['category_id'],
	                    "fitness" => $data['student']['fitness'],
	                    "extra_training" => $data['student']['extra_training'],
	                    "routes_transport_id" => $data['student']['routes_transport_id'],
	                    "scholarship_id" => $data['student']['scholarship_id'],
	                    "date_inscription" => $data['student']['date_inscription'],
	                    "date_transport" => $data['student']['date_transport'],
	                    "status" => $status,
	                    "exonerated" => $exonerated,
	                    "date_created" => date('Y-m-d')));
				
				$this->Student->create();
				$this->Student->save($student);
				$this->Student->recursive = -1;
				$student_id = $this->Student->id;

				$siblings = $this->Student->getSiblings($data['student']['document_number'],$student_id);
				//Update Siblings
				$this->Student->updateAll(array("siblings"=>"'$siblings'"),array("Student.document_number" => $data['student']['document_number']));
				
				$bill_data = $data['bill_data'];
				$total_payment = (empty($bill_data['credit'])) ? $bill_data['total_payment'] : $bill_data['credit'];
				$total_payment = round($total_payment,2);
				$code_itinial = $this->Parameter->getValueParameter(4);
				$bill_code = ($total_payment != 0) ? $bill_data['bill_code'] : $code_itinial.'0000000';
				$observation = $bill_data['observation'];
				/*if ($bill_data['credit_notes'] != 0){
					$amount_credit_note = round($bill_data['credit_notes'],2);
					$observation .= "Se ha creado una nota de crédito por el monto de $".$amount_credit_note.".\n";
				}*/

				$bill_id = $this->BillData->saveBill($responsable_id,$bill_code,1,$bill_data['date_payment'],
													$total_payment,0,$observation,1);
				
				if ($bill_data['credit_notes'] != 0) {
					$amount_credit_note = round($bill_data['credit_notes'],2);
					$credit_note = $this->Credit->find('first',array('conditions' => array('people_id' => $responsable_id)));
					if (empty($credit_note)) {
						$credit = array('Credit' => array(
        						'people_id' => $responsable_id,
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

				if($bill_data['scholarship_total'] != 0) {
					$scholarship_total = $bill_data['scholarship_total'];
					$this->ScholarshipsBill->create();
					$scholarship_bill = array('bill_id' => $bill_id, 
											'percentage' => $bill_data['scholarship_str'], 
											'scholarship_total' => $bill_data['scholarship_total']);
					$this->ScholarshipsBill->save($scholarship_bill);
				}

				if($total_payment != 0){
					$modes_bill = $bill_data['modes_bill'];
					for ($i=0; $i < count($modes_bill); $i++) {
	                    $amount = round($modes_bill[$i]['amount'],2);   
	                    $this->BillData->savePayment($bill_id,$modes_bill[$i]['mode_bill'],$amount,$modes_bill[$i]['observation']);
	                }
				}

				$items = $this->data['items'];
				
				$total_credit = $bill_data['credit'];
				$items_pending = array();

				for($i=0; $i < count($items); $i++){
					if ($items[$i]['Product']['status'] == 'pending') $items_pending[] = $items[$i];
					else {
						$product = $items[$i]['Product']['name'];
						$cost = $items[$i]['Product']['cost'];
						$quantity = 1;
						$description = $items[$i]['Product']['message'];
						$month = $items[$i]['Product']['month'];
						$year = $items[$i]['Product']['year'];
						$iva = $items[$i]['Product']['iva'];
						$scholarship = round($items[$i]['Product']['scholarship'],2);
						$exoneration = 0;
						$transport_id = null;

						if ($product == 'Matricula') {
							if ($items[$i]['Product']['exoneration'] == 1) $exoneration = $items[$i]['Product']['cost'];
						}

						if ($product == 'Transporte')
							$transport_id = $this->RoutesTransport->getTransportByIdRouteTransport($data['student']['routes_transport_id']);
						
						if ($total_credit != 0 && $items[$i]['Product']['status'] == 'subscriber') {
							$pending = $items[$i]['Product']['total'] - $items[$i]['Product']['paid'];
							//$pending = $pending / (1 + $iva /100);
							//$cost_pending = $pending / $quantity;
							//$cost_pending = round($cost_pending,2);
							//$items[$i]['Product']['cost'] = $cost_pending;
							$items[$i]['Product']['cost'] = $pending;
							$items[$i]['Product']['message'] = "Deuda pendiente del item de la factura ".$bill_data['bill_code'];
							$description = "Se realizo un abono";

							//$paid = $items[$i]['Product']['paid'] / (1 + $iva /100);
							//$cost = $paid / $quantity;
							//$cost = round($cost,2);
							$cost = round($items[$i]['Product']['paid'],2);
							$items_pending[] = $items[$i]; 
						}
						if($student_id != "")
							$this->BillData->saveDetailBill($bill_id,$student_id,$product,$description,$cost,$exoneration,
											$scholarship,$quantity,$month,$year,$transport_id,$iva);
					}			
				}

				if(!empty($bill_data['credit'])){
					$total_payment_order = $bill_data['total_payment'] - $bill_data['credit'];
					
					if ($total_payment_order != 0) {
						$total_payment_order = round($total_payment_order,2);

						$observation = "Se genero una orden de pago por la deuda de la factura ".$bill_data['bill_code']."\n";
						$order_id = $this->BillData->saveOrder($responsable_id,$total_payment_order,$observation);
						
						for($i=0; $i < count($items_pending); $i++){
							$transport_id = null;
							if ($items_pending[$i]['Product']['name'] == 'Transporte')
								$transport_id = $this->RoutesTransport->getTransportByIdRouteTransport($data['student']['routes_transport_id']);

							$this->BillData->saveDetailOrder($order_id,$student_id,$items_pending[$i]['Product']['name'],
								$items_pending[$i]['Product']['message'],$items_pending[$i]['Product']['cost'],
								1,$items_pending[$i]['Product']['month'],$items_pending[$i]['Product']['year'],
								$transport_id,$items_pending[$i]['Product']['iva']);

							$this->Student->id = $student_id;
							$this->Student->saveField('status',2);
							$this->Student->saveField('observation','El estudiante posee una deuda');
	             			$this->Student->saveField('date_modification',date('Y-m-d'));

						}
					}
				}

				if($total_payment != 0) $this->BillData->updateCodeInitiator(1);
				$data_response = array("bill_id" => $bill_id, "student_id" => $student_id);
				echo json_encode($data_response);
			}
		}

		public function cancelInvoice(){
			if ($this->Auth->user('role_id') != 3) {
				return $this->redirect(
		            array('controller' => 'home', 'action' => 'index')
		        );
			}
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$bill = $this->data['bill_id'];
				$generate = $this->data['generate'];
             	$this->Bill->id = $bill;
             	$cancel = false;
             	$students_id = array();
             	$closing = $this->DetailsClosing->find('first',array('conditions' => array('bill_id' => $bill)));

             	if (empty($closing)){
             		$cancel = true;
             		$data_bill = $this->Bill->find('first', array('conditions' => array('id' => $bill)));
             		$details_bill = $this->DetailsBill->find('all', array('conditions' => array('bill_id' => $bill)));

             		if ($generate == 'true') {

						$order_id = $this->BillData->saveOrder($data_bill['Bill']['people_id'],$data_bill['Bill']['total'],
														$data_bill['Bill']['observation']);
	
             			foreach ($details_bill as $detail) {
             				
             				$this->BillData->saveDetailOrder($order_id,$detail['DetailsBill']['student_id'],
             									$detail['DetailsBill']['product'],$detail_bill['DetailsBill']['description'],
             									$detail['DetailsBill']['cost'],$detail['DetailsBill']['quantity'],
												$detail['DetailsBill']['month'],$detail['DetailsBill']['year'],
												$detail['DetailsBill']['transport_id'],$detail['DetailsBill']['iva']);
             				
             				if (!empty($detail['DetailsBill']['student_id'])) {
             					if (!in_array($students_id, $detail['DetailsBill']['student_id'])) 
             						array_push($students_id, $detail['DetailsBill']['student_id']);	
             				}
             			}

             		} else {
             			
             			foreach ($details_bill as $detail) {
             				if (!empty($detail['DetailsBill']['student_id'])) {
             					if (!in_array($students_id, $detail['DetailsBill']['student_id'])) 
             						array_push($students_id, $detail['DetailsBill']['student_id']);	
             				}
             			}

             		}
	             	
	             	$this->Bill->saveField('status',2);
	             	$this->Bill->saveField('date_modification',date('Y-m-d'));

	             	if (!empty($students_id)) {
	             		for ($i=0; $i < count($students_id); $i++) { 
	             			$orders = $this->Order->getPendingPaymentsByStudent($students_id[$i]);
							$status = 2;
	             			if (empty($orders)) $status = 1;
	             			
	             			$this->Student->id = $students_id[$i];
	             			$this->Student->saveField('status',$status);
	             			$this->Student->saveField('date_modification',date('Y-m-d'));	
	             		}
	             	}
             	}

   				echo $cancel;
			}
		}

		public function bill(){}

        public function reportBill(){
        	if ($this->request->is('post')) {
        		$data = $this->request->data;
	            $date_from = $data['date_from'];
	            $date_until = $data['date_until'];
	            $bill_code_from = $data['bill_code_from'];
	            $bill_code_until = $data['bill_code_until'];
	            $bills = "";

				if (!empty($date_from) && !empty($date_until) && !empty($bill_code_from) && !empty($bill_code_until)) {
	            	if ($bill_code_until < $bill_code_from) {
	            		$bill_code_from = $data['bill_code_until'];
	            		$bill_code_until = $data['bill_code_from']; 
	            	}
	            	$bills = $this->DetailsBill->getBillsReportByRangeInvoicesAndRangeDates($bill_code_from,$bill_code_until,$date_from,$date_until);
	            } elseif (empty($date_from) && empty($date_until) && !empty($bill_code_from) && !empty($bill_code_until)) {
	            	if ($bill_code_until < $bill_code_from) {
	            		$bill_code_from = $data['bill_code_until'];
	            		$bill_code_until = $data['bill_code_from']; 
	            	}
	            	$bills = $this->DetailsBill->getBillsReportByRangeInvoices($bill_code_from,$bill_code_until);
	            } elseif (!empty($date_from) && !empty($date_until) && empty($bill_code_from) && empty($bill_code_until)) {
	            	$bills = $this->DetailsBill->getBillsReportByRangeDates($date_from,$date_until);
	            }
	            
				$bills_new = array();

				for ($i=0; $i < count($bills); $i++) {
					$modes_bills = $this->InvoicesPayment->getInvoicesPaymentBill($bills[$i]['b']['id']);
					$modes = array();
					$observation = "";
					foreach ($modes_bills as $mode_bill) {
						array_push($modes, $mode_bill['m']['name']);
						if (!empty($modes_bills['InvoicesPayment']['observation']))
							$observation .= $modes_bills['InvoicesPayment']['observation'];
					}

					$iva = $bills[$i]['DetailsBill']['iva'];
					$sub_total_iva = 0;
					$sub_total_14 = 0;
					$sub_total_0 = 0;
					if ($iva != 0) {
						$sub_total_14 = round($bills[$i][0]['sub_total'],2);
						$deduction = round($bills[$i][0]['deduction'],2);
						$sub_total_14 = $sub_total_14 - $bills[$i]['b']['credit'] - $deduction;
						$sub_total_iva = $sub_total_14 * ($iva / 100);
						$sub_total_iva = round($sub_total_iva,2);
					} else $sub_total_0 = round($bills[$i][0]['sub_total'],2);

					$total_amount = ($bills[$i]['b']['status'] == 3) ? $this->InvoicesPayment->getTotalAmount($bills[$i]['b']['id']) : 0;
					$data = array(
								'date_payment' => $bills[$i]['b']['date_payment'],
								'client' => $bills[$i]['p']['name'],
								'student_id' => $bills[$i]['DetailsBill']['student_id'],
								'student' => $bills[$i]['s']['name']." ".$bills[$i]['s']['lastname'],
								'bill_code' => $bills[$i]['b']['bill_code'],
								'status' => $bills[$i]['b']['status'],
								'total_payment' =>  $bills[$i]['b']['total'],
								'sub_total_14' => $sub_total_14,
								'sub_total_0' => $sub_total_0,
								'sub_total_iva' => $sub_total_iva,
								'total' =>  $sub_total_14 + $sub_total_0 + $sub_total_iva,
								'total_amount' =>  $total_amount,
								'modes_bills' => $modes,
								'observation' => $observation
							);
					if (empty($bills_new)) array_push($bills_new, $data);
					else {
						$student_position = "";

						for ($j=0; $j < count($bills_new); $j++) { 
							if ($bills[$i]['DetailsBill']['student_id'] == $bills_new[$j]['student_id'] &&
								$bills[$i]['b']['bill_code'] == $bills_new[$j]['bill_code']) $student_position = $j;
						}

						if (empty($student_position)) array_push($bills_new, $data);
						else {
							if ($iva != 0) {
								$bills_new[$student_position]['sub_total_14'] = $sub_total_14;
								$bills_new[$student_position]['sub_total_iva'] = $sub_total_iva;
							} else $bills_new[$student_position]['sub_total_0'] = $sub_total_0;

							$bills_new[$student_position]['total'] = $bills_new[$student_position]['total'] + $sub_total_14 + $sub_total_0 + $sub_total_iva;
							for ($k=0; $k < count($modes); $k++) {
								if (!in_array($modes[$k], $bills_new[$student_position]['modes_bills']))
									$bills_new[$student_position]['modes_bills'][] = $modes[$k];	
							}
						}		
					}
				}

        		$this->pdfConfig = array(
					'download' => true,
					'orientation' => 'landscape',
					'pageSize' => 'Tabloid',
					'filename' => 'reporte_facturas.pdf'
				);

				$this->set('date_from',$date_from);
	            $this->set('date_until',$date_until);
	            $this->set('bill_code_from',$bill_code_from);
	            $this->set('bill_code_until',$bill_code_until);
	            $this->set('bills',$bills_new);
        	}
        }

		public function accountsReceivable(){}

		public function reportAccountsReceivable(){
        	if ($this->request->is('post')) {
        		$data = $this->request->data;
	            $date_from = $data['date_from'];
	            $date_until = $data['date_until'];
	            $accounts_receivable = $this->Bill->getAccountsReceivable($date_from,$date_until);

	            for ($i=0; $i < count($accounts_receivable); $i++) {
	            	$payment = $this->InvoicesPayment->getTotalAmount($accounts_receivable[$i]['Bill']['id']);
	            	$total_pending = $accounts_receivable[$i]['Bill']['total'] - $payment;
	            	$accounts_receivable[$i]['Bill']['payment'] = $payment;
	            	$accounts_receivable[$i]['Bill']['total_pending'] = $total_pending;
	            }

	            $this->pdfConfig = array(
					'download' => true,
					'filename' => 'reporte_cuentas_cobrar.pdf'
				);

	            $this->set('date_from',$date_from);
	            $this->set('date_until',$date_until);
	            $this->set('accounts_receivable',$accounts_receivable);
	        }
        }

        public function printBill($bill_id = ''){
			$data_bill = $this->Bill->getDataBill($bill_id);
			$details_bill = $this->DetailsBill->getDataDetail($bill_id);
			$iva_zero = 0;
			$sub_total = 0;
			$total_iva = 0;
			$iva = 0;
			$exoneration = 0;
			$scholarship = 0;
			$modes_bill = $this->InvoicesPayment->getInvoicesPaymentBill($bill_id);
			$modes_bill_str = "";
			$observation = "";
			if (!empty($modes_bill)) {
				$size_modes_bill = sizeof($modes_bill) - 1;
				for ($i=0; $i < count($modes_bill); $i++) { 
					if ($i == $size_modes_bill) {
						$modes_bill_str .= $modes_bill[$i]['m']['name'].":$".$modes_bill[$i]['InvoicesPayment']['subscribed_amount'];
						$observation .= (!empty($modes_bill[$i]['InvoicesPayment']['observation'])) ? $modes_bill[$i]['InvoicesPayment']['observation'].".\n" : "";
					} else {
						$modes_bill_str .= $modes_bill[$i]['m']['name'].":$".$modes_bill[$i]['InvoicesPayment']['subscribed_amount'].",";
						$observation .= (!empty($modes_bill[$i]['InvoicesPayment']['observation'])) ? $modes_bill[$i]['InvoicesPayment']['observation'].".\n" : "";
					}
				}
			}

			/*foreach ($details_bill as $detail):
				if($detail['DetailsBill']['iva'] == 0)
					$iva_zero += ($detail['DetailsBill']['cost'] * $detail['DetailsBill']['quantity']);
				else{
					$sub_total += ($detail['DetailsBill']['cost'] * $detail['DetailsBill']['quantity']);
					$iva = $detail['DetailsBill']['iva'];
				}

				if ($detail['DetailsBill']['scholarship'] != 0) $scholarship = round($detail['DetailsBill']['scholarship'],2);
				if ($detail['DetailsBill']['exoneration'] != 0) $exoneration = round($detail['DetailsBill']['exoneration'],2);
			endforeach;*/
			$exoneration_iva = 0;
			for ($i=0; $i < count($details_bill); $i++) { 
				if ($details_bill[$i]['DetailsBill']['iva'] == 0) {
					$total_iva_zero += ($details_bill[$i]['DetailsBill']['cost'] * $details_bill[$i]['DetailsBill']['quantity']);
					$details_bill[$i]['DetailsBill']['cost_item'] = $details_bill[$i]['DetailsBill']['cost'];
					$details_bill[$i]['DetailsBill']['sub_total_item'] = $details_bill[$i]['DetailsBill']['cost'] * $details_bill[$i]['DetailsBill']['quantity'];
				} else {
					$iva = $details_bill[$i]['DetailsBill']['iva'];
					$cost_item = $details_bill[$i]['DetailsBill']['cost'] / (1 + ( $details_bill[$i]['DetailsBill']['iva'] / 100));
					$cost_item = round($cost_item,2);
					$details_bill[$i]['DetailsBill']['cost_item'] = $cost_item;
					$details_bill[$i]['DetailsBill']['sub_total_item'] = $cost_item * $details_bill[$i]['DetailsBill']['quantity'];
					$sub_total += ($cost_item * $details_bill[$i]['DetailsBill']['quantity']);
					$sub_total_item = $cost_item * $details_bill[$i]['DetailsBill']['quantity'];
					//$total_iva += ($details_bill[$i]['DetailsBill']['cost'] * $details_bill[$i]['DetailsBill']['quantity']) - $sub_total_item;
					$scholarship_iva = 0;
					if ($details_bill[$i]['DetailsBill']['scholarship'] != 0){
						$scholarship = $details_bill[$i]['DetailsBill']['scholarship'] / (1 + ( $details_bill[$i]['DetailsBill']['iva'] / 100));;
						$scholarship = round($scholarship,2);
						$scholarship_iva = $details_bill[$i]['DetailsBill']['scholarship'] - $scholarship;
						$scholarship_iva = round($scholarship_iva,2);
						//$scholarship = round($details_bill[$i]['DetailsBill']['scholarship'],2);
					}

					if ($details_bill[$i]['DetailsBill']['exoneration'] != 0){
						$exoneration_cal = $details_bill[$i]['DetailsBill']['exoneration'] / (1 + ( $details_bill[$i]['DetailsBill']['iva'] / 100));;
						$exoneration_cal = round($exoneration_cal,2); 
						$exoneration += $exoneration_cal;
						$exoneration_iva += $details_bill[$i]['DetailsBill']['exoneration'] - $exoneration_cal;
						//$exoneration += $exoneration;
						//$exoneration_iva += $details_bill[$i]['DetailsBill']['exoneration'] - $exoneration;
						//$exoneration_iva += $exoneration_iva;
						//$scholarship = round($details_bill[$i]['DetailsBill']['scholarship'],2);
					}
					//if ($detail['DetailsBill']['exoneration'] != 0) $exoneration = round($detail['DetailsBill']['exoneration'],2);

					$total_iva += ($details_bill[$i]['DetailsBill']['cost'] * $details_bill[$i]['DetailsBill']['quantity']) - $sub_total_item - $scholarship_iva;
				}	
			}

			$scholarship_bill = $this->ScholarshipsBill->find('first',array('conditions' => array('bill_id' => $bill_id)));
			$scholarship_total = (!empty($scholarship_bill)) ? $scholarship_bill['ScholarshipsBill']['scholarship_total'] : 0;
			//$total_iva = ($sub_total - $scholarship_total) * ($iva / 100);
			$scholarship_iva =  $scholarship_total * ($iva / 100);
			$scholarship_iva = round($scholarship_iva,2);
			$total_iva = $total_iva - $scholarship_iva - $exoneration_iva;
			$months = ["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Juio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
			//$scholarship_bill = $this->ScholarshipsBill->find('first',array('conditions' => array('bill_id' => $bill_id)));
			//$scholarship_total = (!empty($scholarship_bill)) ? $scholarship_bill['ScholarshipsBill']['scholarship_total'] : 0;
			
			//$total_iva = ($sub_total - $exoneration - $scholarship - $data_bill['Bill']['credit'] - $scholarship_total) * ($iva / 100);
			
			$total_payment = round($data_bill['Bill']['total'],2);
			$total_iva = round($total_iva,2);
        	$this->pdfConfig = array(
				'download' => false,
				'filename' => 'factura.pdf'
			);

			$this->set('data_bill',$data_bill);
			$this->set('details_bill',$details_bill);
			$this->set('iva_zero',$iva_zero);
			$this->set('sub_total',$sub_total);
			$this->set('total_iva',$total_iva);
			$this->set('iva',$iva);
			$this->set('exoneration',$exoneration);
			$this->set('scholarship',$scholarship);
			$this->set('months',$months);
			$this->set('total_payment',$total_payment);
			$this->set('modes_bill_str',$modes_bill_str);
			$this->set('observation',$observation);
			$this->set('scholarship_bill',$scholarship_bill);
		}

		public function printBillStudent($bill_id = '') {
			$data_bill = $this->Bill->getDataBill($bill_id);
			$details_bill = $this->DetailsBill->find('all', array('conditions' => array('bill_id' => $bill_id)));
			$student = $this->Student->getStudentById($details_bill[0]['DetailsBill']['student_id']);
			$iva_zero = 0;
			$sub_total = 0;
			$total_iva = 0;
			$iva = 0;
			$exoneration = 0;
			$scholarship = 0;
			$modes_bill = $this->InvoicesPayment->getInvoicesPaymentBill($bill_id);
			$modes_bill_str = "";
			$observation = "";
			if (!empty($modes_bill)) {
				$size_modes_bill = sizeof($modes_bill) - 1;
				for ($i=0; $i < count($modes_bill); $i++) { 
					if ($i == $size_modes_bill) {
						$modes_bill_str .= $modes_bill[$i]['m']['name'].":$".$modes_bill[$i]['InvoicesPayment']['subscribed_amount'];
						$observation .= (!empty($modes_bill[$i]['InvoicesPayment']['observation'])) ? $modes_bill[$i]['InvoicesPayment']['observation'].".\n" : "";
					} else {
						$modes_bill_str .= $modes_bill[$i]['m']['name'].":$".$modes_bill[$i]['InvoicesPayment']['subscribed_amount'].",";
						$observation .= (!empty($modes_bill[$i]['InvoicesPayment']['observation'])) ? $modes_bill[$i]['InvoicesPayment']['observation'].".\n" : "";
					}
				}
			}
			
			/*foreach ($details_bill as $detail):
				if($detail['DetailsBill']['iva'] == 0)
					$iva_zero += $detail['DetailsBill']['cost'] * $detail['DetailsBill']['quantity'];
				else{
					$sub_total += $detail['DetailsBill']['cost'] * $detail['DetailsBill']['quantity'];
					$iva = $detail['DetailsBill']['iva'];				
				}

				if ($detail['DetailsBill']['scholarship'] != 0) $scholarship = round($detail['DetailsBill']['scholarship'],2);
				if ($detail['DetailsBill']['exoneration'] != 0) $exoneration = round($detail['DetailsBill']['exoneration'],2);
			endforeach;*/
			for ($i=0; $i < count($details_bill); $i++) { 
				if ($details_bill[$i]['DetailsBill']['iva'] == 0) {
					$iva_zero += ($details_bill[$i]['DetailsBill']['cost'] * $details_bill[$i]['DetailsBill']['quantity']);
					$details_bill[$i]['DetailsBill']['cost_item'] = $details_bill[$i]['DetailsBill']['cost'];
					$details_bill[$i]['DetailsBill']['sub_total_item'] = $details_bill[$i]['DetailsBill']['cost'] * $details_bill[$i]['DetailsBill']['quantity'];
				} else {
					$iva = $details_bill[$i]['DetailsBill']['iva'];
					$cost_item = $details_bill[$i]['DetailsBill']['cost'] / (1 + ( $details_bill[$i]['DetailsBill']['iva'] / 100));
					$cost_item = round($cost_item,2);
					$details_bill[$i]['DetailsBill']['cost_item'] = $cost_item;
					$details_bill[$i]['DetailsBill']['sub_total_item'] = $cost_item * $details_bill[$i]['DetailsBill']['quantity'];
					$sub_total += ($cost_item * $details_bill[$i]['DetailsBill']['quantity']);
					$sub_total_item = $cost_item * $details_bill[$i]['DetailsBill']['quantity'];
					$scholarship_iva = 0;
					if ($details_bill[$i]['DetailsBill']['scholarship'] != 0){
						$scholarship = $details_bill[$i]['DetailsBill']['scholarship'] / (1 + ( $details_bill[$i]['DetailsBill']['iva'] / 100));;
						$scholarship = round($scholarship,2);
						$scholarship_iva = $details_bill[$i]['DetailsBill']['scholarship'] - $scholarship;
						$scholarship_iva = round($scholarship_iva,2);
						//$scholarship = round($details_bill[$i]['DetailsBill']['scholarship'],2);
					}

					$exoneration_iva = 0;
					if ($details_bill[$i]['DetailsBill']['exoneration'] != 0){
						$exoneration = $details_bill[$i]['DetailsBill']['exoneration'] / (1 + ( $details_bill[$i]['DetailsBill']['iva'] / 100));;
						$exoneration = round($exoneration,2);
						$exoneration_iva = $details_bill[$i]['DetailsBill']['exoneration'] - $exoneration;
						$exoneration_iva = round($exoneration_iva,2);
						//$scholarship = round($details_bill[$i]['DetailsBill']['scholarship'],2);
					}
					//if ($detail['DetailsBill']['exoneration'] != 0) $exoneration = round($detail['DetailsBill']['exoneration'],2);

					$total_iva += ($details_bill[$i]['DetailsBill']['cost'] * $details_bill[$i]['DetailsBill']['quantity']) - $sub_total_item - $scholarship_iva - $exoneration_iva;
				}	
			}
			$months = ["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Juio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];

			$total_payment = round($data_bill['Bill']['total'],2);

			$scholarship_bill = $this->ScholarshipsBill->find('first',array('conditions' => array('bill_id' => $bill_id)));
			$scholarship_total = (!empty($scholarship_bill)) ? $scholarship_bill['ScholarshipsBill']['scholarship_total'] : 0;
			$scholarship_iva =  $scholarship_total * ($iva / 100);
			$scholarship_iva = round($scholarship_iva,2);
			$total_iva = $total_iva - $scholarship_iva;
			//$total_iva = ($sub_total - $exoneration - $scholarship - $data_bill['Bill']['credit'] - $scholarship_total) * ($iva / 100);
        	
        	$this->pdfConfig = array(
				'download' => false,
				'filename' => 'factura_estudiante_' . $student['Student']['name'] . "_" . $student['Student']['lastname'] .'.pdf'
			);

			$this->set('data_bill',$data_bill);
			$this->set('details_bill',$details_bill);
			$this->set('student',$student);
			$this->set('iva_zero',$iva_zero);
			$this->set('sub_total',$sub_total);
			$this->set('total_iva',$total_iva);
			$this->set('exoneration',$exoneration);
			$this->set('scholarship',$scholarship);
			$this->set('iva',$iva);
			$this->set('months',$months);
			$this->set('total_payment',$total_payment);
			$this->set('modes_bill_str',$modes_bill_str);
			$this->set('observation',$observation);
			$this->set('scholarship_bill',$scholarship_bill);
		}

		public function printBillClient($bill_id = '') {
			$data_bill = $this->Bill->getDataBillClient($bill_id);
			$details_bill = $this->DetailsBill->find('all', array('conditions' => array('bill_id' => $bill_id)));
			$iva_zero = 0;
			$sub_total = 0;
			$total_iva = 0;
			$iva = 0;
			$modes_bill = $this->InvoicesPayment->getInvoicesPaymentBill($bill_id);
			$modes_bill_str = "";
			$observation = "";
			if (!empty($modes_bill)) {
				$size_modes_bill = sizeof($modes_bill) - 1;
				for ($i=0; $i < count($modes_bill); $i++) { 
					if ($i == $size_modes_bill) {
						$modes_bill_str .= $modes_bill[$i]['m']['name'].":$".$modes_bill[$i]['InvoicesPayment']['subscribed_amount'];
						$observation .= (!empty($modes_bill[$i]['InvoicesPayment']['observation'])) ? $modes_bill[$i]['InvoicesPayment']['observation'].".\n" : "";
					} else {
						$modes_bill_str .= $modes_bill[$i]['m']['name'].":$".$modes_bill[$i]['InvoicesPayment']['subscribed_amount'].",";
						$observation .= (!empty($modes_bill[$i]['InvoicesPayment']['observation'])) ? $modes_bill[$i]['InvoicesPayment']['observation'].".\n" : "";
					}
				}
			}

			/*foreach ($details_bill as $detail):
				if($detail['DetailsBill']['iva'] == 0)
					$iva_zero += ($detail['DetailsBill']['cost'] * $detail['DetailsBill']['quantity']);
				else{
					$sub_total += ($detail['DetailsBill']['cost'] * $detail['DetailsBill']['quantity']);
					$iva = $detail['DetailsBill']['iva'];
				}
					
			endforeach;*/
			for ($i=0; $i < count($details_bill); $i++) { 
				if ($details_bill[$i]['DetailsBill']['iva'] == 0) {
					$total_iva_zero += ($details_bill[$i]['DetailsBill']['cost'] * $details_bill[$i]['DetailsBill']['quantity']);
					$details_bill[$i]['DetailsBill']['cost_item'] = $details_bill[$i]['DetailsBill']['cost'];
					$details_bill[$i]['DetailsBill']['sub_total_item'] = $details_bill[$i]['DetailsBill']['cost'] * $details_bill[$i]['DetailsBill']['quantity'];
				} else {
					$iva = $details_bill[$i]['DetailsBill']['iva'];
					$cost_item = $details_bill[$i]['DetailsBill']['cost'] / (1 + ( $details_bill[$i]['DetailsBill']['iva'] / 100));
					$cost_item = round($cost_item,2);
					$details_bill[$i]['DetailsBill']['cost_item'] = $cost_item;
					$details_bill[$i]['DetailsBill']['sub_total_item'] = $cost_item * $details_bill[$i]['DetailsBill']['quantity'];
					$sub_total += ($cost_item * $details_bill[$i]['DetailsBill']['quantity']);
					$sub_total_item = $cost_item * $details_bill[$i]['DetailsBill']['quantity'];
					$total_iva += ($details_bill[$i]['DetailsBill']['cost'] * $details_bill[$i]['DetailsBill']['quantity']) - $sub_total_item;
				}	
			}

			$scholarship_bill = $this->ScholarshipsBill->find('first',array('conditions' => array('bill_id' => $bill_id)));
			$scholarship_total = (!empty($scholarship_bill)) ? $scholarship_bill['ScholarshipsBill']['scholarship_total'] : 0;
			//$total_iva = ($sub_total - $scholarship_total) * ($iva / 100);
			$scholarship_iva =  $scholarship_total * ($iva / 100);
			$scholarship_iva = round($scholarship_iva,2);
			$total_iva = $total_iva - $scholarship_iva;

        	$this->pdfConfig = array(
				'download' => false,
				'filename' => 'factura_cliente_' . $data_bill['p']['name'] .'.pdf'
			);
			$this->set('data_bill',$data_bill);
			$this->set('details_bill',$details_bill);
			$this->set('iva_zero',$iva_zero);
			$this->set('sub_total',$sub_total);
			$this->set('iva',$iva);
			$this->set('total_iva',$total_iva);
			$this->set('modes_bill_str',$modes_bill_str);
			$this->set('observation',$observation);
			$this->set('scholarship_bill',$scholarship_bill);
		}

		public function verifyInvoices(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$number_bills = $this->Bill->unopenedInvoices();
				$number_receipts = $this->Receipt->unopenedReceipts();
				$number_bills = $number_bills + $number_receipts;
				echo $number_bills;
			}
		}

		public function getProductStudent(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$student_id = $this->data['student_id'];
				$this->Student->recursive = -1;
				$student = $this->Student->find('first', array('conditions' => array('id' => $student_id)));

				$products = array(); 

				$date_inscription = strtotime($student['Student']['date_inscription']);
	            $now = time();
	            $date_diff = $now - $date_inscription;
	            $days = floor($date_diff / (60 * 60 * 24));
	            if($days > 350 && $student['Student']['status'] == 1) {
	            	$cost = $this->Parameter->getValueParameter(2);
					$cost = round($cost,2);
	                $product = array('Product' => array('name' => 'Matricula', 'cost' => $cost, 
	                				'description' => 'Pago de renovación de matricula', 'prefix' => 'pr-Mat'));
					array_push($products, $product);
	            }

				if ($student['Student']['fitness'] == 1) {
					$parameter_id = 5;
					$cost = $this->Parameter->getValueParameter($parameter_id);
					$cost = round($cost,2);
					$name_category = $this->Category->getNameCategory($student['Student']['category_id']);
					if ($name_category == "Fitness" && !empty($student['Student']['scholarship_id'])) {
						$scholarship = 0;
						$scholarship = $this->Scholarship->getValueScholarship($student['Student']['scholarship_id']);
						$cost = $cost - (($cost * $scholarship) /100);
						$cost = round($cost,2);	
					}
					$product = array('Product' => array('name' => 'Fitness', 'cost' => $cost, 'description' => 'Pago Fitness', 'prefix' => 'pr-Fit'));
					array_push($products, $product);
				}

				if (!empty($student['Student']['training_mode_id'])) {
					$parameter_id = "";
					if ($student['Student']['training_mode_id'] == 2) $parameter_id = 6;
					else if ($student['Student']['training_mode_id'] == 3) $parameter_id = 3;

					$cost = $this->Parameter->getValueParameter($parameter_id);
					$cost = round($cost,2);
					$scholarship = 0;
					if(!empty($student['Student']['scholarship_id'])){
						$scholarship = $this->Scholarship->getValueScholarship($student['Student']['scholarship_id']);
						$cost = $cost - (($cost * $scholarship) /100);
						$cost = round($cost,2);
					} else {
						//Discount by siblings
						$siblings = $this->Student->getSiblings($student['Student']['document_number'],$student_id);
						
						$multiplier = 1;
						if ($siblings == 1)	$multiplier = 0.9;
						else if($siblings > 1) $multiplier = 0.8;
						
						$cost = $cost * $multiplier;
						$cost = round($cost,2);
					}
					$product = array('Product' => array('name' => 'Pension', 'cost' => $cost, 'description' => 'Pago de pension', 'prefix' => 'pr-Pen'));
					array_push($products, $product);
				}

				if (!empty($student['Student']['routes_transport_id'])) {
					$routes_transport_id = $student['Student']['routes_transport_id'];
					$this->Route->recursive = -1;
			        $route_transport = $this->Route->getRoute($routes_transport_id);
			        $cost = $route_transport['Route']['cost'];
			        $cost = round($cost,2);
					$product = array('Product' => array('name' => 'Transporte', 'cost' => $cost, 'description' => 'Pago Transporte', 'prefix' => 'pr-Trans-'.$routes_transport_id));
					array_push($products, $product);
				}

				if ($student['Student']['extra_training'] == 1) {
					$parameter_id = 7;
					$cost = $this->Parameter->getValueParameter($parameter_id);
					$cost = round($cost,2);
					$product = array('Product' => array('name' => 'Extra Training', 'cost' => $cost, 'description' => 'Pago Extra Training', 'prefix' => 'pr-ET'));
					array_push($products, $product);
				}

				$products_school = $this->Product->find('all',array('conditions' => array('status' => 1),'fields' => array('id','name','cost','description')));
				for ($i=0; $i < count($products_school) ; $i++) { 
					$products_school[$i]['Product']['prefix'] = "pr-".$products_school[$i]['Product']['id'];
					array_push($products, $products_school[$i]);
				}

				echo json_encode($products);
			}
		}

		public function getLastMonthPaidProduct(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$data = $this->data;
				$student_id = $data['student_id'];
				$product = $data['product'];
				$last_month_paid = "";
				$disabled_student = $this->DisabledsStudent->find('first', array('conditions' => array('student_id' => $student_id)));
				if (empty($disabled_student)) {
					$last_month_paid = $this->DetailsBill->getLastMonthPaid($product,$student_id);
					$last_month_paid = $last_month_paid['DetailsBill']['month'];
				} else {
                    $date_disabled = $disabled_student['DisabledsStudent']['date_disabled'];
					$last_month_paid = explode('-',$date_disabled);
					$last_month_paid = $last_month_paid[1];
					/*if ($last_month_paid['DetailsBill']['month'] >= date('n')) $last_month_paid = $last_month_paid['DetailsBill']['month'];
					elseif (date('n') < $last_month_paid['DetailsBill']['month'] && date('Y') > $last_month_paid['DetailsBill']['year']) {
						if (date('n') == 1) $last_month_paid = 12;
						else $last_month_paid = date('n') - 1;
					} elseif ($last_month_paid['DetailsBill']['month'] < date('n') && date('Y') > $last_month_paid['DetailsBill']['year'])
						$last_month_paid = date('n') - 1;*/

				} 				
				echo $last_month_paid;
			}
		}

		public function searchBill(){
			$term = null;
			if(!empty($this->request->query['term'])){
				$term = $this->request->query['term'];
				$terms = explode(' ', trim($term));
				$terms = array_diff($terms, array(''));
				foreach($terms as $term) {
					$conditions[] = array('Bill.bill_code LIKE' => '%' . $term . '%');
				}
				
				$bills = $this->Bill->find('all', array(
														'recursive' => -1, 
														'fields' => array('Bill.id', 'Bill.bill_code'), 
														'conditions' => $conditions, 
														'limit' => 20
												));
			}
			echo json_encode($bills);
			$this->autoRender = false;
		}

	}
