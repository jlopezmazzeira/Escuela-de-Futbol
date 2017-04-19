<?php
App::uses('AppController', 'Controller');
App::uses('String', 'Utility');
class UsersController extends AppController {

    //public $components = array('Recaptcha.Recaptcha');
    var $uses = array('User','Token','Category','CategoriesEmployee','Flash');

    public function beforeFilter() {
	    parent::beforeFilter();
        $this->Auth->allow('logout','forgotPassword','recoverPassword');
        if ($this->action != 'login' && $this->action != 'recoverPassword'){
            $this->layout = 'layout';
        }
	}

    public function index(){
        if ($this->Auth->user('role_id') != 1) {
            return $this->redirect(
                array('controller' => 'home', 'action' => 'index')
            );
        }
        $user_id = $this->Auth->user('id');
        $users = $this->User->getUsers($user_id);
        $size = sizeof($users);
        $this->set('users',$users);
        $this->set('size',$size);

    }

    public function add(){
        if ($this->Auth->user('role_id') != 1) {
            return $this->redirect(
                array('controller' => 'home', 'action' => 'index')
            );
        }
        if($this->request->is('post')){
            $data = $this->request->data;
            $password = '12345678';
            
            $user_verify = $this->User->find('first',array('conditions' => array('username' => $data['username']), 'fields' => array('id')));
            if (empty($user_verify)) {
                $user = array('User' => array( 
                    "name" => $data['name'],
                    "lastname" => $data['lastname'],
                    "phone" => $data['phone'],
                    "email" => $data['email'],
                    "username" => $data['username'],
                    "password" => $password,
                    "address" => $data['address'],
                    "role_id" => $data['role'],
                    "status" => 1,
                    "date_created" => date('Y-m-d')));
            
                $this->User->create();

                if ($this->User->save($user)) {
                    if ($data['role'] == 3 && isset($data['Category'])) {
                        foreach ($data['Category'] as $category) {
                            $this->CategoriesEmployee->create();
                            $category_employee = array('CategoriesEmployee' => array(
                                                'category_id' => $category,
                                                'employee_id' => $this->User->id
                                ));
                            $this->CategoriesEmployee->save($category_employee);
                        }
                    }

                    $title_message = "Creación de usuario";
                    $message = "Usted ha sido registrado en el portal.";
                    $message .="Usuario: ".$data['username'].", ";
                    $message .= "Clave: ".$password.".";
                    App::uses('CakeEmail', 'Network/Email');                        
                    $Email = new CakeEmail('gmail');
                    $Email->from('gefecuador@gmail.com');
                    $Email->to($data['email']);
                    $Email->subject($title_message);
                    $Email->send($message);

                    $this->Flash->success('Se guardó correctamente');
                    return $this->redirect(array('action' => 'index'));
                }
            } else $this->Flash->danger('El nombre de usuario ya existe, intente con otro');
            
            $this->Flash->danger('Se produjo un error intente nuevamente');
        }

        $categories = $this->Category->find('all', array('conditions' => array('status !=' => 0),'order' => array('id' => 'ASC')));
        $this->set('categories',$categories);
    }

