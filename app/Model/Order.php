<?php

/**
* 
* 
*/
class Order extends AppModel{
    var $name = 'Order';

    public function getAllPendingPayments(){
        $orders = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'people',
                                    'alias' => 'p',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'p.id = Order.people_id'
                                    )
                                )
                            ),
                            'fields' => array('Order.id,Order.date_created,Order.pending_total,p.name, p.document_number')
                        ));
        return $orders;
    }

    public function getPendingPayment($order_id = null){
        $order = $this->find('first', array(
                            'joins' => array(
                                array(
                                    'table' => 'details_orders',
                                    'alias' => 'do',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'do.order_id' => $order_id
                                    )
                                ),
                                array(
                                    'table' => 'people',
                                    'alias' => 'p',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'p.id = Order.people_id'
                                    )
                                ),
                                array(
                                    'table' => 'students',
                                    'alias' => 's',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        's.id = do.student_id'
                                    )
                                ),
                                array(
                                    'table' => 'genders',
                                    'alias' => 'g',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'g.id = s.gender_id'
                                    )
                                ),
                                array(
                                    'table' => 'categories',
                                    'alias' => 'c',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'c.id = s.category_id'
                                    )
                                )
                            ),
                            'group' => 'do.order_id',
                            'conditions' => array('Order.id' => $order_id),
                            'fields' => array('Order.id,Order.date_created,Order.pending_total,Order.observation,
                                               s.name,s.lastname,s.birthday,s.email,s.alternative_email,
                                               s.phone,s.home_phone,s.address,s.responsable,s.relation,g.name,
                                               p.name, p.document_number, p.document_type, p.address, p.phone, c.name')
                        ));
        return $order;
    }

    public function getPendingPaymentsByStudent($student_id = null){
        $orders = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'details_orders',
                                    'alias' => 'do',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'do.order_id = Order.id'
                                    )
                                ),
                                array(
                                    'table' => 'people',
                                    'alias' => 'p',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'p.id = Order.people_id'
                                    )
                                ),
                                array(
                                    'table' => 'students',
                                    'alias' => 's',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        's.id = do.student_id'
                                    )
                                ),
                                array(
                                    'table' => 'genders',
                                    'alias' => 'g',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'g.id = s.gender_id'
                                    )
                                )
                            ),
                            'conditions' => array('do.student_id' => $student_id),
                            'group' => 'do.order_id',
                            'fields' => array('Order.id,Order.date_created,Order.pending_total,s.name,
                                               s.lastname,p.name, p.document_number')
                        ));
        return $orders;
    }

    public function getLastPendingPaymentByStudent($student_id = null){
        $orders = $this->find('first', array(
                            'joins' => array(
                                array(
                                    'table' => 'details_orders',
                                    'alias' => 'do',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'do.order_id = Order.id'
                                    )
                                ),
                                array(
                                    'table' => 'students',
                                    'alias' => 's',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        's.id = do.student_id'
                                    )
                                )
                            ),
                            'conditions' => array('do.student_id' => $student_id),
                            'order' => 'do.order_id DESC',
                            'group' => 'do.order_id',
                            'limit' => 1,
                            'fields' => array('Order.id,Order.pending_total')
                        ));
        return $orders;
    }

    public function getPendingPaymentByStudent($student_id = null){
        $orders = $this->find('first', array(
                            'joins' => array(
                                array(
                                    'table' => 'details_orders',
                                    'alias' => 'do',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'do.order_id = Order.id'
                                    )
                                ),
                                array(
                                    'table' => 'people',
                                    'alias' => 'p',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'p.id = Order.people_id'
                                    )
                                ),
                                array(
                                    'table' => 'students',
                                    'alias' => 's',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        's.id = do.student_id'
                                    )
                                ),
                                array(
                                    'table' => 'genders',
                                    'alias' => 'g',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'g.id = s.gender_id'
                                    )
                                )
                            ),
                            'conditions' => array('do.student_id' => $student_id),
                            'group' => 'do.order_id',
                            'limit' => 1,
                            'fields' => array('Order.id,Order.date_created,Order.pending_total,s.name,
                                               s.lastname,p.name, p.document_number')
                        ));
        return $orders;
    }

    public function getPendingTotal(){
        $this->virtualFields['total'] = 'SUM(pending_total)';
        $sum = $this->find('first',array(
                'fields' => array('total')
            ));

        return $sum['Order']['total'];
    }

}