<?php  

	/**
	* 
	*/
	class PaymentsController extends AppController
	{
		var $uses = array('People','TypesPayment','TypesRetentionsPercentage','TypesRetentionsSource','Parameter','ModesBill','Payment','Flash');

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
			$payments = $this->Payment->getAllPayments();
			$payments_expired = $this->Payment->getExpiredBills();
			$payments_to_expire = $this->Payment->invoicesToExpire();
			$size = sizeof($payments);
			$this->set('size',$size);
			$this->set('payments',$payments);
			$this->set('payments_expired',$payments_expired);
			$this->set('payments_to_expire',$payments_to_expire);
		}

		public function reportProvider(){
			if ($this->request->is('post')) {
	            $data = $this->request->data;
	            $date_from = $data['date_from'];
	            $date_until = $data['date_until'];
	            $providers_id = array();
            	$iva = 0;
            	$data_report = "";

            	if(!empty($data['Provider'])){
            		foreach ($data['Provider'] as $provider_id) {
	            		array_push($providers_id, $provider_id);
	            	}
            	}

            	if (!empty($data['Provider']) && $date_from != "" && $date_until != "")
            		$data_report = $this->Payment->getPayments($providers_id,$date_from,$date_until);
            	else if (!empty($data['Provider']) && $date_from == "" && $date_until == "")
            		$data_report = $this->Payment->getPaymentsByProviders($providers_id);
            	else if (empty($data['Provider']) && $date_from != "" && $date_until != "")
            		$data_report = $this->Payment->getPaymentsByDates($date_from,$date_until);
            	
            	foreach ($data_report as $key => $value) {
            		if (is_array($value)) {
            			foreach ($value as $index => $data) {
            				if ($index == 'Payment') {
            					$date_payment = $data_report[$key]['Payment']['date_payment'];
								$date_explode = explode("-", $date_payment);
								$new_date = $date_explode[2]."/".$date_explode[1]."/".$date_explode[0];
								$data_report[$key]['Payment']['date_payment'] = $new_date;
								$amount_iva = $data_report[$key]['Payment']['value_14'] * ($data_report[$key]['Payment']['iva'] / 100);
								$amount_iva = round($amount_iva,2);
								$iva = $data_report[$key]['Payment']['iva'];
								$data_report[$key]['Payment']['amount_iva'] = $amount_iva;
								
								$types_retentions_iva = $this->TypesRetentionsPercentage->find('all');
								foreach ($types_retentions_iva as $retention) {
									$percentage_retention_iva = $retention['TypesRetentionsPercentage']['value'];
									if ($percentage_retention_iva == $data_report[$key]['Payment']['percentage_retention_iva'])
										$data_report[$key]['Payment']['retention_iva_'.$percentage_retention_iva] = $amount_iva * ($percentage_retention_iva / 100);
									else $data_report[$key]['Payment']['retention_iva_'.$percentage_retention_iva] = 0;
								}

								$sub_total = $data_report[$key]['Payment']['value_14'] + $data_report[$key]['Payment']['value_0'];
								
								$types_retentions_sources = $this->TypesRetentionsSource->find('all');
								foreach ($types_retentions_sources as $retention) {
									$percentage_retention_source = $retention['TypesRetentionsSource']['value'];
									if ($percentage_retention_source == $data_report[$key]['Payment']['percentage_retention_source'])
										$data_report[$key]['Payment']['retention_source_'.$percentage_retention_source] = $sub_total * ($percentage_retention_source / 100);
									else $data_report[$key]['Payment']['retention_source_'.$percentage_retention_source] = 0;
								}
            				}
            			}
            		}
            	}

		        $this->pdfConfig = array(
							'download' => true,
							'orientation' => 'landscape',
							'pageSize' => 'Tabloid',
							'filename' => 'reporte_pago_proveedores.pdf'
						);

		        $this->set('data_report',$data_report);
            	$this->set('iva',$iva);
            	$this->set('date_from',$date_from);
            	$this->set('date_until',$date_until);
	        }
		}

		public function add(){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			if ($this->request->is('post')) {
	            $data = $this->request->data;
	            $payment = array('Payment' => array(
	            		"people_id" => $data['provider_id'],
	                    "type_payment_id" => $data['type_payment'],
	                    "mode_bill_id" => $data['mode_bill'],
	                    "bill_code" => $data['bill_code'],
	                    "retention_number" => $data['retention_number'],
	                    "value_14" => $data['value_14'],
	                    "value_0" => $data['value_0'],
	                    "iva" => $data['iva'],
	                    "percentage_retention_iva" => $data['retention_iva'],
	                    "percentage_retention_source" => $data['retention_source'],
	                    "total" => $data['total_payment'],
	                    "date_payment" => $data['date_payment'],
	                    "description" => $data['description'],
	                    "observation" => $data['observation'],
	                    "status" => $data['status_bill'],
	                    "date_created" => date('Y-m-d')));
				
				$this->Payment->create();

	            if ($this->Payment->save($payment)) {
	            	
	            	$description = "Se ha creado el pago con los siguientes datos: ";
                    $description .= " número de factura: ".$data['bill_code'];
                    $description .= " número de retención: ".$data['retention_number'];
                    $description .= " valor de 14%: ".$data['value_14'];
                    $description .= " valor de 0%: ".$data['value_0'];
                    $description .= " iva: ".$data['iva'];
                    $description .= " portencetaje de iva retenido: ".$data['retention_iva'];
                    $description .= " portencetaje de fuente retenido: ".$data['retention_source'];
                    $description .= " descripción: ".$data['description'];
                    $description .= " y su id correspondiente es : ".$this->Payment->id;
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

		public function edit($payment_id = ''){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			if (!$payment_id) throw new NotFoundException(__('Invalid payment'));

	        $payment = $this->Payment->getPayment($payment_id);

	        if (!$payment) throw new NotFoundException(__('No se encontro el pago'));

	        $visibility_types_retentions = 'hidden';
	        $sub_total = $payment['Payment']['value_14'] + $payment['Payment']['value_0'];
	        $sub_total_iva = $payment['Payment']['value_14'] * ($payment['Payment']['iva'] / 100);
	        $total_retention = $sub_total * ($payment['Payment']['percentage_retention_source'] / 100);
			$total_iva = $sub_total_iva * ($payment['Payment']['percentage_retention_iva'] / 100);
			$total = $sub_total - $total_retention - $total_iva + $sub_total_iva;
			
			if ($this->request->is(array('post', 'put'))) {
	            $this->Payment->id = $payment_id;
	            $data = $this->request->data;

	            if ($this->Payment->saveField('status',$data['status_bill'])) {
                    $this->Payment->saveField('description',$data['description']);
                    $this->Payment->saveField('observation',$data['observation']);
                    $this->Payment->saveField('date_modification',date('Y-m-d'));
	                $this->Flash->success('Se guardo correctamente');
	                return $this->redirect(array('action' => 'index'));
	            }
	            $this->Flash->danger('No se actualizó los datos de la factura');
	        }

			$this->set('payment',$payment);
			$this->set('sub_total',$sub_total);
			$this->set('sub_total_iva',$sub_total_iva);
			$this->set('total_iva',$total_iva);
			$this->set('total_retention',$total_retention);
			$this->set('total',$total);
			$this->set('visibility_types_retentions',$visibility_types_retentions);
		}

		public function providerBill($provider_id = ''){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			if (!$provider_id) throw new NotFoundException(__('Invalid provider'));

	        $provider = $this->People->findById($provider_id);

	        if (!$provider) throw new NotFoundException(__('No se encontro el proveedor'));

			$modes_bills = $this->ModesBill->find('all');
			$provider = $this->People->getProvider($provider_id);
			$types_retentions = $this->TypesRetentionsSource->find('all',array('conditions' => array('id !=' => 1)));
			$types_payments = "";
			$visibility_types_retentions = 'hidden';
			$iva = $this->Parameter->getValueParameter(1);
			$iva = round($iva,2);

			if($provider['tp']['id'] == 3){
				$types_payments = $this->TypesPayment->find('all',array('conditions' => array('id !=' => 1)));
				$visibility_types_retentions = 'visibility';
			} else $types_payments = $this->TypesPayment->find('all');

			$this->set('modes_bills',$modes_bills);
			$this->set('types_payments',$types_payments);
			$this->set('provider',$provider);
			$this->set('types_retentions',$types_retentions);
			$this->set('visibility_types_retentions',$visibility_types_retentions);
			$this->set('iva',$iva);
		}

		public function detailPayment($payment_id = ''){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			if (!$payment_id) throw new NotFoundException(__('Invalid payment'));

	        $payment = $this->People->findById($payment_id);

	        if (!$payment) throw new NotFoundException(__('No se encontro el pago'));

			$payment = $this->Payment->getPayment($payment_id);
			$date_payment = $payment['Payment']['date_payment'];
			$date_explode = explode("-", $date_payment);
			$new_date = $date_explode[2]."/".$date_explode[1]."/".$date_explode[0];
			$payment['Payment']['date_payment'] = $new_date;
			$amount_iva = $payment['Payment']['value_14'] * ($payment['Payment']['iva'] / 100);
			$amount_iva = round($amount_iva,2);
			$payment['Payment']['amount_iva'] = $amount_iva;
			
			$types_retentions_iva = $this->TypesRetentionsPercentage->find('all');
			foreach ($types_retentions_iva as $retention) {
				$percentage_retention_iva = $retention['TypesRetentionsPercentage']['value'];
				if ($percentage_retention_iva == $payment['Payment']['percentage_retention_iva'])
					$payment['Payment']['retention_iva_'.$percentage_retention_iva] = $amount_iva * ($percentage_retention_iva / 100);
				else $payment['Payment']['retention_iva_'.$percentage_retention_iva] = 0;
			}

			$sub_total = $payment['Payment']['value_14'] + $payment['Payment']['value_0'];
			
			$types_retentions_sources = $this->TypesRetentionsSource->find('all');
			foreach ($types_retentions_sources as $retention) {
				$percentage_retention_source = $retention['TypesRetentionsSource']['value'];
				if ($percentage_retention_source == $payment['Payment']['percentage_retention_source'])
					$payment['Payment']['retention_source_'.$percentage_retention_source] = $sub_total * ($percentage_retention_source / 100);
				else $payment['Payment']['retention_source_'.$percentage_retention_source] = 0;
			}
			$this->set('payment',$payment);
		}

		public function getRetentions(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$data = $this->data;
				$provider_id = $data['provider_id'];
				$type_payment = $data['type_payment_id'];
				$retention_iva = 0;
				$retention_source = 0;
				$retentions = array();
				$provider = $this->People->getProvider($provider_id);
				$type_accounting = $provider['ta']['id'];
				$type_provider = $provider['tp']['id'];
				//type_provider -> 1 - Natural -> 2 - Juridica -> 3 - Profesional -> 4 - RISE
				//type_payment -> 1 - Producto -> 2 - Servicio
				//type_accounting -> 1 -No Obligatorio -> 2 - Obligatorio
				if($type_provider == 1 && $type_payment == 1 && $type_accounting == 1){
					$retention = $this->TypesRetentionsPercentage->find('first',array('conditions' => array('id' => 2)));
					$retention_iva = $retention['TypesRetentionsPercentage']['value'];
				} elseif($type_provider == 1 && ($type_payment == 1 || $type_payment == 2) && $type_accounting == 2){
					$retention = $this->TypesRetentionsPercentage->find('first',array('conditions' => array('id' => 1)));
					$retention_iva = $retention['TypesRetentionsPercentage']['value'];
				} elseif($type_provider == 1 && $type_payment == 2 && $type_accounting == 1){
					$retention = $this->TypesRetentionsPercentage->find('first',array('conditions' => array('id' => 3)));
					$retention_iva = $retention['TypesRetentionsPercentage']['value'];
				} elseif($type_provider == 2 && ($type_payment == 1 || $type_payment == 2) && $type_accounting == 2){
					$retention = $this->TypesRetentionsPercentage->find('first',array('conditions' => array('id' => 1)));
					$retention_iva = $retention['TypesRetentionsPercentage']['value'];
				} elseif($type_provider == 3 && $type_payment == 2 && $type_accounting == 1){
					$retention = $this->TypesRetentionsPercentage->find('first',array('conditions' => array('id' => 4)));
					$retention_iva = $retention['TypesRetentionsPercentage']['value'];
				} elseif($type_provider == 3 && $type_payment == 1 && $type_accounting == 2){
					$retention = $this->TypesRetentionsPercentage->find('first',array('conditions' => array('id' => 1)));
					$retention_iva = $retention['TypesRetentionsPercentage']['value'];
				} elseif($type_provider == 4 && ($type_payment == 1 || $type_payment == 2) && $type_accounting == 1){
					$retention = $this->TypesRetentionsPercentage->find('first',array('conditions' => array('id' => 1)));
					$retention_iva = $retention['TypesRetentionsPercentage']['value'];
				}

				if($type_provider == 1 && $type_payment == 1 && ($type_accounting == 1 || $type_accounting == 2)){
					$retention = $this->TypesRetentionsSource->find('first',array('conditions' => array('id' => 1)));
					$retention_source = $retention['TypesRetentionsSource']['value'];	
				} elseif($type_provider == 1 && $type_payment == 2 && ($type_accounting == 1 || $type_accounting == 2)){
					$retention = $this->TypesRetentionsSource->find('first',array('conditions' => array('id' => 2)));
					$retention_source = $retention['TypesRetentionsSource']['value'];
				} elseif($type_provider == 2 && $type_payment == 1 && $type_accounting == 2){
					$retention = $this->TypesRetentionsSource->find('first',array('conditions' => array('id' => 1)));
					$retention_source = $retention['TypesRetentionsSource']['value'];
				} elseif($type_provider == 2 && $type_payment == 2 && $type_accounting == 2){
					$retention = $this->TypesRetentionsSource->find('first',array('conditions' => array('id' => 2)));
					$retention_source = $retention['TypesRetentionsSource']['value'];
				}
				
				$retentions['iva'] = intval($retention_iva);
				$retentions['source'] = $retention_source; 
				echo json_encode($retentions);
			}
		}

		public function cancelPayment(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$payment_id = $this->data['payment_id'];
             	$this->Payment->id = $payment_id;
             	$cancel = false;
             	if ($this->Payment->saveField('status',2)) {
             		$this->Payment->saveField('date_modification',date('Y-m-d'));
             		$cancel = true;
             	}

             	echo $cancel;
			}
		}

	}