    public function edit($id = null){
        if ($this->Auth->user('role_id') != 1) {
            return $this->redirect(
                array('controller' => 'home', 'action' => 'index')
            );
        }
        $this->User->recursive = -1;

        if (!$id) throw new NotFoundException(__('Invalid user'));

        $user = $this->User->findById($id);

        if (!$user) throw new NotFoundException(__('No se encontro el usuario'));

        if ($this->request->is(array('post', 'put'))) {
            $this->User->id = $id;
            $data = $this->request->data;
            $user = array ('User' => array( 
                                        "name" => $data['name'],
                                        "lastname" => $data['lastname'],
                                        "address" => $data['address'],
                                        "phone" => $data['phone'],
                                        "email" => $data['email'],
                                        "username" => $data['username'],
                                        "address" => $data['address'],
                                        "role_id" => $data['role'],
                                        "date_modification" => date('Y-m-d')));
            if ($this->User->save($user)) {
                if ($data['role'] == 3 && isset($data['Category'])) {
                    $categories_employee = $this->CategoriesEmployee->find('all', array('conditions' => array('employee_id' => $id)));
                    $size = sizeof($categories_employee);
                    if ($size == 0) {
                        foreach ($data['Category'] as $category) {
                            $this->CategoriesEmployee->create();
                            $category_employee = array('CategoriesEmployee' => array(
                                                'category_id' => $category,
                                                'employee_id' => $id
                                ));
                            $this->CategoriesEmployee->save($category_employee);
                        }
                        $this->Flash->success('Se guardo correctamente');
                        return $this->redirect(array('action' => 'index'));
                    } else {

                        $categories_previous = array();
                        foreach ($categories_employee as $category) {
                            $categories_previous[] = $category['CategoriesEmployee']['category_id'];
                        }

                        foreach ($data['Category'] as $category) {

                            if (!in_array($category, $categories_previous)) {
                                $this->CategoriesEmployee->create();
                                $category_employee = array('CategoriesEmployee' => array(
                                                    'category_id' => $category,
                                                    'employee_id' => $id
                                    ));
                                $this->CategoriesEmployee->save($category_employee);
                                $categories_previous[] = $category;
                            }
                        }

                        foreach ($categories_previous as $category) {
                            if (!in_array($category, $data['Category'])) {
                                $category_employee = $this->CategoriesEmployee->find('first',array('conditions' => array('category_id' => $category, 'employee_id' => $id)));
                                $category_employee_id = $category_employee['CategoriesEmployee']['id'];
                                $this->CategoriesEmployee->id = $category_employee_id;
                                $this->CategoriesEmployee->delete($category_employee_id); 
                            }
                        }
                    }
                    $this->Flash->success('Se guardo correctamente');
                    return $this->redirect(array('action' => 'index'));
                }
                $this->Flash->success('Se guardo correctamente');
                return $this->redirect(array('action' => 'index'));
            }
            
            $this->Flash->danger('No se actualizó los datos del usuario');
        }

        if (!$this->request->data) {
            $categories = $this->Category->find('all', array('conditions' => array('status !=' => 0),'order' => array('id' => 'ASC')));
            $this->CategoriesEmployee->recursive = -1;
            $categories_employee = $this->CategoriesEmployee->find('all', array(
                'conditions' => array('employee_id' => $id),
                'fields'=>array('category_id')
            ));

            $categories_id = array();
            foreach ($categories_employee as $temp):
                $categories_id[] = $temp['CategoriesEmployee']['category_id'];
            endforeach;
            $this->set('categories',$categories);
            $this->set('categories_id', $categories_id);
            $this->set('user', $user);
        }
    }

    public function delete(){
        $this->autoRender = false;
        
        if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

        if(!empty($this->data)){
            $user = $this->data['user_id'];
            $this->User->id = $user;
            $this->User->saveField('status',0);
            $this->User->saveField('date_modification',date('Y-m-d'));
            $this->CategoriesEmployee->deleteAll(array('employee_id' => $user));
            $delete = true;
            echo json_encode($delete);
        }
    }

    public function profile($id = null){
        $this->User->recursive = -1;

        if (!$id) throw new NotFoundException(__('Invalid user'));

        $user = $this->User->findById($id);

        if (!$user) throw new NotFoundException(__('No se encontro el usuario'));

        if ($this->request->is(array('post', 'put'))) {
            $this->User->id = $id;
            $data = $this->request->data;
            $user_verify = $this->User->find('first',array('conditions' => array('username' => $data['username']), 'fields' => array('id')));
            if (empty($user_verify)) {
                $user = array ('User' => array(
                                        "name" => $data['name'],
                                        "lastname" => $data['lastname'],
                                        "address" => $data['address'],
                                        "phone" => $data['phone'],
                                        "email" => $data['email'],
                                        "username" => $data['username'],
                                        "address" => $data['address'],
                                        "date_modification" => date('Y-m-d')));
                if ($this->User->save($user)) {
                    $this->Flash->success('Se guardo correctamente');
                    return $this->redirect(array('action' => 'profile/'.$id));
                }
            } else {
                if ($id == $user_verify['User']['id']) {
                    $user = array ('User' => array(
                                        "name" => $data['name'],
                                        "lastname" => $data['lastname'],
                                        "address" => $data['address'],
                                        "phone" => $data['phone'],
                                        "email" => $data['email'],
                                        "username" => $data['username'],
                                        "address" => $data['address'],
                                        "date_modification" => date('Y-m-d')));
                    if ($this->User->save($user)) {
                        $this->Flash->success('Se guardo correctamente');
                        return $this->redirect(array('action' => 'profile/'.$id));
                    }
                } else {
                    $this->Flash->danger('El nombre del usuario ya existe');
                    return $this->redirect(array('action' => 'profile/'.$id));        
                }
            }
            $this->Flash->danger('No se actualizó los datos del usuario');
            return $this->redirect(array('action' => 'profile/'.$id));
        }

        if (!$this->request->data) $this->set('user', $user);
    }

