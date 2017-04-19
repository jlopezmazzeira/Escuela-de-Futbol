<?php

App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {

    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                $this->data[$this->alias]['password']
            );
        }

        if (isset($this->data[$this->alias]['password_update']) && !empty($this->data[$this->alias]['password_update'])) {
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password_update']);
        }

        return true;
    }

    public function getUsers($user_id){
    	$users = $this->find('all',array(
                            'joins' => array(
                                array(
                                    'table' => 'roles',
                                    'alias' => 'r',
                                    'type' => 'LEFT',
                                    'conditions' => array(
                                        'r.id = User.role_id'
                                    )
                                )
                            ),
                            'conditions' => array(
                                'User.status ' => 1,
                                'User.id !=' => $user_id
                            ),
                            'recursive' => -1,
                            'fields' => array('User.id, User.name, User.lastname,User.phone,User.email,User.address,
                            					User.username,r.name')
    		));

    	return $users;
    }
}