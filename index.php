<?php

	$user = "root";
	$pass = "";
	$pdo = new PDO('mysql:host=localhost;dbname=cc_devi_2017_02_b;charset=UTF8', $user, $pass);
	

	switch($_SERVER['REQUEST_METHOD']){
		case "GET":{
			$uri = $_SERVER['REQUEST_URI'];
			$path = parse_url($uri)['path'];
			switch($path){
				case "/cc/charges":{
					list_charges($_GET['annee'], $_GET['page']);
				}
				case "/cc/annee":{
					calcul_annee();
				}
			}
			break;
		}
		case "POST":{
			add_charge($_POST);
			break;
		}
		case "PUT":{
			//parse_str(file_get_contents("php://input"), $info);
			$info = json_decode(file_get_contents("php://input"),true);
			edit_charge($_GET['id'],$info);
			break;
		}
		case "GET":{
			echo "DELETE";
			break;
		}
	}
	
	function list_charges($annee, $page=1){
		global $pdo;
		$start = ($page-1)*20;
		$quantity = 20;
		$query = "SELECT * from charge WHERE annee = {$annee} ORDER BY mois, compte ASC LIMIT {$start}, {$quantity}";
		//echo $query;
		$rows = $pdo->query($query);
		$result = $rows->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode($result);
		echo $json;
	}
	
	function edit_charge($id, $charge){
		global $pdo;
		$compte = $charge['compte'];
		$montant = $charge['montant'];
		$query = "UPDATE charge SET compte = ?, montant = ? WHERE id = ?";
		$statement = $pdo->prepare($query);
		$result = $statement->execute([$compte, $montant, $id]);
		if($result)
			echo "OK";
		else
			echo "404";
	}
	
	function add_charge($charge){
		global $pdo;
		$query = "INSERT INTO charge (annee, mois, compte, montant) VALUES (?,?,?,?)";
		$statement = $pdo->prepare($query);
		$result = $statement->execute([$charge['annee'], $charge['mois'], $charge['compte'], $charge['montant']]);
		if($result)
			echo "Ok";
		else
			echo "401";
	}
	
	function calcul_annee(){
		echo "calcul_annee";
	}