    public function changePassword(){
        if ($this->request->is(array('post', 'put'))) {
            $this->User->id = $this->Auth->user('id');
            $data = $this->request->data;
            $user = array ('User' => array(
                                    "password" => $data['password_update'],
                                    "date_modification" => date('Y-m-d')));
            if ($this->User->save($user)) {
                $this->Flash->success('Se guardo correctamente');
                return $this->redirect(array('action' => 'changePassword'));
            }
            $this->Flash->danger('No se actualizó los datos del usuario');

        }
    }

    public function forgotPassword(){
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $user = $this->User->find('first', array('conditions' => array('email' => $data['email'])));
            if(!empty($user)) {
                $this->User->id = $user['User']['id'];
                $token = String::uuid();

                $this->Token->create();
                $token_new = array('Token'=>array(
                        "token"=>$token,
                        "user_id"=>$user['User']['id']
                ));

                $this->Token->save($token_new);
                $title_message = "Cambio de Contraseña";
                $message = "Ha solicitado el cambio de contraseña, si fue usted presione el siguiente ";
                $message .="el siguiente enlace: http://localhost/escuela/users/recoverPassword/".$token."/".$user['User']['id'];
                $message .= " Si no ignore el siguiente mensaje";
                App::uses('CakeEmail', 'Network/Email');                        
                $Email = new CakeEmail('gmail');
                $Email->from('gefecuador@gmail.com');
                $Email->to($user['User']['email']);
                $Email->subject($title_message);
                if ($Email->send($message)){
                    $this->Flash->success('Para completar el proceso vaya a su correo electrónico');
                    $this->redirect(array('action' => 'login'));    
                } else $this->Flash->danger('Se ha ocurrido un problema al tratar de enviar el correo');
                
            }
            $this->Flash->danger('Se ha ocurrido un problema, contacte al administrador');
            $this->redirect(array('action' => 'login'));
        }
    }

    public function recoverPassword($token = null,$user_id = null){

        $token_verify = $this->Token->find('first', array('conditions' => array('token' => $token,'user_id' => $user_id)));
        if ($this->request->is('post')) {
            if (!empty($token_verify)) {
                $data = $this->request->data;            
                $this->User->id = $user_id;
                $user = array ('User' => array(
                                        "password" => $data['password_update'],
                                        "date_modification" => date('Y-m-d')));
                if ($this->User->save($user)) {
                    $this->Token->delete($token_verify['Token']['id']);
                    $this->Flash->success('Cambio de contraseña exitosa');
                    return $this->redirect(array('action' => 'login'));
                }

                $this->Flash->danger('No se actualizó los datos del usuario');   
            } else {
                $this->Flash->danger('El token no existe');
                return $this->redirect(array('action' => 'login'));
            }
        }

        $this->set('token',$token);
        $this->set('user_id',$user_id);
    }

	public function login() {
	    if ($this->request->is('post')) {
            //if ($this->Recaptcha->verify()) {
                $data = $this->request->data;
                $user = $this->User->find('first', array('conditions' => array('username' => $data['User']['username'])));
                if (!empty($user)) {
                    if ($user['User']['status'] == 1) {
                        if ($this->Auth->login()) return $this->redirect($this->Auth->redirectUrl());
                    } else {
                        $this->Flash->danger('Su cuenta se encuentra inhabilitada, contacte al administrador');    
                        return $this->redirect(array('action' => 'login'));    
                    }
                }
                $this->Flash->danger('Usuario o/y Contraseña invalida');
            /*} else {
                $this->Flash->danger('Por favor introduzca el captcha');
            }*/
	    }
	}

	public function logout() {
	    return $this->redirect($this->Auth->logout());
	}

}