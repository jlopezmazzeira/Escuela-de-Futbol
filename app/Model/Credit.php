<?php

/**
* 
* 
*/
class Credit extends AppModel{
    var $name = 'Credit';

    public function getAllCreditsNotes(){
    	$credits = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'people',
                                    'alias' => 'p',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'p.id = Credit.people_id'
                                    )
                                )
                            ),
                            'fields' => array('Credit.id, Credit.date_created, Credit.amount, 
                                p.name, p.document_number, p.document_type, p.phone')
                        ));

        return $credits;
    }
}