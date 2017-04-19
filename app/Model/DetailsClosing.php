<?php

/**
* 
* 
*/
class DetailsClosing extends AppModel{
    var $name = 'DetailsClosing';

    public function getBillsByClosing($closing_id = ''){
        /*
            SELECT p.name, b.id, b.bill_code, ip.subscribed_amount, mb.name, b.status 
            FROM details_closings dc 
            LEFT JOIN bills b ON b.id = dc.bill_id 
            LEFT JOIN people p ON p.id = b.people_id 
            LEFT JOIN invoices_payments ip ON ip.bill_id = dc.bill_id
            LEFT JOIN modes_bills mb ON mb.id = ip.mode_bill_id
            WHERE dc.closing_id = 2 
        */
    	$bills = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'bills',
                                    'alias' => 'b',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'b.id = DetailsClosing.bill_id'
                                    )
                                ),array(
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
                                        'ip.bill_id = DetailsClosing.bill_id'
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
                            'conditions' => array('DetailsClosing.closing_id' => $closing_id),
                            'fields' => array('b.bill_code,b.date_payment,ip.subscribed_amount,b.status,mb.name,
                                                p.name, mb.id, ip.observation')
                        ));
        return $bills;
    }

}