<?php  

	/**
	* 
	*/
	class OrdersController extends AppController
	{
		var $uses = array('Order','DetailsOrder','ModesBill','Student','Parameter','Scholarship',
			'ScholarshipsBill','Credit','DetailsBill','RoutesTransport','Flash');

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
			$pendings_payments = $this->Order->getAllPendingPayments();
			$this->set('size',sizeof($pendings_payments));
			$this->set('pendings_payments',$pendings_payments);
		}

		public function detailOrder($order_id = ''){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			$pending_payment = $this->Order->getPendingPayment($order_id);
			$detail_pending = $this->DetailsOrder->getDataDetail($order_id);
			$total_iva_zero = 0;
			$iva = 0;
			$total_iva = 0;
			$sub_total = 0;
			/*foreach ($detail_pending as $detail) {
				if ($detail['DetailsOrder']['iva'] == 0) $total_iva_zero += $detail['DetailsOrder']['cost'];
				else{
					$total_iva += $detail['DetailsOrder']['cost'] * ($detail['DetailsOrder']['iva'] / 100);
					$sub_total += $detail['DetailsOrder']['cost'];
					$iva = $detail['DetailsOrder']['iva'];
				}
			}*/
			for ($i=0; $i < count($detail_pending); $i++) { 
				if ($detail_pending[$i]['DetailsOrder']['iva'] == 0) $total_iva_zero += $detail_pending[$i]['DetailsOrder']['cost'];
				else{
					$cost_item = $detail_pending[$i]['DetailsOrder']['cost'] / (1 + ($detail_pending[$i]['DetailsOrder']['iva'] / 100));
					$cost_item = round($cost_item,2);
					$detail_pending[$i]['DetailsOrder']['cost_item'] = $cost_item;
					$detail_pending[$i]['DetailsOrder']['sub_total_item'] = $cost_item * $detail_pending[$i]['DetailsOrder']['quantity'];
					$sub_total_item = $cost_item * $detail_pending[$i]['DetailsOrder']['quantity'];
					//$total_iva += $detail_pending[$i]['DetailsOrder']['cost'] / ($detail_pending[$i]['DetailsOrder']['iva'] / 100);
					$sub_total += ($cost_item * $detail_pending[$i]['DetailsOrder']['quantity']);
					$total_iva += ($detail_pending[$i]['DetailsOrder']['cost'] * $detail_pending[$i]['DetailsOrder']['quantity']) - $sub_total_item;
					$iva = $detail_pending[$i]['DetailsOrder']['iva'];
				}
			}

			$total_iva = round($total_iva,2);
			$sub_total = round($sub_total,2);
			$months = ["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Juio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];

			$this->set('pending_payment',$pending_payment);
			$this->set('iva',$iva);
			$this->set('months',$months);
			$this->set('detail_pending',$detail_pending);
			$this->set('total_iva',$total_iva);
			$this->set('total_iva_zero',$total_iva_zero);
			$this->set('sub_total',$sub_total);
		}

		public function pendingPayments($student_id = ''){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			$pendings_payments = $this->Order->getPendingPaymentsByStudent($student_id);
			$this->set('size',sizeof($pendings_payments));
			$this->set('pendings_payments',$pendings_payments);
		}

		public function paymentOrder($order_id = null){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			if (!$order_id) throw new NotFoundException(__('Invalid order'));

	        $order = $this->Order->findById($order_id);

	        if (!$order) throw new NotFoundException(__('No se encontro la orden de pago'));

			$pending_payment = $this->Order->getPendingPayment($order_id);
			$modes_bills = $this->ModesBill->find('all');
			$bill_code = $this->BillData->generateBillCode();
			
			$status = array(1,2);
			$students = $this->Student->find('all',array('conditions' => array(
															'Student.document_number' => $pending_payment['p']['document_number'], 
															'Student.status' => $status),
														'fields' => array('id','name','lastname')));
			$iva = $this->Parameter->getValueParameter(1);
			$iva = round($iva,2);
			$this->Scholarship->recursive = -1;
			$scholarships = $this->Scholarship->find('all',array('conditions' => array('status !=' => 0)));
			$this->set('scholarships',$scholarships);
			$this->set('pending_payment',$pending_payment);
			$this->set('bill_code',$bill_code);
			$this->set('modes_bills',$modes_bills);
			$this->set('students',$students);
			$this->set('iva',$iva);
		}

		public function getDetails(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$order_id = $this->data['order_id'];
				$details_pending = $this->DetailsOrder->getDataDetail($order_id);
				$items = array();
				$months = ["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Juio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
				foreach ($details_pending as $detail) {
					$item = array('student' => $detail['s']['name']." ".$detail['s']['lastname'],
								  'student_id' => $detail['s']['id'],
								  'product' => $detail['DetailsOrder']['product'],
								  'quantity' => $detail['DetailsOrder']['quantity'],
								  'cost' => $detail['DetailsOrder']['cost'],
								  'description' => $detail['DetailsOrder']['description'],
								  'iva' => $detail['DetailsOrder']['iva'],
								  'transport_id' => $detail['DetailsOrder']['transport_id'],
								  'month' => $detail['DetailsOrder']['month'],
								  'month_str' => $months[$detail['DetailsOrder']['month']],
								  'year' => $detail['DetailsOrder']['year'],
								  'exoneration' => 0
								);
					array_push($items, $item);
				}
				echo json_encode($items);
			}
		}

		public function getOrdersStudent(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$data = $this->data;
				$order_id = $data['order_id'];
				$student_id = $data['student_id'];
				$order;
				$order_student = $this->Order->getPendingPaymentByStudent($student_id);

				if ($order_id != $order_student['Order']['id']) {
					$detail_order = $this->DetailsOrder->getDataDetail($order_student['Order']['id']);
					$items = array();
					$months = ["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Juio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
					foreach ($detail_order as $detail) {
						$item = array('student' => $detail['s']['name']." ".$detail['s']['lastname'],
									  'student_id' => $detail['s']['id'],
									  'product' => $detail['DetailsOrder']['product'],
									  'quantity' => $detail['DetailsOrder']['quantity'],
									  'cost' => $detail['DetailsOrder']['cost'],
									  'description' => $detail['DetailsOrder']['description'],
									  'iva' => $detail['DetailsOrder']['iva'],
									  'transport_id' => $detail['DetailsOrder']['transport_id'],
									  'month' => $detail['DetailsOrder']['month'],
									  'month_str' => $months[$detail['DetailsOrder']['month']],
									  'year' => $detail['DetailsOrder']['year'],
									  'exoneration' => 0
									);
						array_push($items, $item);
					}

					$order = array(
								'order_id' => $order_student['Order']['id'], 
								'detail_order' => $items
								);
				}
				
				echo json_encode($order);
			}
		}

        public function processOrder(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$data = $this->data;
				$bill_data = $data['bill_data'];
				$items = $data['items'];

				$total_payment = (empty($bill_data['credit'])) ? $bill_data['total_payment'] : $bill_data['credit'];
				$total_payment = round($total_payment,2);
				$code_itinial = $this->Parameter->getValueParameter(4);
				$bill_code = ($total_payment != 0) ? $bill_data['bill_code'] : $code_itinial.'0000000';
				$students_id = array();

				$orders = $data['orders'];
				$order;
				for ($i=0; $i < count($orders); $i++) { 
					$order = $this->Order->findById($orders[$i]);	
				}

				$observation = trim($bill_data['observation']);
				/*if ($bill_data['credit_notes'] != 0){
					$amount_credit_note = round($bill_data['credit_notes'],2);
					$observation .= "Se ha creado una nota de crédito por el monto de $".$amount_credit_note.".\n";
				}*/
				$bill_id = $this->BillData->saveBill($order['Order']['people_id'],$bill_code,2,
													$bill_data['date_payment'],$total_payment,0,$observation,1);

				if ($bill_data['credit_notes'] != 0) {
					$amount_credit_note = round($bill_data['credit_notes'],2);
        			$credit_note = $this->Credit->find('first',array('conditions' => array('people_id' => $order['Order']['people_id'])));
					if (empty($credit_note)) {
						$credit = array('Credit' => array(
        						'people_id' => $order['Order']['people_id'],
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
					$scholarship_total = $data_bill['scholarship_total'];
					$this->ScholarshipsBill->create();
					$scholarship_bill = array('bill_id' => $bill_id, 
											'percentage' => $bill_data['scholarship_str'], 
											'scholarship_total' => $bill_data['scholarship_total']);
					$this->ScholarshipsBill->save($scholarship_bill);
				}

				$modes_bill = $bill_data['modes_bill'];
				for ($i=0; $i < count($modes_bill); $i++) {
                    $total_payment = round($modes_bill[$i]['amount'],2);   
                    $this->BillData->savePayment($bill_id,$modes_bill[$i]['mode_bill'],$total_payment,$modes_bill[$i]['observation']);
                }
                
				$total_credit = $bill_data['credit'];
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
						$transport_id = $items[$i]['transport_id'];
						$iva = $items[$i]['iva'];
						$student_id = $items[$i]['student_id'];
						$exoneration = $items[$i]['exoneration'];
						if (!in_array($students_id, $student_id)) array_push($students_id, $student_id);

						if ($total_credit != 0 && $items[$i]['status'] == 'subscriber') {
							$pending = $items[$i]['total'] - $items[$i]['paid'];
							$pending = $pending / (1 + $iva /100);
							$cost_pending = $pending / $quantity;
							$cost_pending = round($cost_pending,2);
							$items[$i]['cost'] = $cost_pending;
							$items[$i]['description'] = "Deuda pendiente del item";
							$description = "Se realizo un abono";

							$paid = $items[$i]['paid'] / (1 + $iva /100);
							$cost = $paid / $quantity;
							$cost = round($cost,2);
							$items_pending[] = $items[$i]; 
						}

						if($student_id != ""){
							$this->BillData->saveDetailBill($bill_id,$student_id,$product,$description,$cost,$exoneration,0,
															$quantity,$month,$year,$transport_id,$iva);

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
						}
					}			
				}

				if(!empty($bill_data['credit'])){
					$total_payment_order = $bill_data['total_payment'] - $bill_data['credit'];
					if ($total_payment_order != 0) {
						$total_payment_order = round($total_payment_order,2);

						$observation = "Se genero una orden de pago por la deuda \n";
						$order_id = $this->BillData->saveOrder($order['Order']['people_id'],$total_payment_order,
															$observation);
						
						for($i=0; $i < count($items_pending); $i++){

							$this->BillData->saveDetailOrder($order_id,$items_pending[$i]['student_id'],
								$items_pending[$i]['product'],$items_pending[$i]['description'],
								$items_pending[$i]['cost'],$items_pending[$i]['quantity'],
								$items_pending[$i]['month'],$items_pending[$i]['year'],$items_pending[$i]['transport_id'],$items_pending[$i]['iva']);

							$this->Student->id = $items_pending[$i]['student_id'];
							$this->Student->saveField('status',2);
							$this->Student->saveField('observation','El estudiante posee una deuda');
	             			$this->Student->saveField('date_modification',date('Y-m-d'));

						}
					}
				}

				for ($i=0; $i < count($orders); $i++) { 
					$this->DetailsOrder->deleteAll(array('order_id' => $orders[$i]));
	             	$this->Order->id = $orders[$i];
	             	$this->Order->delete($orders[$i]);
				}
             	
             	for ($i=0; $i <count($students_id) ; $i++) { 
             		$pendings_payments = $this->Order->getPendingPaymentsByStudent($students_id[$i]);
					if(sizeof($pendings_payments) == 0){
						$this->Student->id = $students_id[$i];
						$this->Student->saveField('status',1);
						$this->Student->saveField('observation',null);
	             		$this->Student->saveField('date_modification',date('Y-m-d'));
					}	
             	}

				$this->BillData->updateCodeInitiator(1);
             	echo $bill_id;
			}
		}

		public function delete(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
             	$order_id = $this->data['order_id'];
             	$this->Order->id = $order_id;
             	$students = $this->DetailsOrder->find('all',array(
             										'conditions' => array('order_id' => $order_id),
             										'fields' => array('student_id'),
             										'group' => 'student_id'));
             	foreach ($students as $student) {
					$this->Student->id = $student['DetailsOrder']['student_id'];
					$this->Student->saveField('status',3);
					$this->Student->saveField('date_modification',date('Y-m-d'));
             	}

				$this->DetailsOrder->deleteAll(array('order_id' => $order_id));
             	$this->Order->delete($order_id);
            }
		}

		public function generateOrderStudent(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$data = $this->data;
				$student_id = $data['student_id'];

				$month = date('n');
				$year = date('Y');
				$IVA = $this->Parameter->getValueParameter(1);
				$items_student = array();
				$cost_transport = 0;
				$cost_fitness = 0;
				$cost_pension = 0;
				$cost_extra_training = 0;
				$sub_total = 0;
				$sub_total_iva = 0;
				$total_payment = 0;

				$this->Student->recursive = -1;
				$student = $this->Student->findById($student_id);
				
				$multiplier = 1;
	        	$schorlarship = 0;
	        	
	        	if(!empty($student['Student']['scholarship_id']))
	        		$schorlarship = $this->Scholarship->getValueScholarship($student['Student']['scholarship_id']);
	        	else {
	        		$siblings = $this->Student->getSiblings($student['Student']['document_number'], $student['Student']['id']);
	        		if($siblings == 1) $multiplier = 0.9;
	        		else if ($siblings > 1) $multiplier = 0.8;	
	        	}

	        	if (!empty($student['Student']['training_mode_id'])) {
					$cost_pension = $this->CalculateAmount->amountItem($student['Student']['training_mode_id']);

					$item = array('student_id' => $student['Student']['id'],'product' => 'Pension', 'quantity' => 1,
			        			  'cost' => $cost_pension,'iva' => $IVA, 'description' => 'Pago pendiente del mes',
			        			  'transport_id' => null, 'month' => $month, 'year' => $year);
			        
					$last_month_paid = $this->DetailsBill->getLastMonthPaid('Pension',$student['Student']['id']);
					array_push($items_student,$item);
						
				}

	        	if ($student['Student']['extra_training'] != 0) {
					$extra_training = $this->CalculateAmount->amountItem(7);

					$item = array('student_id' => $student['Student']['id'],'product' => 'Extra Training', 'quantity' => 1,
			        			  'cost' => $extra_training,'iva' => $IVA, 'description' => 'Pago pendiente del mes',
			        			  'transport_id' => null, 'month' => $month, 'year' => $year);
			        
					$last_month_paid = $this->DetailsBill->getLastMonthPaid('Extra Training',$student['Student']['id']);
					array_push($items_student,$item);
						
				}

	        	if ($student['Student']['fitness'] != 0) {
					$cost_fitness = $this->CalculateAmount->amountItem(5);
					if(empty($student['Student']['training_mode_id']) && !empty($student['Student']['scholarship_id']))
						$cost_fitness = $cost_fitness - ((($cost_fitness * $schorlarship) /100));

					$item = array('student_id' => $student['Student']['id'],'product' => 'Fitness', 'quantity' => 1,
			        			  'cost' => $cost_fitness,'iva' => $IVA, 'description' => 'Pago pendiente del mes',
			        			  'transport_id' => null, 'month' => $month, 'year' => $year);
			        
					$last_month_paid = $this->DetailsBill->getLastMonthPaid('Fitness',$student['Student']['id']);
					array_push($items_student,$item);
						
				}
				
				if (!empty($student['Student']['routes_transport_id'])) {
					$cost_transport = $this->CalculateAmount->amountTransport($student['Student']['routes_transport_id']);
					$last_month_paid = $this->DetailsBill->getLastMonthPaid('Transporte',$student['Student']['id']);
					$transport_id = $this->RoutesTransport->getTransportByIdRouteTransport($student['Student']['routes_transport_id']);
					
					$item = array('student_id' => $student['Student']['id'],'product' => 'Transporte', 'quantity' => 1,
			        			  'cost' => $cost_transport,'iva' => 0, 'description' => 'Pago pendiente del mes',
			        			  'transport_id' => $transport_id, 'month' => $month, 'year' => $year);
			        
					array_push($items_student,$item);
				}	
				
				if (sizeof($items_student) > 0) {
					$sub_total = $cost_pension + $cost_fitness + $cost_extra_training;
					$sub_total_iva = $sub_total + ($sub_total * ($IVA / 100));
					$total_payment = $sub_total_iva + $cost_transport;
					$total_payment = round($total_payment,2);
					$observation = "Se genero la orden de pago por la deuda del mes";
					$this->_generateOrder($student,$items_student,$total_payment,$observation);
				}
			}
		}

		public function _generateOrder($student,$items_student,$total_payment,$observation){
			$pendings_payments = $this->Order->getPendingPaymentsByStudent($student['Student']['id']);
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