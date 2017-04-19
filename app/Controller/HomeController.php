<?php  

	/**
	* 
	*/
	class HomeController extends AppController
	{
		var $uses = array('Student','Scholarship','Order','Bill','DetailsBill','Taking','Flash');
		// Carga la plantilla llamada layout
        function beforeFilter(){
            parent::beforeFilter();
            $this->layout = 'layout';
        }

		public function home(){}

		public function index(){

			$this->Student->recursive = -1;
			$status = array(1,2);
			$students = $this->Student->find('all',array(
										'conditions' => array(
											'status' => $status, 
											'MONTH(date_created) !=' => date('n')
										)));
			$total_month = 0;
			foreach ($students as $student) {
			    $multiplier = 1;

		        $schorlarship = 0;
		        if(!empty($student['Student']['scholarship_id']))
		        	$schorlarship = $this->Scholarship->getValueScholarship($student['Student']['scholarship_id']);
		        else {
		        	$siblings = $this->Student->getSiblings($student['Student']['document_number'], $student['Student']['id']);
		        	
			        if($siblings == 1) $multiplier = 0.9;
			        else if ($siblings > 1) $multiplier = 0.8;	
		        }

		        $transport = 0;
		        if(!empty($student['Student']['routes_transport_id']))
		        	$transport = $this->CalculateAmount->amountTransport($student['Student']['routes_transport_id']);

		        $sub_total = 0;
		        $total_payment = 0;
		        $cost_pension = 0;

		        $IVA = $this->CalculateAmount->amountItem(1);

		        $fitness = ($student['Student']['fitness'] != 0) ? $this->CalculateAmount->amountItem(5) : 0;
	    		//$fitness = $fitness + ($fitness * $IVA / 100);
	    		$fitness = round($fitness,2);
		        
		        $extra_training = ($student['Student']['extra_training'] != 0) ? $this->CalculateAmount->amountItem(7) : 0;
		        //$extra_training = $extra_training + ($extra_training * $IVA / 100);
	    		$extra_training = round($extra_training,2);

		        if ($student['Student']['training_mode_id'] == 2) {
		        	$once_week = $this->CalculateAmount->amountItem(6);
		        	$cost_pension = $this->CalculateAmount->calculateRate($once_week,$multiplier,$schorlarship);
		        } else if($student['Student']['training_mode_id'] == 3){
		        	$monthly_rate = $this->CalculateAmount->amountItem(3);
		        	$cost_pension = $this->CalculateAmount->calculateRate($monthly_rate,$multiplier,$schorlarship);
		        }

		        $sub_total = $cost_pension + $fitness + $extra_training;
		        //$sub_total = $sub_total + ($sub_total * ($IVA / 100));
		        $total_payment = $sub_total + $transport;
		        $total_month += $total_payment;
			}

			$total_month = round($total_month,2);
			$total_pending = $this->Order->getPendingTotal();
			$total_pending = round($total_pending,2);

			$bills = $this->Bill->getBillsPaid();
			$cumulative_total = 0;
			foreach ($bills as $bill) {
				$details_bill = $this->DetailsBill->getDataDetail($bill['Bill']['id']);
				$sub_total = 0;
				foreach ($details_bill as $detail) {
					if ($detail['DetailsBill']['product'] != 'Transporte' && 
						$detail['DetailsBill']['product'] != 'Pension' && 
						$detail['DetailsBill']['product'] != 'Fitness' && 
						$detail['DetailsBill']['product'] != 'Extra Training') {
						
						$cost_item = $detail['DetailsBill']['cost'] - $detail['DetailsBill']['exoneration'];
						$cost_item = $cost_item / (1 + ($detail['DetailsBill']['iva'] / 100));
						$cost_item = round($cost_item,2);
						$sub_total_item = $cost_item * $detail['DetailsBill']['quantity'];
						//$sub_total_item = $sub_total_item * $detail['DetailsBill']['quantity'];
						//$sub_total_item = $sub_total_item + ($sub_total_item * ($detail['DetailsBill']['iva'] / 100));
						$sub_total += $sub_total_item;
					}
				}
				$total = $bill['Bill']['total'] - $sub_total;
				$cumulative_total += $total;
			}

			$cumulative_total = round($cumulative_total,2);
			$estimated_amount = $total_month + $total_pending;
			$estimated_amount = round($estimated_amount,2);
			$accumulated_percentage = ($estimated_amount != 0) ? ($cumulative_total * 100) / $estimated_amount : 0;
			$accumulated_percentage = round($accumulated_percentage,2);

			$remaining_amount = $estimated_amount - $cumulative_total;
			$remaining_amount = round($remaining_amount,2);
			$remaining_amount_percentage = ($estimated_amount != 0) ? ($remaining_amount * 100) / $estimated_amount : 0;
			$remaining_amount_percentage = round($remaining_amount_percentage,2);

			$months = ["CERO","Enero","Febrero","Marzo","Abril","Mayo","Junio","Juio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
			$month = date('n');
			$month = intval($month);

			$takings = $this->Taking->getTakings();

			for ($i=0; $i < count($takings); $i++) {
				$takings[$i]['Taking']['remaining_amount_percentage'] = ($takings[$i]['Taking']['estimated_amount'] != 0) ? ($takings[$i]['Taking']['remaining_amount'] * 100) / $takings[$i]['Taking']['estimated_amount'] : 0;
				$takings[$i]['Taking']['remaining_amount_percentage'] = round($takings[$i]['Taking']['remaining_amount_percentage'],2);
				
				$takings[$i]['Taking']['accumulated_percentage'] = ($takings[$i]['Taking']['estimated_amount'] != 0) ? ($takings[$i]['Taking']['cumulative_total'] * 100) / $takings[$i]['Taking']['estimated_amount'] : 0;
				$takings[$i]['Taking']['accumulated_percentage'] = round($takings[$i]['Taking']['accumulated_percentage'],2);
			}

			$this->set('remaining_amount_percentage',$remaining_amount_percentage);
			$this->set('accumulated_percentage',$accumulated_percentage);
			$this->set('estimated_amount',$estimated_amount);
			$this->set('cumulative_total',$cumulative_total);
			$this->set('remaining_amount',$remaining_amount);
			$this->set('role_id',$this->Auth->user('role_id'));
			$this->set('month',$month);
			$this->set('months',$months);
			$this->set('takings',$takings);
			$this->set('size',sizeof($takings));
		}
	}