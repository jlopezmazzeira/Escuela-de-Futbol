<?php

/**
* 
* 
*/
class DetailsControl extends AppModel{
    var $name = 'DetailsControl';

    public function getAbsentByStudent($student_id = ''){
    	$this->virtualFields['total_assistance'] = 'COUNT(assistance)';
    	$num = $this->find('first', array(
                            'joins' => array(
                                array(
                                    'table' => 'controls',
                                    'alias' => 'c',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'c.id = DetailsControl.control_id'
                                    )
                                )
                            ),
                            'conditions' => array('MONTH(c.date_control)' => date('n'),
                                                  'DetailsControl.student_id' => $student_id,
                                                  'DetailsControl.assistance' => 0),
                            'fields' => array('total_assistance')
                            ));

        return $num['DetailsControl']['total_assistance'];
    }

    public function getAssistanceByStudent($student_id = ''){
        $this->virtualFields['total_assistance'] = 'COUNT(assistance)';
        $num = $this->find('first', array(
                            'joins' => array(
                                array(
                                    'table' => 'controls',
                                    'alias' => 'c',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'c.id = DetailsControl.control_id'
                                    )
                                )
                            ),
                            'conditions' => array('MONTH(c.date_control)' => date('n'),
                                                  'DetailsControl.student_id' => $student_id,
                                                  'DetailsControl.assistance' => 1),
                            'fields' => array('total_assistance')
                            ));

        return $num['DetailsControl']['total_assistance'];
    }

    public function getAssistanceByStudentAndDate($student_id = '',$date_from = '', $date_until = ''){
    	$assistance = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'controls',
                                    'alias' => 'c',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'c.id = DetailsControl.control_id'
                                    )
                                )
                            ),
                            'conditions' => array('c.date_control >=' => $date_from, 'c.date_control <=' => $date_until,
                                                  'DetailsControl.student_id' => $student_id),
                            'fields' => array('DetailsControl.assistance, c.date_control')
                            ));

        return $assistance;
    }

    public function getAssistanceByCategoryAndDate($category_id = '',$date_from = '', $date_until = ''){
        $status = array(1,2,5);
        $assistance = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'controls',
                                    'alias' => 'c',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'c.id = DetailsControl.control_id'
                                    )
                                ),
                                array(
                                    'table' => 'students',
                                    'alias' => 's',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        's.id = DetailsControl.student_id'
                                    )
                                )
                            ),
                            'conditions' => array('c.date_control >=' => $date_from, 'c.date_control <=' => $date_until,
                                                  'c.category_employee_id' => $category_id, 's.status' => $status),
                            'fields' => array('DetailsControl.assistance, DetailsControl.student_id, DetailsControl.control_id')
                            ));

        return $assistance;
    }

    public function getAssistanceByCategoryAndDateControl($category_id = '',$date_control = ''){
        $status = array(1,2,5);
        $assistance = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'controls',
                                    'alias' => 'c',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'c.id = DetailsControl.control_id'
                                    )
                                ),
                                array(
                                    'table' => 'students',
                                    'alias' => 's',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        's.id = DetailsControl.student_id'
                                    )
                                )
                            ),
                            'conditions' => array('c.date_control' => $date_control,'c.category_employee_id' => $category_id, 
                                                's.status' => $status),
                            'fields' => array('DetailsControl.id, DetailsControl.assistance, DetailsControl.student_id, 
                                               DetailsControl.control_id, s.name, s.lastname, s.id, DetailsControl.observation,
                                               s.birthday, s.status')
                            ));

        return $assistance;
    }

}