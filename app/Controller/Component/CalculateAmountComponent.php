<?php
	App::uses('Component', 'Controller');
	class CalculateAmountComponent extends Component {
		
		function calculateRate($rate,$multiplier,$schorlarship){
    		$rate = $rate * $multiplier; // Tarifa con Descuento de Hermanos ACTIVOS
    		$total = $rate - (($rate * $schorlarship) /100);  //Tarifa con descuento de BECA
    		return round($total,2);
		}

		function calculateAmountInscription(){
			$Parameter = ClassRegistry::init('Parameter');
			$cost_inscription = $Parameter->getValueParameter(2);
			return $cost_inscription;
		}

		function calculateAmountItem($parameter_id,$day){
			$Parameter = ClassRegistry::init('Parameter');
			$cost_pension = $Parameter->getValueParameter($parameter_id);
			$cost_pension_day = ($cost_pension / 30);
			$days_pay = 30;
			if ($day == 1) $day = 0;
			elseif ($day > 1) $day = $day - 1;
			if ($day < 30) $days_pay = 30 - $day;
			$cost_pension_pay = $cost_pension_day * ($days_pay);
			$total = $cost_pension_pay;
			$total = round($total,2);
    		return $total;
		}

		function calculateAmountTransport($routes_transport_id,$day){
			$Route = ClassRegistry::init('Route');
			$Route->recursive = -1;
	        $route_transport = $Route->getRoute($routes_transport_id);
	        $cost_transport = $route_transport['Route']['cost'];
	        $cost_transport_day = ($cost_transport / 30);
			$days_pay = 30;
			if ($day == 1) $day = 0;
			elseif ($day > 1) $day = $day - 1;
			if ($day < 30) $days_pay = 30 - $day;
			$total = $cost_transport_day * ($days_pay);
			$total = round($total,2);
    		return $total;
		}

		function amountTransport($routes_transport_id = ''){
			$Route = ClassRegistry::init('Route');
			$Route->recursive = -1;
	        $route_transport = $Route->getRoute($routes_transport_id);
	        $cost_transport = $route_transport['Route']['cost'];
    		return $cost_transport;
		}

		function amountItem($parameter_id){
			$Parameter = ClassRegistry::init('Parameter');
			$cost_item = $Parameter->getValueParameter($parameter_id);
    		return $cost_item;
		}
	}