<?php
	App::uses('Component', 'Controller');
	class BillDataComponent extends Component {

	    function updateCodeInitiator($id){
	        $Initiator = ClassRegistry::init('Initiator');
	        $initiator = $Initiator->find('first',array('conditions' => array('id' => $id)));
            $Initiator->id = $initiator['Initiator']['id'];
			$initiator = $initiator['Initiator']['value'];
            $initiator++;
            $Initiator->saveField('value',$initiator);
	    }

	    function generateBillCode(){
	    	$Initiator = ClassRegistry::init('Initiator');
	    	$Parameter = ClassRegistry::init('Parameter');
			//code Format
			$format = $Parameter->getValueParameter(4);
			//Initial code
			$code_first = $Parameter->getValueParameter(8);
			
			$initiator = $Initiator->find('first',array('conditions' => array('id' => 1)));
			$initiator = $initiator['Initiator']['value'];
			$initiator = floatval($initiator) + floatval($code_first);
			
			$lenght = 7 - strlen($initiator);
            $filled = str_repeat("0",$lenght);
            $final_code = $format.$filled.$initiator;

            return $final_code;
		}

		public function generateReceiptCode(){
			//code Format
			$Initiator = ClassRegistry::init('Initiator');
			$initiator = $Initiator->find('first',array('conditions' => array('id' => 3)));
			$initiator = $initiator['Initiator']['value'];
			
			$lenght = 7 - strlen($initiator);
            $filled = str_repeat("0",$lenght);
            $final_code = $filled.$initiator;

            return $final_code;
		}

		function saveBill($responsable_id,$bill_code,$type_bill_id,$date_payment,$total_payment,$credit,$observation,$status){
			$Bill = ClassRegistry::init('Bill');
			$bill = array('Bill' => array(
							'people_id' => $responsable_id,
							'bill_code' => $bill_code,
							'type_bill_id' => $type_bill_id,
							'date_payment' => $date_payment,
							'total' => $total_payment,
							'credit' => $credit,
							'observation' => $observation,
							'status' => $status,
							'date_created' => date('Y-m-d')));
			
			$Bill->create();
			$Bill->save($bill);
			return $Bill->id;
		}

		function saveDetailBill($bill_id,$student_id,$product,$description,$cost,$exoneration,$scholarship,$quantity,$month,
								$year,$transport_id,$iva){
			$DetailsBill = ClassRegistry::init('DetailsBill');
			$detail_bill = array('DetailsBill' => array(
								'bill_id' => $bill_id,
								'student_id' => $student_id,
								'product' => $product,
								'quantity' => $quantity,
								'cost' => $cost,
								'exoneration' => $exoneration,
								'scholarship' => $scholarship,
								'iva' => $iva,
								'description' => $description,
								'transport_id' => $transport_id,
								'month' => $month,
								'year' => $year));

			$DetailsBill->create();	
			$DetailsBill->save($detail_bill);
		}

		function saveOrder($responsable_id,$pending_total,$observation){
			$Order = ClassRegistry::init('Order');
			$order = array('Order' => array(
							'people_id' => $responsable_id,
							'pending_total' => $pending_total,
							'observation' => $observation,
							'date_created' => date('Y-m-d')));
			
			$Order->create();
			$Order->save($order);
			return $Order->id;
		}

		function saveDetailOrder($order_id,$student_id,$product,$description,$cost,$quantity,$month,$year,$transport_id,$iva){
			$DetailsOrder = ClassRegistry::init('DetailsOrder');
			$detail_order = array('DetailsOrder' => array(
								'order_id' => $order_id,
								'student_id' => $student_id,
								'product' => $product,
								'quantity' => $quantity,
								'cost' => $cost,
								'iva' => $iva,
								'description' => $description,
								'transport_id' => $transport_id,
								'month' => $month,
								'year' => $year));	
			$DetailsOrder->create();
			$DetailsOrder->save($detail_order);
		}

		function savePayment($bill_id,$mode_bill_id,$subscribed_amount,$observation){
			$InvoicesPayment = ClassRegistry::init('InvoicesPayment');
			$payment = array('InvoicesPayment' => array(
								'bill_id' => $bill_id,
								'mode_bill_id' => $mode_bill_id,
								'subscribed_amount' => $subscribed_amount,
								'observation' => $observation,
								'date_payment' => date('Y-m-d')));	
			$InvoicesPayment->create();
			$InvoicesPayment->save($payment);

			return $InvoicesPayment->id;
		}
	}