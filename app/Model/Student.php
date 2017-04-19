<?php

/**
* 
* 
*/
class Student extends AppModel{
    var $name = 'Student';
    /*Relation one to many => hasMany*/
    /*Relation one to one => hasOne*/
    /*Relation many to many => hasAndBelongsToMany*/
    /*Relation many to one*/
    var $belongsTo = array(
        'people' => array(
            'className' => 'people',
            'foreignKey' => 'people_id',
        )
    );

    function getStudents() {
    	$students = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'categories',
                                    'alias' => 'c',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'c.id = Student.category_id'
                                    )
                                )
                            ),
                            'conditions' => array('Student.status !=' => 4),
                            'order' => 'Student.lastname ASC',
                            'fields' => array('Student.id, Student.name, Student.lastname, Student.email, 
                            	Student.birthday, Student.responsable, Student.status, Student.date_inscription, 
                            	c.name, Student.alternative_email')
                        ));

    	return $students;
    }

    function getStudentById($id = null) {
        $student = $this->find('first', array(
                            'joins' => array(
                                array(
                                    'table' => 'categories',
                                    'alias' => 'c',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'c.id = Student.category_id'
                                    )
                                )
                            ),
                            'conditions' => array('Student.id' => $id),
                            'fields' => array('Student.id, Student.name, Student.lastname, Student.email, 
                                Student.birthday, Student.responsable, Student.phone, Student.home_phone, Student.address, 
                                c.name, Student.status, Student.fitness, Student.document_number, Student.date_inscription,
                                Student.extra_training, Student.siblings, Student.training_mode_id, Student.routes_transport_id,
                                Student.scholarship_id, Student.document_img, Student.exonerated, Student.observation')
                        ));

        return $student;
    }

    function getStudentsByStatus($status) {
    	$date = date('Y-m-d');
        $conditions = array();
        if ($status == 4 || $status == 2) {
            $conditions = array('Student.status' => $status);
        } else {
            $conditions = array('DATEDIFF("'.$date.'",Student.date_inscription) <' => 350,
                                'Student.status' => $status);
        }

        $students = $this->find('all', array(  
                            'joins' => array(
                                array(
                                    'table' => 'categories',
                                    'alias' => 'c',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'c.id = Student.category_id'
                                    )
                                )
                            ),
                            'conditions' => $conditions,
                            'order' => 'Student.lastname ASC',
                            'fields' => array('Student.id, Student.name, Student.lastname, Student.email, 
                                Student.birthday, Student.responsable, c.name, Student.alternative_email, 
                                Student.status, Student.date_inscription')
                            ));
    	return $students;
    }

    function getStudentsNews() {
    	$students = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'categories',
                                    'alias' => 'c',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'c.id = Student.category_id'
                                    )
                                )
                            ),
                            'conditions' => array('MONTH(Student.date_created)' => date('n'),
                                                  'Student.status' => 1),
                            'order' => 'Student.lastname ASC',
                            'fields' => array('Student.id, Student.name, Student.lastname, Student.email, 
                                Student.birthday, Student.responsable, c.name,Student.date_created')
                            ));
    	return $students;
    }

    function getStudentsNewsByDateInscription($date_from,$date_until) {
        $students = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'categories',
                                    'alias' => 'c',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'c.id = Student.category_id'
                                    )
                                )
                            ),
                            'conditions' => array('MONTH(Student.date_created)' => date('n'),
                                                  'Student.status' => 1,
                                                  'Student.date_created >=' => $date_from,
                                                  'Student.date_created <=' => $date_until),
                            'order' => 'Student.lastname ASC',
                            'fields' => array('Student.id, Student.name, Student.lastname, Student.email, 
                                Student.birthday, Student.responsable, c.name,Student.date_created')
                            ));
        return $students;
    }

    function getSiblingsActive($document_number){
        $status = array(1,2);
        $this->virtualFields['siblings'] = 'COUNT(Student.document_number)';
    	$siblings = $this->find('first',array('fields' => array('siblings'),
                                            'conditions' => array(
    												'Student.document_number' => $document_number, 
    												'Student.status' => $status)
                                            ));
        $siblings = $siblings['Student']['siblings'];
        return $siblings;
    }

    function getSiblings($document_number,$student_id){
        $status = array(1,2);
        $this->virtualFields['siblings'] = 'COUNT(Student.document_number)';
        $siblings = $this->find('first',array('fields' => array('siblings'),
                                            'conditions' => array(
                                                    'Student.document_number' => $document_number,
                                                    'Student.id !=' => $student_id,
                                                    'Student.status' => $status)
                                            ));

        $siblings = $siblings['Student']['siblings'];
        return $siblings;
    }

    function getStudentsByDocumentNumber($document_number){
        $this->virtualFields['number_students'] = 'COUNT(Student.document_number)';
        $students = $this->find('all',array('fields' => array('number_students'),
                                        'conditions' => array('Student.document_number' => $document_number)
                                        ));

        $number_students = $students['Student']['number_students'];
        return $number_students;
    }

    function getStudentsRenovate() {
        $date = date('Y-m-d');
        $students = $this->find('all', array(
                            'joins' => array(
                                array(
                                    'table' => 'categories',
                                    'alias' => 'c',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'c.id = Student.category_id'
                                    )
                                )
                            ),
                            'conditions' => array('DATEDIFF("'.$date.'",Student.date_inscription) >=' => 350,
                                                  'Student.status' => 1),
                            'order' => 'Student.lastname ASC',
                            'fields' => array('Student.id, Student.name, Student.lastname, Student.email, 
                                Student.birthday, Student.responsable, c.name,Student.date_created, Student.status, 
                                Student.date_inscription')
                            ));
        return $students;
    }
}