<?php

/**
* 
* 
*/
class Scholarship extends AppModel{
    var $name = 'Scholarship';

    // Relacion (1:M) - Scholarchip:Student
    var $hasMany = array('Student'=>array('className' => 'Student','dependent' => false));

    function getValueScholarship($scholarship_id){
    	$scholarship = $this->find('first', array('conditions' => array('id' => $scholarship_id)));
        $scholarship = $scholarship['Scholarship']['percentage'];
        return $scholarship;
    }
}
