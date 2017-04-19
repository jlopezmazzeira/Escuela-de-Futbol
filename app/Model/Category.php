<?php

/**
* 
* 
*/
class Category extends AppModel{
    var $name = 'Category';


    function getNameCategory($category_id){
    	$category = $this->find('first', array('conditions' => array('id' => $category_id)));
    	$category = $category['Category']['name'];
        return $category;
    }

	public function getStudentsByCategory($category_id){

        $students = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'students',
                                    'alias' => 's',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        's.category_id = Category.id'
                                    )
                                )
                            ),
                            'conditions' => array(
                                'Category.id ' => $category_id,
                                'Category.name != ' => '',
                                's.name != ' => ''
                            ),
                            'recursive' => -1,
                            'fields' => array('s.id, s.name, s.lastname, s.birthday, Category.id, Category.name')
                        ));

        return $students;
    }

    public function getTeacherByCategory($category_id){

        $students = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'categories_employees',
                                    'alias' => 'ce',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'ce.category_id = Category.id'
                                    )
                                ),
                                array(
                                    'table' => 'users',
                                    'alias' => 'p',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'p.id = ce.employee_id'
                                    )
                                )
                            ),
                            'conditions' => array(
                                'Category.id ' => $category_id,
                                'Category.name != ' => '',
                            ),
                            'recursive' => -1,
                            'fields' => array('Category.id, Category.name, p.lastname, p.name, p.id')
                        ));

        return $students;
    }

    public function getStudentsByCategoryControl($category_id){
        $status = array(1,2,5);
        $students = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'students',
                                    'alias' => 's',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        's.category_id = Category.id'
                                    )
                                )
                            ),
                            'conditions' => array(
                                'Category.id ' => $category_id,
                                'Category.name != ' => '',
                                's.name != ' => '',
                                's.status' => $status
                            ),
                            'recursive' => -1,
                            'fields' => array('s.id, s.name, s.lastname, s.birthday, s.status')
                        ));

        return $students;
    }

}