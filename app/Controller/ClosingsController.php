<?php  

	/**
	* 
	*/
	class ClosingsController extends AppController
	{
		var $uses = array('Closing','DetailsClosing','ReceiptsClosing','CoinsClosing','InvoicesPayment','Receipt','Bill','Flash');
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
			$closings = $this->Closing->find('all',array('order' => array('id DESC')));
			$size = sizeof($closings);
			$this->set('closings',$closings);
			$this->set('size',$size); 
		}

		public function add(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

  			if(!empty($this->data)){
				$data = $this->data;
				$dates_closing = $data['dates_closing'];
				$this->Closing->create();
				date_default_timezone_set('America/Guayaquil');
				$hour = date('h:i:s');
				$closing = array('Closing' => array(
									"date_closing_start" => $dates_closing['date_start'],
				                    "date_closing_end" => $dates_closing['date_end'],
				                    "hour" => $hour,
				                    "total_closing" => $data['total'],
				                    "date_created" => date('Y-m-d')));
				$this->Closing->save($closing);
				$closing_id = $this->Closing->id;
				$bills = (isset($data['bills'])) ? $data['bills'] : "";
				if (!empty($bills)) {
					for ($i=0; $i < count($bills); $i++) { 
						for ($j=0; $j < count($bills[$i]); $j++) { 
							$bill_id = $bills[$i][$j]['b']['id'];
							$bill_closing = $this->DetailsClosing->find('first', array('conditions' => array('bill_id' => $bill_id)));
							if (empty($bill_closing)) {
								$detail_closing = array('DetailsClosing' => array(
					                    "closing_id" => $closing_id,
					                    "bill_id" => $bill_id));
					
								$this->DetailsClosing->create();
								$this->DetailsClosing->save($detail_closing);

								$this->Bill->id = $bill_id;
								$this->Bill->saveField('closed',1);
			             		$this->Bill->saveField('date_modification',date('Y-m-d'));
							}	
						}
					}
				}
				
				$receipts = (isset($data['receipts'])) ? $data['receipts'] : "";
				if (!empty($receipts)) {
					for ($i=0; $i < count($receipts); $i++) { 
						for ($j=0; $j < count($receipts[$i]); $j++){
							$receipt_id = $receipts[$i][$j]['r']['id'];
							$receipt_closing = $this->ReceiptsClosing->find('first', array('conditions' => array('receipt_id' => $receipt_id)));
							if (empty($receipt_closing)) {
								$detail_closing = array('ReceiptsClosing' => array(
					                    "closing_id" => $closing_id,
					                    "receipt_id" => $receipt_id));
								
								$bill_id = $this->Receipt->getBillByReceipt($receipt_id);
								$bill = $this->Bill->find('first',array('conditions' => array('id' => $bill_id), 'fields' => array('status')));
								$bill_status = $bill['Bill']['status'];
								if ($bill_status == 1) {
									$this->Bill->id = $bill_id;
									$this->Bill->saveField('closed',1);
			             			$this->Bill->saveField('date_modification',date('Y-m-d'));
								}
								$this->ReceiptsClosing->create();
								$this->ReceiptsClosing->save($detail_closing);

								$this->Receipt->id = $receipt_id;
								$this->Receipt->saveField('closed',1);
							}
						}
					}
				}
				$coins = $data['coins'];
				$quantity_hundred_dollars = $coins['quantity_hundred_dollars'];
				$this->_saveCoinsClosing($closing_id,100,$quantity_hundred_dollars);

				$quantity_fifty_dollars = $coins['quantity_fifty_dollars'];
				$this->_saveCoinsClosing($closing_id,50,$quantity_fifty_dollars);
				
				$quantity_twenty_dollars = $coins['quantity_twenty_dollars'];
				$this->_saveCoinsClosing($closing_id,20,$quantity_twenty_dollars);
	
				$quantity_ten_dollars = $coins['quantity_ten_dollars'];
				$this->_saveCoinsClosing($closing_id,10,$quantity_ten_dollars);
				
				$quantity_five_dollars = $coins['quantity_five_dollars'];
				$this->_saveCoinsClosing($closing_id,5,$quantity_five_dollars);

				$quantity_one_dollar = $coins['quantity_one_dollar'];
				$this->_saveCoinsClosing($closing_id,1,$quantity_one_dollar);
				
				$quantity_fifty_cents = $coins['quantity_fifty_cents'];
				$this->_saveCoinsClosing($closing_id,0.5,$quantity_fifty_cents);
				
				$quantity_twenty_five_cents = $coins['quantity_twenty_five_cents'];
				$this->_saveCoinsClosing($closing_id,0.25,$quantity_twenty_five_cents);
				
				$quantity_ten_cents = $coins['quantity_ten_cents'];
				$this->_saveCoinsClosing($closing_id,0.1,$quantity_ten_cents);
				
				$quantity_five_cents = $coins['quantity_five_cents'];
				$this->_saveCoinsClosing($closing_id,0.05,$quantity_five_cents);
				
				$quantity_one_cent = $coins['quantity_one_cent'];
				$this->_saveCoinsClosing($closing_id,0.01,$quantity_one_cent);
				
				$description = "Se ha creado el cierre";
                $description .= " desde la fecha: ".$dates_closing['date_start'];
                $description .= " hasta la fecha: ".$dates_closing['date_end'];
                $description .= " a las : ".$hour." horas";
                $description .= " con un monto total de : ".$data['total'];
                $description .= " y su id correspondiente es : ".$closing_id;
                $log = array('Log'=>array(
                    'user_id' => $this->Auth->user('id'),
                    'description' => $description
                ));
                $this->SaveLog->saveData($log);

  				echo $closing_id;
  			}
		}

		public function revertClose(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$data = $this->data;
				$close_id = $data['close_id'];
				$details_closings = $this->DetailsClosing->find('all',array('conditions' => array('closing_id' => $close_id)));
				foreach ($details_closings as $detail_closing) {
					$this->Bill->id = $detail_closing['DetailsClosing']['bill_id'];
					$this->Bill->saveField('closed',0);
	             	$this->Bill->saveField('date_modification',date('Y-m-d'));
				}
				
				$receipts_closings = $this->ReceiptsClosing->find('all',array('conditions' => array('closing_id' => $close_id)));
				foreach ($receipts_closings as $receipt_closing) {
					$this->ReceiptsClosing->id = $receipt_closing['ReceiptsClosing']['bill_id'];
					$this->ReceiptsClosing->saveField('closed',0);
				}

				$this->DetailsClosing->deleteAll(array('closing_id' => $close_id));
				$this->ReceiptsClosing->deleteAll(array('closing_id' => $close_id));
				$this->CoinsClosing->deleteAll(array('closing_id' => $close_id));
				$this->Closing->id = $close_id;
				$closing = $this->Closing->findById($close_id);

				$description = "Se ha revertido el cierre";
                $description .= " con fecha desde: ".$closing['Closing']['date_closing_start'];
                $description .= " hasta: ".$closing['Closing']['date_closing_end'];
                $description .= " con hora : ".$closing['Closing']['hour']." horas";
                $description .= " con un monto total de : ".$total;
                $description .= " y su id correspondiente es : ".$closing_id;
                $log = array('Log'=>array(
                    'user_id' => $this->Auth->user('id'),
                    'description' => $description
                ));
                $this->SaveLog->saveData($log);

				$this->Closing->delete($close_id);
				echo true;
			}
		}

		public function detailClosing($closing_id = ''){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			$this->Closing->recursive = -1;

	        if (!$closing_id) throw new NotFoundException(__('Invalid closing'));

	        $closing = $this->Closing->findById($closing_id);

	        if (!$closing) throw new NotFoundException(__('No se encontro el cierre'));

	        $total_check = 0;
			$total_cash = 0;
			$total_deposit = 0;
			$total_tdc = 0;
			$total_transfer = 0;
			$details_closings = $this->DetailsClosing->getBillsByClosing($closing_id);
			foreach ($details_closings as $detail_closing) {
				if ($detail_closing['b']['status'] == 1 && $detail_closing['mb']['id'] == 1)
					$total_check += $detail_closing['ip']['subscribed_amount'];
				else if ($detail_closing['b']['status'] == 1 && $detail_closing['mb']['id'] == 2)
					$total_cash += $detail_closing['ip']['subscribed_amount'];
				elseif ($detail_closing['b']['status'] == 1 && $detail_closing['mb']['id'] == 3)
					$total_deposit += $detail_closing['ip']['subscribed_amount'];
				elseif ($detail_closing['b']['status'] == 1 && $detail_closing['mb']['id'] == 4)
					$total_tdc += $detail_closing['ip']['subscribed_amount'];
				elseif ($detail_closing['b']['status'] == 1 && $detail_closing['mb']['id'] == 5)
					$total_transfer += $detail_closing['ip']['subscribed_amount'];
			}

			$receipts_closings = $this->ReceiptsClosing->getReceiptsByClosing($closing_id);
			foreach ($receipts_closings as $receipt_closing) {
				if ($receipt_closing['mb']['id'] == 1)
					$total_check += $receipt_closing['ip']['subscribed_amount'];
			 	else if ($receipt_closing['mb']['id'] == 2)
					$total_cash += $receipt_closing['ip']['subscribed_amount'];
			 	elseif ($receipt_closing['mb']['id'] == 3)
					$total_deposit += $receipt_closing['ip']['subscribed_amount'];
			 	elseif ($receipt_closing['mb']['id'] == 4)
					$total_tdc += $receipt_closing['ip']['subscribed_amount'];
			 	elseif ($receipt_closing['mb']['id'] == 5)
					$total_transfer += $receipt_closing['ip']['subscribed_amount'];
			}

			$coins_closing = $this->CoinsClosing->find('all',array('conditions' => array('closing_id' => $closing['Closing']['id'])));
			
			$this->set('closing',$closing);
			$this->set('details_closings',$details_closings);
			$this->set('receipts_closings',$receipts_closings);
			$this->set('size_details_closings',sizeof($details_closings));
			$this->set('size_receipts_closings',sizeof($receipts_closings));
			$this->set('size_coins_closings',sizeof($coins_closing));
			$this->set('coins_closing',$coins_closing);
			$this->set('total_check',$total_check);
			$this->set('total_cash',$total_cash);
			$this->set('total_deposit',$total_deposit);
			$this->set('total_tdc',$total_tdc);
			$this->set('total_transfer',$total_transfer);
		}

		public function generateClosing(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$date_start = "";
				$last_close = $this->Closing->find('first',array(
												 'fields' => array('date_closing_end'),
												 'order' => array('date_closing_end DESC'),
												 'limit' => 1
												 ));
				if (empty($last_close)) $date_start = $this->Bill->getDateStart();
				else $date_start = $last_close['Closing']['date_closing_end'];

				$date_end = date('Y-m-d');
				$bills = array();
				$receipts = array();
				$total_check = $this->InvoicesPayment->getTotalByModeAndDate(1,$date_start,$date_end,0);
				$bills_check = $this->InvoicesPayment->getBillByModeAndDate(1,$date_start,$date_end,0);
				$receipt_check = $this->InvoicesPayment->getTotalReceiptsByModeAndDate(1,$date_start,$date_end,0);
				$receipt = $this->InvoicesPayment->getReceiptByModeAndDate(1,$date_start,$date_end,0);
				$total_check = $total_check + $receipt_check;
				if (!empty($bills_check)) array_push($bills, $bills_check);
				if (!empty($receipt)) array_push($receipts, $receipt);

				$total_cash = $this->InvoicesPayment->getTotalByModeAndDate(2,$date_start,$date_end,0);
				$bills_cash = $this->InvoicesPayment->getBillByModeAndDate(2,$date_start,$date_end,0);
				$receipt_cash = $this->InvoicesPayment->getTotalReceiptsByModeAndDate(2,$date_start,$date_end,0);
				$receipt = $this->InvoicesPayment->getReceiptByModeAndDate(2,$date_start,$date_end,0);
				$total_cash = $total_cash + $receipt_cash;
				if (!empty($bills_cash)) array_push($bills, $bills_cash);
				if (!empty($receipt)) array_push($receipts, $receipt);

				$total_deposit = $this->InvoicesPayment->getTotalByModeAndDate(3,$date_start,$date_end,0);
				$bills_deposit = $this->InvoicesPayment->getBillByModeAndDate(3,$date_start,$date_end,0);
				$receipt_deposit = $this->InvoicesPayment->getTotalReceiptsByModeAndDate(3,$date_start,$date_end,0);
				$receipt = $this->InvoicesPayment->getReceiptByModeAndDate(3,$date_start,$date_end,0);
				$total_deposit = $total_deposit + $receipt_deposit;
				if (!empty($bills_deposit)) array_push($bills, $bills_deposit);
				if (!empty($receipt)) array_push($receipts, $receipt);
				
				$total_tdc = $this->InvoicesPayment->getTotalByModeAndDate(4,$date_start,$date_end,0);
				$bills_tdc = $this->InvoicesPayment->getBillByModeAndDate(4,$date_start,$date_end,0);
				$receipt_tdc = $this->InvoicesPayment->getTotalReceiptsByModeAndDate(4,$date_start,$date_end,0);
				$receipt = $this->InvoicesPayment->getReceiptByModeAndDate(4,$date_start,$date_end,0);
				$total_tdc = $total_tdc + $receipt_tdc;
				if (!empty($bills_tdc)) array_push($bills, $bills_tdc);
				if (!empty($receipt)) array_push($receipts, $receipt);

				$total_transfer = $this->InvoicesPayment->getTotalByModeAndDate(5,$date_start,$date_end,0);
				$bills_transfer = $this->InvoicesPayment->getBillByModeAndDate(5,$date_start,$date_end,0);
				$receipt_transfer = $this->InvoicesPayment->getTotalReceiptsByModeAndDate(5,$date_start,$date_end,0);
				$receipt = $this->InvoicesPayment->getReceiptByModeAndDate(5,$date_start,$date_end,0);
				$total_transfer = $total_transfer + $receipt_transfer;
				if (!empty($bills_transfer)) array_push($bills, $bills_transfer);
				if (!empty($receipt)) array_push($receipts, $receipt);

				$total = array('total_check' => floatval($total_check), 'total_cash' => floatval($total_cash), 'total_deposit' => floatval($total_deposit),'total_tdc' => floatval($total_tdc), 'total_transfer' => floatval($total_transfer));
				$data = array();

				$data['total'] = $total;
				$data['bills'] = $bills;
				$data['receipts'] = $receipts;
				$data['dates_closing'] = array('date_start' => $date_start, 'date_end' => $date_end);
				echo json_encode($data);
			}

		}

		public function CloseDay($closing_id = null){
			$this->Closing->recursive = -1;

	        if (!$closing_id) throw new NotFoundException(__('Invalid closing'));

	        $closing = $this->Closing->findById($closing_id);

	        if (!$closing) throw new NotFoundException(__('No se encontro el cierre'));

	        $total_check = 0;
			$total_cash = 0;
			$total_deposit = 0;
			$total_tdc = 0;
			$total_transfer = 0;
			$details_closings = $this->DetailsClosing->getBillsByClosing($closing_id);
			foreach ($details_closings as $detail_closing) {
				if ($detail_closing['b']['status'] == 1 && $detail_closing['mb']['id'] == 1)
					$total_check += $detail_closing['ip']['subscribed_amount'];
				else if ($detail_closing['b']['status'] == 1 && $detail_closing['mb']['id'] == 2)
					$total_cash += $detail_closing['ip']['subscribed_amount'];
				elseif ($detail_closing['b']['status'] == 1 && $detail_closing['mb']['id'] == 3)
					$total_deposit += $detail_closing['ip']['subscribed_amount'];
				elseif ($detail_closing['b']['status'] == 1 && $detail_closing['mb']['id'] == 4)
					$total_tdc += $detail_closing['ip']['subscribed_amount'];
				elseif ($detail_closing['b']['status'] == 1 && $detail_closing['mb']['id'] == 5)
					$total_transfer += $detail_closing['ip']['subscribed_amount'];
			}

			$receipts_closings = $this->ReceiptsClosing->getReceiptsByClosing($closing_id);
			foreach ($receipts_closings as $receipt_closing) {
				if ($receipt_closing['mb']['id'] == 1)
					$total_check += $receipt_closing['ip']['subscribed_amount'];
				else if ($receipt_closing['mb']['id'] == 2)
					$total_cash += $receipt_closing['ip']['subscribed_amount'];
				elseif ($receipt_closing['mb']['id'] == 3)
					$total_deposit += $receipt_closing['ip']['subscribed_amount'];
				elseif ($receipt_closing['mb']['id'] == 4)
					$total_tdc += $receipt_closing['ip']['subscribed_amount'];
				elseif ($receipt_closing['mb']['id'] == 5)
					$total_transfer += $receipt_closing['ip']['subscribed_amount'];
			}

			$coins_closing = $this->CoinsClosing->find('all',array('conditions' => array('closing_id' => $closing['Closing']['id'])));
			
			$this->pdfConfig = array(
				'download' => false,
				'orientation' => 'landscape',
				'pageSize' => 'Tabloid',
				'filename' => 'cierre_del_dia_.pdf'
			);
			
			$this->set('closing',$closing);
			$this->set('details_closings',$details_closings);
			$this->set('receipts_closings',$receipts_closings);
			$this->set('size_details_closings',sizeof($details_closings));
			$this->set('size_receipts_closings',sizeof($receipts_closings));
			$this->set('size_coins_closings',sizeof($coins_closing));
			$this->set('coins_closing',$coins_closing);
			$this->set('total_check',$total_check);
			$this->set('total_cash',$total_cash);
			$this->set('total_deposit',$total_deposit);
			$this->set('total_tdc',$total_tdc);
			$this->set('total_transfer',$total_transfer);
		}

		public function _saveCoinsClosing($closing_id,$nomination,$quantity){
			$this->CoinsClosing->create();
			$coin = array('CoinsClosing' => array(
								"closing_id" => $closing_id,
			                    "nomination" => $nomination,
			                    "quantity" => $quantity));
			$this->CoinsClosing->save($coin);
		}
	}