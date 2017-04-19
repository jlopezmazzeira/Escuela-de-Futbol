<?php
/**
* 
* 
*/
class Taking extends AppModel{
    var $name = 'Taking';

    function getTakings()
    {
    	$takings = $this->find('all',array('conditions' => array('month !=' => date('n')),
    									'limit' => 5));

    	return $takings;
    }

}