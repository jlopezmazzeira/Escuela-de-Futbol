<?php  

	/**
	* 
	*/
	class ProductsController extends AppController
	{
		var $uses = array('Product','Flash');
		// Carga la plantilla llamada layout
        function beforeFilter(){
            parent::beforeFilter();
            $this->layout = 'layout';
        }

		public function index(){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			$this->Product->recursive = -1;
        	$products = $this->Product->find('all', array('conditions' => array('status !=' => 0),'order' => array('id' => 'ASC')));
        	$size = sizeof($products);
        	$this->set('products',$products);
        	$this->set('size',$size);
		}

		public function add(){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			if ($this->request->is('post')) {
	            $data = $this->request->data;  // Obtengo valores enviados desde mi vista

    			$tupla = array('Product' => array( "name" => $data['name'],
	                    "description" => $data['description'],
	                    "cost" => $data['cost'],
	                    "status" => 1,
	                    "date_created" => date('Y-m-d')));
				
				$this->Product->create();

	            if ($this->Product->save($tupla)) {
	            	$description = "Se ha creado el producto: ".$data['name'];
                    $description .= " con la descripción: ".$data['description'];
                    $description .= " y costo: ".$data['cost'];
                    $description .= " y su id correspondiente es : ".$this->Product->id;
                    $log = array('Log'=>array(
                        'user_id' => $this->Auth->user('id'),
                        'description' => $description
                    ));
                    $this->SaveLog->saveData($log);

	                $this->Flash->success('Se guardó correctamente');
	                return $this->redirect(array('action' => 'index'));
	            }
	            $this->Flash->danger('Se produjo un error intente nuevamente');
	        }
		}

		public function edit($id = null){
			if ($this->Auth->user('role_id') != 1) {
                return $this->redirect(
                    array('controller' => 'home', 'action' => 'index')
                );
            }
			$this->Product->recursive = -1;

	        if (!$id) throw new NotFoundException(__('Invalid product'));

	        $product = $this->Product->findById($id);

	        if (!$product) throw new NotFoundException(__('No se encontro el producto'));

	        if ($this->request->is(array('post', 'put'))) {
	            $this->Product->id = $id;
	            $data = $this->request->data;
	            $product = array ('Product' => array( "name" => $data['name'],
	                                                 "description" => $data['description'],
	                    							 "cost" => $data['cost'],
	                                                 "date_modification" => date('Y-m-d')));

	            if ($this->Product->save($product)) {
	            	$description = "Se ha editado el producto: ".$data['name'];
                    $description .= " con la descripción: ".$data['description'];
                    $description .= " y costo: ".$data['cost'];
                    $description .= " y su id correspondiente es : ".$id;
                    $log = array('Log'=>array(
                        'user_id' => $this->Auth->user('id'),
                        'description' => $description
                    ));
                    $this->SaveLog->saveData($log);
	                $this->Flash->success('Se guardo correctamente');
	                return $this->redirect(array('action' => 'index'));
	            }
	            $this->Flash->danger('No se actualizó los datos del producto');
	        }

	        if (!$this->request->data) $this->set('product', $product);
		}

		public function delete(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
             	$this->Product->id = $this->data['product_id'];
             	$this->Product->saveField('status',0);
             	$this->Product->saveField('date_modification',date('Y-m-d'));
             	$product = $this->Product->findById($this->data['product_id']);
             	$description = "Se ha eliminado el producto: ".$product['Product']['name'];
                $description .= " con la descripción: ".$product['Product']['description'];
                $description .= " y costo: ".$product['Product']['cost'];
                $description .= " y su id correspondiente es : ".$this->data['product_id'];
                $log = array('Log'=>array(
                    'user_id' => $this->Auth->user('id'),
                    'description' => $description
                ));
                $this->SaveLog->saveData($log);

   				echo json_encode($this->data['product_id']);
			}
		}

		public function getCostProduct(){
			$this->autoRender = false;
  			
  			if($this->RequestHandler->isAjax()) Configure::write('debug', 0);

			if(!empty($this->data)){
             	$id = $this->data['product_id'];
             	$product = $this->Product->find('first',array('conditions' => array('id' => $id)));
             	echo $product['Product']['cost'];
			}
		}
	}