<?php
/**
* 
* 
*/
class CategoriesEmployee extends AppModel{
    var $name = 'CategoriesEmployee';

    public function getCategoriesByUser($user_id = ''){
    	$categories = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'categories',
                                    'alias' => 'c',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'c.id = CategoriesEmployee.category_id'
                                    )
                                )
                            ),
                            'conditions' => array(
                                'CategoriesEmployee.employee_id ' => $user_id,
                                'c.status' => 1
                            ),
                            'recursive' => -1,
                            'fields' => array('c.id, c.name')
                        ));

        return $categories;
    }

}