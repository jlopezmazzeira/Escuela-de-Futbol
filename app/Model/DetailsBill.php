<?php

/**
* 
* 
*/
class DetailsBill extends AppModel{
    var $name = 'DetailsBill';

    public function getDataDetail($bill_id = ''){
    	$details_bill = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'students',
                                    'alias' => 's',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        's.id = DetailsBill.student_id'
                                    )
                                )
                            ),
                            'conditions' => array('DetailsBill.bill_id' => $bill_id),
                            'fields' => array('s.name,s.lastname,DetailsBill.product, DetailsBill.quantity, DetailsBill.cost,
                            				DetailsBill.month, DetailsBill.iva, DetailsBill.exoneration, DetailsBill.scholarship')
                        ));

    	return $details_bill;	
    }

    public function getLastMonthPaid($product = '', $student_id = ''){
        $data = $this->find('first', array(
                            'conditions' => array('DetailsBill.product' => $product, 'DetailsBill.student_id' => $student_id),
                            'order' => 'DetailsBill.id DESC',
                            'limit' => 1,
                            'fields' => array('DetailsBill.month,DetailsBill.year,DetailsBill.student_id')
                        ));
        return $data;
    }

    public function getLastBillPaid($student_id = ''){
        $data = $this->find('first', array(
                            'conditions' => array('student_id' => $student_id),
                            'order' => 'id DESC',
                            'limit' => 1,
                            'fields' => array('bill_id')
                        ));
        return $data['DetailsBill']['bill_id'];
    }

    public function getBillsReportByRangeInvoicesAndRangeDates($bill_code_from,$bill_code_until,$date_from,$date_until){
        $bills = $this->find('all',array(
                            'joins' => array(
                                array(
                                    'table' => 'bills',
                                    'alias' => 'b',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'b.id = DetailsBill.bill_id'
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
                                    'table' => 'students',
                                    'alias' => 's',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        's.id = DetailsBill.student_id'
                                    )
                                )
                            ),
                            'group' => 'b.bill_code, DetailsBill.student_id, DetailsBill.iva',
                            'conditions' => array(
                                'b.bill_code !=' => '', 
                                'b.bill_code >=' => $bill_code_from,
                                'b.bill_code <=' => $bill_code_until, 
                                'b.date_payment >=' => $date_from, 
                                'b.date_payment <=' => $date_until
                                ),
                            'fields' => array('b.date_payment,p.name, s.name, s.lastname, b.bill_code, 
                                b.id, DetailsBill.student_id, DetailsBill.iva, SUM(cost*quantity) AS sub_total, 
                                b.status, b.total, b.credit, SUM(exoneration+scholarship) AS deduction')
                        ));
        return $bills;
    }

    public function getBillsReportByRangeInvoices($bill_code_from,$bill_code_until){
        $bills = $this->find('all',array(
                            'joins' => array(
                                array(
                                    'table' => 'bills',
                                    'alias' => 'b',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'b.id = DetailsBill.bill_id'
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
                                    'table' => 'students',
                                    'alias' => 's',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        's.id = DetailsBill.student_id'
                                    )
                                )
                            ),
                            'group' => 'b.bill_code, DetailsBill.student_id, DetailsBill.iva',
                            'conditions' => array(
                                'b.bill_code !=' => '', 
                                'b.bill_code >=' => $bill_code_from,
                                'b.bill_code <=' => $bill_code_until),
                            'fields' => array('b.date_payment,p.name, s.name, s.lastname, b.bill_code, 
                                b.id, DetailsBill.student_id, DetailsBill.iva, SUM(cost*quantity) AS sub_total, b.status, 
                                b.total, b.credit, SUM(exoneration+scholarship) AS deduction')
                        ));
        return $bills;
    }

    public function getBillsReportByRangeDates($date_from,$date_until){
        $bills = $this->find('all',array(
                            'joins' => array(
                                array(
                                    'table' => 'bills',
                                    'alias' => 'b',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'b.id = DetailsBill.bill_id'
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
                                    'table' => 'students',
                                    'alias' => 's',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        's.id = DetailsBill.student_id'
                                    )
                                )
                            ),
                            'group' => 'b.bill_code, DetailsBill.student_id, DetailsBill.iva',
                            'conditions' => array(
                                'b.bill_code !=' => '', 
                                'b.date_payment >=' => $date_from, 
                                'b.date_payment <=' => $date_until),
                            'fields' => array('b.date_payment,p.name, s.name, s.lastname, b.bill_code, 
                                b.id, DetailsBill.student_id, DetailsBill.iva, SUM(cost*quantity) AS sub_total, b.status, 
                                b.total, b.credit, SUM(exoneration+scholarship) AS deduction')
                        ));
        return $bills;
    }
}