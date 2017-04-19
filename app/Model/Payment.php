<?php

/**
* 
* 
*/
class Payment extends AppModel{
    var $name = 'Payment';

    public function getAllPayments(){
    	$payments = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'people',
                                    'alias' => 'p',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'p.id = Payment.people_id'
                                    )
                                ),
                                array(
                                    'table' => 'types_payments',
                                    'alias' => 'tp',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'tp.id = Payment.type_payment_id'
                                    )
                                ),
                                array(
                                    'table' => 'modes_bills',
                                    'alias' => 'mb',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'mb.id = Payment.mode_bill_id'
                                    )
                                )
                            ),
                            'fields' => array('p.name,tp.name,mb.name,Payment.bill_code,Payment.retention_number,
                            				Payment.date_payment,Payment.status,Payment.description, Payment.id, 
                                            Payment.status')
                        ));
    	return $payments;
    }

    public function getPayment($payment_id = ''){
        $payments = $this->find('first', array(
                            'joins' => array(
                                array(
                                    'table' => 'people',
                                    'alias' => 'p',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'p.id = Payment.people_id'
                                    )
                                ),
                                array(
                                    'table' => 'types_payments',
                                    'alias' => 'tp',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'tp.id = Payment.type_payment_id'
                                    )
                                ),
                                array(
                                    'table' => 'types_accountings',
                                    'alias' => 'ta',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'ta.id = p.type_accounting_id'
                                    )
                                ),
                                array(
                                    'table' => 'types_providers',
                                    'alias' => 't',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        't.id = p.type_provider_id'
                                    )
                                ),
                                array(
                                    'table' => 'modes_bills',
                                    'alias' => 'mb',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'mb.id = Payment.mode_bill_id'
                                    )
                                )
                            ),
                            'conditions' => array('Payment.id' => $payment_id),
                            'fields' => array('p.name,p.document_number,tp.name,p.document_type,p.address, p.phone, p.email,
                                            Payment.bill_code,Payment.retention_number, ta.name, t.name, t.id, mb.name,
                                            Payment.date_payment,Payment.description,Payment.retention_number,Payment.iva, 
                                            Payment.value_14, Payment.value_0, Payment.percentage_retention_iva,
                                            Payment.percentage_retention_source, Payment.status, Payment.id, Payment.observation')
                        ));
        return $payments;
    }

    public function getPayments($providers_id = array(), $date_from = '', $date_until = ''){
        $payments = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'people',
                                    'alias' => 'p',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'p.id' => $providers_id
                                    )
                                ),
                                array(
                                    'table' => 'types_payments',
                                    'alias' => 'tp',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'tp.id = Payment.type_payment_id'
                                    )
                                )
                            ),
                            'conditions' => array('p.id' => $providers_id, 'Payment.date_payment >=' => $date_from, 'Payment.date_payment <=' => $date_until),
                            'fields' => array('p.name,p.document_number,tp.name,Payment.bill_code,Payment.retention_number,
                                            Payment.date_payment,Payment.description,Payment.retention_number,Payment.iva, 
                                            Payment.value_14, Payment.value_0, Payment.percentage_retention_iva,
                                            Payment.percentage_retention_source, Payment.status'),
                            'order' => 'p.id DESC'
                        ));
        return $payments;
    }

    public function getPaymentsByProviders($providers_id = array()){
        $payments = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'people',
                                    'alias' => 'p',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'p.id' => $providers_id
                                    )
                                ),
                                array(
                                    'table' => 'types_payments',
                                    'alias' => 'tp',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'tp.id = Payment.type_payment_id'
                                    )
                                )
                            ),
                            'conditions' => array('p.id' => $providers_id),
                            'fields' => array('p.name,p.document_number,tp.name,Payment.bill_code,Payment.retention_number,
                                            Payment.date_payment,Payment.description,Payment.retention_number,Payment.iva, 
                                            Payment.value_14, Payment.value_0, Payment.percentage_retention_iva,
                                            Payment.percentage_retention_source, Payment.status'),
                            'order' => 'p.id DESC'
                        ));
        return $payments;
    }

    public function getPaymentsByDates($date_from = '', $date_until = ''){
        $payments = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'people',
                                    'alias' => 'p',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'p.id = Payment.people_id'
                                    )
                                ),
                                array(
                                    'table' => 'types_payments',
                                    'alias' => 'tp',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'tp.id = Payment.type_payment_id'
                                    )
                                )
                            ),
                            'conditions' => array('Payment.date_payment >=' => $date_from, 'Payment.date_payment <=' => $date_until),
                            'fields' => array('p.name,p.document_number,tp.name,Payment.bill_code,Payment.retention_number,
                                            Payment.date_payment,Payment.description,Payment.retention_number,Payment.iva, 
                                            Payment.value_14, Payment.value_0, Payment.percentage_retention_iva,
                                            Payment.percentage_retention_source, Payment.status'),
                            'order' => 'p.id DESC'
                        ));
        return $payments;
    }

    public function getExpiredBills(){
        $payments = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'people',
                                    'alias' => 'p',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'p.id = Payment.people_id'
                                    )
                                )
                            ),
                            'conditions' => array('Payment.status' => 3,'Payment.date_payment < NOW()'),
                            'fields' => array('p.name,Payment.bill_code,Payment.date_payment','Payment.total'),
                            'order' => 'DAYOFYEAR(Payment.date_payment) ASC',
                            'limit' => 5
                        ));
        return $payments;
    }

    public function invoicesToExpire(){
        $payments = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'people',
                                    'alias' => 'p',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'p.id = Payment.people_id'
                                    )
                                )
                            ),
                            'conditions' => array('Payment.status' => 3,'Payment.date_payment >= NOW()'),
                            'fields' => array('p.name,Payment.bill_code,Payment.date_payment','Payment.total'),
                            'order' => 'DAYOFYEAR(Payment.date_payment) ASC',
                            'limit' => 5
                        ));
        return $payments;
    }
}