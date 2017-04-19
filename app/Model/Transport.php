<?php

/**
* 
* 
*/
class Transport extends AppModel{
    var $name = 'Transport';
    // Relacion (M:M) - Transport:Route
    var $hasAndBelongsToMany = array(
        'Route' => array(
            'className' =>'Route',
            'joinTable' => 'routes_transports',
            'foreignKey' => 'transport_id',
            'associationForeignKey' => 'route_id'
        )
    );

    
    public function getCarrierIncomeByDatesAndTransport($transport_id,$date_from,$date_until){
        $transport = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'details_bills',
                                    'alias' => 'db',
                                    'type' => 'INNER',
                                    'conditions' => array(
                                        'db.transport_id' => $transport_id
                                    )
                                ),
                                array(
                                    'table' => 'bills',
                                    'alias' => 'b',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'b.id = db.bill_id'
                                    )
                                ),
                                array(
                                    'table' => 'students',
                                    'alias' => 's',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        's.id = db.student_id'
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
                            'conditions' => array(
                                    'and' => array(
                                                    array('b.date_payment >= ' => $date_from,
                                                          'b.date_payment <= ' => $date_until
                                                   )),
                                    'Transport.id' => $transport_id

                            ),
                            'recursive' => -1,
                            'fields' => array('Transport.id, Transport.name, Transport.lastname, Transport.phone, 
                                Transport.movil, db.cost, b.status, s.name, s.lastname, s.birthday, s.id, s.email,
                                s.responsable, c.name, b.bill_code, b.date_payment')
                        ));
        return $transport;
    }

    public function getCarrierIncomeByTransport($transport_id){
        $transport = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'details_bills',
                                    'alias' => 'db',
                                    'type' => 'INNER',
                                    'conditions' => array(
                                        'db.transport_id' => $transport_id
                                    )
                                ),
                                array(
                                    'table' => 'bills',
                                    'alias' => 'b',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'b.id = db.bill_id'
                                    )
                                ),
                                array(
                                    'table' => 'students',
                                    'alias' => 's',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        's.id = db.student_id'
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
                            'conditions' => array(
                                    'Transport.id' => $transport_id

                            ),
                            'recursive' => -1,
                            'fields' => array('Transport.id, Transport.name, Transport.lastname, Transport.phone, 
                                Transport.movil, db.cost, b.status, s.name, s.lastname, s.birthday, s.id, s.email,
                                s.responsable, c.name, b.bill_code, b.date_payment')
                        ));
        return $transport;
    }

    public function getCarrierIncomeByDates($date_from,$date_until){
        $transport = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'details_bills',
                                    'alias' => 'db',
                                    'type' => 'INNER',
                                    'conditions' => array(
                                        'db.transport_id = Transport.id'
                                    )
                                ),
                                array(
                                    'table' => 'bills',
                                    'alias' => 'b',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'b.id = db.bill_id'
                                    )
                                ),
                                array(
                                    'table' => 'students',
                                    'alias' => 's',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        's.id = db.student_id'
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
                            'conditions' => array(
                                    'and' => array(
                                                    array('b.date_payment >= ' => $date_from,
                                                          'b.date_payment <= ' => $date_until
                                                   ))

                            ),
                            'recursive' => -1,
                            'fields' => array('Transport.id, Transport.name, Transport.lastname, Transport.phone, 
                                Transport.movil, db.cost, b.status, s.name, s.lastname, s.birthday, s.id, s.email,
                                s.responsable, c.name, b.bill_code, b.date_payment')
                        ));
        return $transport;
    }

    public function getPendingPaymentsByDatesAndTransport($transport_id,$date_from,$date_until){
        $transport = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'details_orders',
                                    'alias' => 'do',
                                    'type' => 'INNER',
                                    'conditions' => array(
                                        'do.transport_id' => $transport_id
                                    )
                                ),
                                array(
                                    'table' => 'orders',
                                    'alias' => 'o',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'o.id = do.order_id'
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
                                    'table' => 'categories',
                                    'alias' => 'c',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'c.id = s.category_id'
                                    )
                                )
                            ),
                            'conditions' => array(
                                    'and' => array(
                                                    array('o.date_created >= ' => $date_from,
                                                          'o.date_created <= ' => $date_until
                                                   )),
                                    'Transport.id' => $transport_id

                            ),
                            'recursive' => -1,
                            'fields' => array('Transport.id, Transport.name, Transport.lastname, Transport.phone, 
                                Transport.movil, do.cost, s.name, s.lastname, s.birthday, s.id, s.email,
                                s.responsable, c.name')
                        ));
        return $transport;
    }

    public function getPendingPaymentsByTransport($transport_id){
        $transport = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'details_orders',
                                    'alias' => 'do',
                                    'type' => 'INNER',
                                    'conditions' => array(
                                        'do.transport_id' => $transport_id
                                    )
                                ),
                                array(
                                    'table' => 'orders',
                                    'alias' => 'o',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'o.id = do.order_id'
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
                                    'table' => 'categories',
                                    'alias' => 'c',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'c.id = s.category_id'
                                    )
                                )
                            ),
                            'conditions' => array(
                                    'Transport.id' => $transport_id

                            ),
                            'recursive' => -1,
                            'fields' => array('Transport.id, Transport.name, Transport.lastname, Transport.phone, 
                                Transport.movil, do.cost, s.name, s.lastname, s.birthday, s.id, s.email,
                                s.responsable, c.name')
                        ));
        return $transport;
    }

    public function getPendingPaymentsByDates($date_from,$date_until){
        $transport = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'details_orders',
                                    'alias' => 'do',
                                    'type' => 'INNER',
                                    'conditions' => array(
                                        'do.transport_id = Transport.id'
                                    )
                                ),
                                array(
                                    'table' => 'orders',
                                    'alias' => 'o',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'o.id = do.order_id'
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
                                    'table' => 'categories',
                                    'alias' => 'c',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'c.id = s.category_id'
                                    )
                                )
                            ),
                            'conditions' => array(
                                    'and' => array(
                                                    array('o.date_created >= ' => $date_from,
                                                          'o.date_created <= ' => $date_until
                                                   ))

                            ),
                            'recursive' => -1,
                            'fields' => array('Transport.id, Transport.name, Transport.lastname, Transport.phone, 
                                Transport.movil, do.cost, s.name, s.lastname, s.birthday, s.id, s.email,
                                s.responsable, c.name')
                        ));
        return $transport;
    }

}