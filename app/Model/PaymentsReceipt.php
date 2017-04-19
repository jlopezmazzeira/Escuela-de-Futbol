<?php

/**
* 
* 
*/
class PaymentsReceipt extends AppModel{
    var $name = 'PaymentsReceipt';

    public function getPaymentsReceipt($receipt_id = ''){
    	$payments = $this->find('all',array(
    							'joins' => array(
	                                array(
	                                    'table' => 'receipts',
	                                    'alias' => 'r',
	                                    'type' => 'LEFT',
	                                    'conditions' => array(
	                                        'r.id = PaymentsReceipt.receipt_id'
	                                    )
	                                ),
	                                array(
	                                    'table' => 'invoices_payments',
	                                    'alias' => 'ip',
	                                    'type' => 'LEFT',
	                                    'conditions' => array(
	                                        'ip.id = PaymentsReceipt.invoice_payment_id'
	                                    )
	                                ),
	                                array(
	                                    'table' => 'modes_bills',
	                                    'alias' => 'mb',
	                                    'type' => 'LEFT',
	                                    'conditions' => array(
	                                        'mb.id = ip.mode_bill_id'
	                                    )
	                                )
                            	),
								'conditions' => array('PaymentsReceipt.receipt_id' => $receipt_id),
                            	'fields' => array('ip.subscribed_amount, mb.name')
    		));

    	return $payments;
    }
}