<?php  

	/**
	* 
	*/
	class ReceiptsController extends AppController
	{
		var $uses = array('Receipt','Bill','ModesBill','People','InvoicesPayment','PaymentsReceipt','Parameter','Flash');
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
            $receipts = $this->Receipt->getDataReceipt();
            $size = sizeof($receipts);
            $this->set('size',$size);
            $this->set('receipts',$receipts);
        }

        public function add($bill_id = ''){
            if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
        	$modes_bills = $this->ModesBill->find('all');
        	$bill = $this->Bill->find('first',array(
        								'conditions' => array('id' => $bill_id),
        								'fields' => array('bill_code','total','id')
        		));

            $payment = $this->InvoicesPayment->getTotalAmount($bill_id);
        	$receipt_code = $this->BillData->generateReceiptCode();
            $total_pending = $bill['Bill']['total'] - $payment;
        	$this->set('modes_bills',$modes_bills);
        	$this->set('bill',$bill);
        	$this->set('receipt_code',$receipt_code);
        	$this->set('payment',$payment);
            $this->set('total_pending',$total_pending);
        }

        public function addPayment(){
        	$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
				$data = $this->data;
				$iva = $this->Parameter->getValueParameter(1);
				$iva = round($iva,2);
                $items = $data['items'];
                $invoice_payment_id = "";
                
                $receipt = array('Receipt' => array(
                                    'bill_id' => $data['bill_id'],
                                    'receipt_code' => $data['receipt_code'],
                                    'date_payment' => date('Y-m-d'),
                                    'iva' => $iva,
                    ));

                $this->Receipt->create();
                $this->Receipt->save($receipt);
                $receipt_id = $this->Receipt->id;

                for ($i=0; $i < count($items); $i++) {
                    $total_payment = round($items[$i]['amount'],2);
                    $invoice_payment_id = $this->BillData->savePayment($data['bill_id'],$items[$i]['mode_bill'],$total_payment,$items[$i]['observation']);   
                    $payment_receipt = array('PaymentsReceipt' => array(
                                                'receipt_id' => $receipt_id,
                                                'invoice_payment_id' => $invoice_payment_id
                        ));

                    $this->PaymentsReceipt->create();
                    $this->PaymentsReceipt->save($payment_receipt);
                }
				
                $bill = $this->Bill->find('first',array(
        								'conditions' => array('id' => $data['bill_id']),
        								'fields' => array('total')
        		));
                
                $payment = $this->InvoicesPayment->getTotalAmount($data['bill_id']);
                $total_pending = $bill['Bill']['total'] - $payment;
                if ($total_pending == 0) {
                    $this->Bill->id = $data['bill_id'];
                    $this->Bill->saveField('status',1);
                    $this->Bill->saveField('date_modification',date('Y-m-d'));
                    $data_bill = $this->Bill->findById($data['bill_id']);
                    $this->People->id = $data_bill['Bill']['people_id'];
                    $this->People->saveField('status', 2);
                    $this->People->saveField('date_modification',date('Y-m-d'));
                }

                $this->BillData->updateCodeInitiator(3);
                echo $receipt_id;
			}
        }

        public function billPayment($bill_id = ''){}

        public function detailReceipt($receipt_id = ''){
            if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
            if (!$receipt_id) throw new NotFoundException(__('Invalid receipt'));

            $receipt = $this->Receipt->findById($receipt_id);

            if (!$receipt) throw new NotFoundException(__('No se encontro el recibo de pago'));

            $receipt = $this->Receipt->getDataReceiptById($receipt_id);
            $payments_receipts = $this->PaymentsReceipt->getPaymentsReceipt($receipt_id);
            $this->set('receipt',$receipt);
            $this->set('payments_receipts',$payments_receipts);

        }

        public function receipt($receipt_id = ''){
            if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
            if (!$receipt_id) throw new NotFoundException(__('Invalid receipt'));

            $receipt = $this->Receipt->findById($receipt_id);

            if (!$receipt) throw new NotFoundException(__('No se encontro el recibo de pago'));

            $receipt = $this->Receipt->getDataReceiptById($receipt_id);
            $payments_receipts = $this->PaymentsReceipt->getPaymentsReceipt($receipt_id);
            $this->set('receipt',$receipt);
            $this->set('payments_receipts',$payments_receipts);

            $this->pdfConfig = array(
                    'download' => false,
                    'orientation' => 'landscape',
                    'filename' => 'recibo_pago.pdf'
                );
        }

	}