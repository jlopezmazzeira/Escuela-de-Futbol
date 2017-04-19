<?php

/**
* 
* 
*/
class DetailsOrder extends AppModel{
    var $name = 'DetailsOrder';

    public function getDataDetail($order_id = ''){
        $details_order = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'students',
                                    'alias' => 's',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        's.id = DetailsOrder.student_id'
                                    )
                                )
                            ),
                            'conditions' => array('DetailsOrder.order_id' => $order_id),
                            'fields' => array('s.name,s.lastname,s.id, DetailsOrder.product, DetailsOrder.quantity, 
                                DetailsOrder.cost,DetailsOrder.month, DetailsOrder.iva, DetailsOrder.description,
                                DetailsOrder.year, DetailsOrder.transport_id')
                        ));

        return $details_order;
    }

    public function getOrdersPending($student_id = ''){
        $this->virtualFields['orders'] = 'COUNT(product)';
        $orders = $this->find('first',array('fields' => array('orders'),
                                            'conditions' => array(
                                                    'DetailsOrder.student_id' => $student_id, 
                                                    'DetailsOrder.product' => 'Pension')
                                            ));
        $orders = $orders['DetailsOrder']['orders'];
        return $orders;
    }

}