<?php
	App::uses('Component', 'Controller');
	class SaveLogComponent extends Component {

	    function saveData($log){
	        ClassRegistry::init('Log')->save($log);
	    }
	}