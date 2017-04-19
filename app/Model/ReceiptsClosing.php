<?php

/**
* 
* 
*/
class ReceiptsClosing extends AppModel{
    var $name = 'ReceiptsClosing';

    public function getReceiptsByClosing($closing_id = ''){
    	$recepits = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'receipts',
                                    'alias' => 'r',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'r.id = ReceiptsClosing.receipt_id'
                                    )
                                ),array(
                                    'table' => 'payments_receipts',
                                    'alias' => 'pr',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'pr.receipt_id = r.id'
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
                                    'table' => 'people',
                                    'alias' => 'p',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'p.id = b.people_id'
                                    )
                                ),
                                array(
                                    'table' => 'invoices_payments',
                                    'alias' => 'ip',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'ip.id = pr.invoice_payment_id'
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
                            'conditions' => array('ReceiptsClosing.closing_id' => $closing_id),
                            'fields' => array('b.bill_code,r.receipt_code,r.date_payment,ip.subscribed_amount,b.status,mb.name,
                                                p.name, mb.id, ip.observation')
                        ));
        return $recepits;
    }

}