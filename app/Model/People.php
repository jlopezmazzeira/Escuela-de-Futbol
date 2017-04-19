<?php

/**
* 
* 
*/
class People extends AppModel{
    var $name = 'People';
    //public $useTable = 'peoples';

    public function getProvider($provider_id = ''){
    	$provider = $this->find('first', array(
                            'joins' => array(
                                array(
                                    'table' => 'types_providers',
                                    'alias' => 'tp',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'tp.id = People.type_provider_id'
                                    )
                                ),
                                array(
                                    'table' => 'types_accountings',
                                    'alias' => 'ta',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'ta.id = People.type_accounting_id'
                                    )
                                )
                            ),
                            'conditions' => array('People.id' => $provider_id),
                            'fields' => array('People.id,People.address,People.document_number,People.document_type,
                            				People.name,People.phone,People.email,tp.name,ta.name,tp.id,ta.id')
                        ));
    	return $provider;
    }

    public function getResponsable($people_id = ''){
        $responsable = $this->find('first', array(
                            'conditions' => array('People.id' => $people_id),
                            'fields' => array('People.id,People.address,People.document_number,People.document_type,
                                            People.name,People.phone')
                        ));
        return $responsable;  
    }
}