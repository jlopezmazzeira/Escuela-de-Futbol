<?php

/**
* 
* 
*/
class Parameter extends AppModel{
    var $name = 'Parameter';

    function getValueParameter($parameter_id){
    	$parameter = $this->find('first',array('conditions' => array('id' => $parameter_id)));
    	return $parameter['Parameter']['value'];
    }
}