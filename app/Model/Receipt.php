<?php

/**
* 
* 
*/
class Receipt extends AppModel{
    var $name = 'Receipt';

    public function getDataReceipt(){
    	$receipts = $this->find('all',array(
    							'joins' => array(
	                                array(
	                                    'table' => 'bills',
	                                    'alias' => 'b',
	                                    'type' => 'LEFT',
	                                    'conditions' => array(
	                                        'b.id = Receipt.bill_id'
	                                    )
	                                ),
	                                array(
	                                    'table' => 'payments_receipts',
	                                    'alias' => 'pr',
	                                    'type' => 'LEFT',
	                                    'conditions' => array(
	                                        'pr.receipt_id = Receipt.id'
	                                    )
	                                ),
	                                array(
	                                    'table' => 'invoices_payments',
	                                    'alias' => 'ip',
	                                    'type' => 'LEFT',
	                                    'conditions' => array(
	                                        'ip.id = pr.invoice_payment_id'
	                                    )
	                                )
                            	),
								'group' => array('pr.receipt_id'),
                            	'fields' => array('Receipt.id,Receipt.receipt_code,Receipt.date_payment,b.bill_code,
                            					SUM(ip.subscribed_amount) AS total_payment')
    		));

    	return $receipts;
    }

    public function getDataReceiptById($receipt_id = ''){
    	$receipts = $this->find('first',array(
    							'joins' => array(
	                                array(
	                                    'table' => 'bills',
	                                    'alias' => 'b',
	                                    'type' => 'LEFT',
	                                    'conditions' => array(
	                                        'b.id = Receipt.bill_id'
	                                    )
	                                ),
	                                array(
	                                    'table' => 'people',
	                                    'alias' => 'p',
	                                    'type' => 'LEFT',
	                                    'conditions' => array(
	                                        'p.id = b.people_id'
	                                    )
	                                ),
	                                array(
	                                    'table' => 'payments_receipts',
	                                    'alias' => 'pr',
	                                    'type' => 'LEFT',
	                                    'conditions' => array(
	                                        'pr.receipt_id = Receipt.id'
	                                    )
	                                ),
	                                array(
	                                    'table' => 'invoices_payments',
	                                    'alias' => 'ip',
	                                    'type' => 'LEFT',
	                                    'conditions' => array(
	                                        'ip.id = pr.invoice_payment_id'
	                                    )
	                                )
                            	),
								'conditions' => array('Receipt.id' => $receipt_id),
                            	'fields' => array('Receipt.id,Receipt.receipt_code,Receipt.date_payment,b.bill_code,
                            					SUM(ip.subscribed_amount) AS total_payment, p.name, p.document_number,
                            					p.document_type, p.phone, p.address')
    		));

    	return $receipts;
    }

    public function unopenedReceipts(){
        $this->virtualFields['total_receipt'] = 'COUNT(receipt_code)';
        $sum = $this->find('first',array(
                'fields' => array('total_receipt'),
                'conditions' => array(
                    'closed' => 0
                )
            ));

        return $sum['Receipt']['total_receipt'];
    }

    public function getBillByReceipt($receipt_id = ''){
    	$receipt = $this->find('first', array('conditions' => array('id' => $receipt_id),'fields' => array('bill_id')));
    	return $receipt['Receipt']['bill_id'];
    }

}