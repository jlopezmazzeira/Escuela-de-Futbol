<?php

/**
* 
* 
*/
class Route extends AppModel{
    var $name = 'Route';

    // Relacion (M:M) - Route:Transport
    var $hasAndBelongsToMany = array(
        'Transport' => array(
            'className' => 'Transport',
            'joinTable' => 'routes_transports',
            'foreignKey' => 'route_id',
            'associationForeignKey' => 'transport_id'
        )
    );

    function getRoutesTransports() {
        $routes_transports = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'routes_transports',
                                    'alias' => 'rt',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'rt.route_id = Route.id'
                                    )
                                ),
                                array(
                                    'table' => 'transports',
                                    'alias' => 't',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'rt.transport_id = t.id'
                                    )
                                )
                            ),
                            'conditions' => array('rt.id !=' => ""),
                            'fields' => array('rt.id, t.name, t.lastname, Route.name')
                        ));

        return $routes_transports;
    }

    public function getRoute($route_transport_id) {
        $routes_transports = $this->find('first', array(
                            'joins' => array(
                                array(
                                    'table' => 'routes_transports',
                                    'alias' => 'rt',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'rt.route_id = Route.id'
                                    )
                                ),
                                array(
                                    'table' => 'transports',
                                    'alias' => 't',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'rt.transport_id = t.id'
                                    )
                                )
                            ),
                            'conditions' => array('rt.id' => $route_transport_id),
                            'fields' => array('Route.name, Route.cost, t.id')
                        ));

        return $routes_transports;
    }
    
}