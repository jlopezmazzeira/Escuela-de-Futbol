<?php

/**
* 
* 
*/
class Bill extends AppModel{
    var $name = 'Bill';

    public function getBills(){
        $bills = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'invoices_payments',
                                    'alias' => 'ip',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'ip.bill_id = Bill.id'
                                    )
                                ),
                                array(
                                    'table' => 'modes_bills',
                                    'alias' => 'mb',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'mb.id = ip.mode_bill_id'
                                    )
                                ),
                                array(
                                    'table' => 'people',
                                    'alias' => 'p',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'p.id = Bill.people_id'
                                    )
                                )
                            ),
                            'group' => 'Bill.bill_code',
                            'conditions' => array('Bill.bill_code !=' => "", 'NOT' => array(
                                'Bill.bill_code LIKE' => '%0000000%' 
                            )),
                            'fields' => array('Bill.id,Bill.bill_code,Bill.status,Bill.total,
                                        Bill.date_payment,mb.name, p.name, Bill.closed'),
                            'order' => array('Bill.bill_code DESC')
                        ));
        return $bills;
    }

    public function getDataBill($id = null){
    	$bill_data = $this->find('first', array(
                            'joins' => array(
                                array(
                                    'table' => 'people',
                                    'alias' => 'p',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'p.id = Bill.people_id'
                                    )
                                ),
                                array(
                                    'table' => 'types_bills',
                                    'alias' => 't',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        't.id = Bill.type_bill_id'
                                    )
                                ),
                                array(
                                    'table' => 'invoices_payments',
                                    'alias' => 'ip',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'ip.bill_id' => $id
                                    )
                                ),
                                array(
                                    'table' => 'modes_bills',
                                    'alias' => 'm',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'm.id = ip.mode_bill_id'
                                    )
                                )
                            ),
                            'conditions' => array('Bill.id' => $id),
                            'fields' => array('Bill.bill_code, Bill.observation, Bill.total, Bill.status, t.name, m.name,
                            	p.name, p.email, p.document_number, p.document_type, p.address, p.phone, p.role_id, 
                                Bill.date_payment, Bill.credit')
                        ));
		return $bill_data;
    }

    public function getDataBillClient($id = null){
        $bill_data = $this->find('first', array(
                            'joins' => array(
                                array(
                                    'table' => 'people',
                                    'alias' => 'p',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'p.id = Bill.people_id'
                                    )
                                ),
                                array(
                                    'table' => 'types_bills',
                                    'alias' => 't',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        't.id = Bill.type_bill_id'
                                    )
                                ),
                                array(
                                    'table' => 'invoices_payments',
                                    'alias' => 'ip',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'ip.bill_id' => $id
                                    )
                                ),
                                array(
                                    'table' => 'modes_bills',
                                    'alias' => 'm',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'm.id = ip.mode_bill_id'
                                    )
                                )
                            ),
                            'conditions' => array('Bill.id' => $id),
                            'fields' => array('Bill.bill_code, Bill.observation, Bill.date_payment, Bill.status, Bill.total,t.name, m.name,
                                p.name, p.document_number, p.document_type, p.address, p.phone, p.home_phone, p.email')
                        ));
        return $bill_data;
    }

    public function unopenedInvoices(){
        $this->virtualFields['total_bill'] = 'COUNT(bill_code)';
        $sum = $this->find('first',array(
                'fields' => array('total_bill'),
                'conditions' => array(
                    'status' => 1,
                    'closed' => 0
                )
            ));

        return $sum['Bill']['total_bill'];
    }

    public function getDateStart(){
        $date_start = $this->find('first',array(
                'fields' => array('date_payment'),
                'conditions' => array(
                    'bill_code !=' => '',
                    'closed' => 0
                ),
                'order' => array('date_payment ASC'),
                'limit' => 1
            ));

        return $date_start['Bill']['date_payment'];
    }

    public function getAccountsReceivable($date_from,$date_until){
        $accounts_receivable = $this->find('all',array(
                                'joins' => array(
                                    array(
                                        'table' => 'people',
                                        'alias' => 'p',
                                        'type' => 'LEFT',
                                        'conditions' => array(
                                            'p.id = Bill.people_id'
                                        )
                                    ),
                                    array(
                                        'table' => 'details_bills',
                                        'alias' => 'db',
                                        'type' => 'LEFT',
                                        'conditions' => array(
                                            'db.bill_id = Bill.id'
                                        )
                                    )
                                ),
                                'conditions' => array('Bill.status' => 3),
                                'fields' => array('Bill.id, Bill.total, Bill.bill_code, p.name, p.document_number,p.phone')
            ));

        return $accounts_receivable;
    }

    public function getBillsPaid(){
        $bills = $this->find('all', array(
                    'conditions' => array(
                        'Bill.status' => 1, 
                        'Bill.type_bill_id' => 2,
                        'MONTH(Bill.date_payment)' => date('n')
                    ),
                    'fields' => array('Bill.id, Bill.total')
            ));

        return $bills;
    }

}