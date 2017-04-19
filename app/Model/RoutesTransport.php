<?php

/**
* 
* 
*/
class RoutesTransport extends AppModel{
    var $name = 'RoutesTransport';

    public function getRoutesTransportByIdTransport($transport_id){
    	$route_transport = $this->find('first', array('conditions' => array('transport_id' => $transport_id)));
    	return $route_transport;
    }

    public function getRoutesTransportByIdRoute($route_id){
    	$route_transport = $this->find('first', array('conditions' => array('route_id' => $route_id)));
    	return $route_transport;	
    }

    public function getTransportsByIdRoute($route_id){
    	$transports = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'transports',
                                    'alias' => 't',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        't.id = RoutesTransport.transport_id'
                                    )
                                )
                            ),
                            'conditions' => array(
                                    'RoutesTransport.route_id' => $route_id
                            ),
                            'recursive' => -1,
                            'fields' => array('t.id, t.name, t.lastname')
                        ));
    	return $transports;	
    }

    public function getTransportsByIdRouteTransport($route_transport_id){
    	$transports = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'transports',
                                    'alias' => 't',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        't.id = RoutesTransport.transport_id'
                                    )
                                )
                            ),
                            'conditions' => array(
                                    'RoutesTransport.id' => $route_transport_id
                            ),
                            'recursive' => -1,
                            'fields' => array('t.id, t.name, t.lastname')
                        ));
    	return $transports;	
    }

    public function getRouteByIdRouteTransport($route_transport_id = ''){
    	$route = $this->find('first', array(
                            'conditions' => array(
                                    'RoutesTransport.id' => $route_transport_id
                            ),
                            'recursive' => -1,
                            'fields' => array('RoutesTransport.route_id')
                        ));
    	return $route['RoutesTransport']['route_id'];	
    }

    public function getTransportByIdRouteTransport($route_transport_id = ''){
    	$route = $this->find('first', array(
                            'conditions' => array(
                                    'RoutesTransport.id' => $route_transport_id
                            ),
                            'recursive' => -1,
                            'fields' => array('RoutesTransport.transport_id')
                        ));
    	return $route['RoutesTransport']['transport_id'];	
    }
}