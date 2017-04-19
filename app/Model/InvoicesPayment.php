<?php 
	/**
	* 
	*/
	class InvoicesPayment extends AppModel{
		var $name = 'InvoicesPayment';
		
		public function getTotalAmount($bill_id = null){
	        $this->virtualFields['total_amount'] = 'SUM(subscribed_amount)';
	        $sum = $this->find('first',array(
						                'fields' => array('total_amount'),
						                'conditions' => array(
						                    'bill_id' => $bill_id
						                )
						            ));
	        $total = $sum['InvoicesPayment']['total_amount'];
	        if (is_null($total)) {
	            $total = 0;
	        }
	        return $total;
	    }

	    public function getInvoicesPaymentBill($bill_id = null){
	    	$payments = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'modes_bills',
                                    'alias' => 'm',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'm.id = InvoicesPayment.mode_bill_id'
                                    )
                                )
                            ),
                            'conditions' => array('InvoicesPayment.bill_id' => $bill_id),
                            'fields' => array('InvoicesPayment.subscribed_amount, InvoicesPayment.observation,m.name')
                        ));
        	return $payments;
	    }

	    public function getAllInvoicesPayment(){
	    	$payments = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'modes_bills',
                                    'alias' => 'm',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'm.id = InvoicesPayment.mode_bill_id'
                                    )
                                )
                            ),
                            'fields' => array('InvoicesPayment.bill_id,InvoicesPayment.subscribed_amount,m.name')
                        ));
        	return $payments;
	    }

	    public function getTotalByModeAndDate($mode_bill_id,$date_start,$date_end,$closed){
	        $this->virtualFields['total_payment'] = 'SUM(subscribed_amount)';
	        $sum = $this->find('first',array(
	                            'joins' => array(
	                                array(
	                                    'table' => 'bills',
	                                    'alias' => 'b',
	                                    'type' => 'LEFT',
	                                    'conditions' => array(
	                                    	'b.id = InvoicesPayment.bill_id'
	                                    )
	                                ),
	                                array(
	                                    'table' => 'modes_bills',
	                                    'alias' => 'mb',
	                                    'type' => 'LEFT',
	                                    'conditions' => array(
	                                        'mb.id' => $mode_bill_id
	                                    )
	                                )
	                            ),
								'conditions' => array(
									'InvoicesPayment.mode_bill_id' => $mode_bill_id,
									'b.status' => 1,
						            'b.closed' => $closed,
						            'AND' => array('b.date_payment >=' => $date_start, 'b.date_payment <=' => $date_end)
						        ),
	                			'fields' => array('total_payment')
	            ));
	        $total = $sum['InvoicesPayment']['total_payment'];
	        if (is_null($total)) {
	            $total = 0;
	        }
	        return $total;
	    }

	    public function getBillByModeAndDate($mode_bill_id,$date_start,$date_end,$closed){
	        $bills = $this->find('all',array(
	                            'joins' => array(
	                                array(
	                                    'table' => 'bills',
	                                    'alias' => 'b',
	                                    'type' => 'LEFT',
	                                    'conditions' => array(
	                                        'b.id = InvoicesPayment.bill_id'
	                                    )
	                                ),
	                                array(
	                                    'table' => 'modes_bills',
	                                    'alias' => 'mb',
	                                    'type' => 'LEFT',
	                                    'conditions' => array(
	                                        'mb.id = InvoicesPayment.mode_bill_id'
	                                    )
	                                )
	                            ),
	                            'conditions' => array(
	                                'InvoicesPayment.mode_bill_id' => $mode_bill_id,
	                                'b.status' => array(1,2),
	                                'b.closed' => $closed,
	                                'AND' => array('b.date_payment >=' => $date_start, 'b.date_payment <=' => $date_end)
	                            ),
	                            'fields' => array('b.id,b.bill_code, b.date_payment, mb.name, InvoicesPayment.subscribed_amount,
	                            				InvoicesPayment.observation')
	            ));
	        return $bills;
	    }

	    public function getTotalReceiptsByModeAndDate($mode_bill_id,$date_start,$date_end,$closed){
	        $this->virtualFields['total_payment'] = 'SUM(subscribed_amount)';
	        $sum = $this->find('first',array(
	                            'joins' => array(
	                                array(
	                                    'table' => 'payments_receipts',
	                                    'alias' => 'pr',
	                                    'type' => 'LEFT',
	                                    'conditions' => array(
	                                    	'pr.invoice_payment_id = InvoicesPayment.id'
	                                    )
	                                ),
	                                array(
	                                    'table' => 'receipts',
	                                    'alias' => 'r',
	                                    'type' => 'LEFT',
	                                    'conditions' => array(
	                                    	'r.id = pr.receipt_id'
	                                    )
	                                ),
	                                array(
	                                    'table' => 'bills',
	                                    'alias' => 'b',
	                                    'type' => 'LEFT',
	                                    'conditions' => array(
	                                    	'b.id = r.bill_id'
	                                    )
	                                ),
	                                array(
	                                    'table' => 'modes_bills',
	                                    'alias' => 'mb',
	                                    'type' => 'LEFT',
	                                    'conditions' => array(
	                                        'mb.id' => $mode_bill_id
	                                    )
	                                )
	                            ),
								'conditions' => array(
									'InvoicesPayment.mode_bill_id' => $mode_bill_id,
									'r.closed' => $closed,
						            'AND' => array('r.date_payment >=' => $date_start, 'r.date_payment <=' => $date_end)
						        ),
	                			'fields' => array('total_payment')
	            ));
	        $total = $sum['InvoicesPayment']['total_payment'];
	        if (is_null($total)) {
	            $total = 0;
	        }
	        return $total;
	    }

	    public function getReceiptByModeAndDate($mode_bill_id,$date_start,$date_end,$closed){
	        $bills = $this->find('all',array(
	                            'joins' => array(
	                                array(
	                                    'table' => 'payments_receipts',
	                                    'alias' => 'pr',
	                                    'type' => 'LEFT',
	                                    'conditions' => array(
	                                    	'pr.invoice_payment_id = InvoicesPayment.id'
	                                    )
	                                ),
	                                array(
	                                    'table' => 'receipts',
	                                    'alias' => 'r',
	                                    'type' => 'LEFT',
	                                    'conditions' => array(
	                                    	'r.id = pr.receipt_id'
	                                    )
	                                ),
	                                array(
	                                    'table' => 'bills',
	                                    'alias' => 'b',
	                                    'type' => 'LEFT',
	                                    'conditions' => array(
	                                    	'b.id = r.bill_id'
	                                    )
	                                ),
	                                array(
	                                    'table' => 'modes_bills',
	                                    'alias' => 'mb',
	                                    'type' => 'LEFT',
	                                    'conditions' => array(
	                                        'mb.id' => $mode_bill_id
	                                    )
	                                )
	                            ),
								'conditions' => array(
									'InvoicesPayment.mode_bill_id' => $mode_bill_id,
									'r.closed' => $closed,
						            'AND' => array('r.date_payment >=' => $date_start, 'r.date_payment <=' => $date_end)
						        ),
	                            'fields' => array('b.id,b.bill_code, r.id, r.receipt_code, r.date_payment, mb.name, 
	                            				   InvoicesPayment.subscribed_amount, InvoicesPayment.observation')
	            ));
	        return $bills;
	    }

	}