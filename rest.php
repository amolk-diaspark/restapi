<?php
header('Content-type: application/json');
require_once("object.php");
		
$controller = new cardController();
		
switch($_SERVER['REQUEST_METHOD']){
	case "GET":	
		if (isset($_GET["keyword"])){
			$list = $controller->readCard($_GET["keyword"]);
			if ($list == null){
				http_response_code(404);
				echo json_encode("Code Not Found.");							
			}else{
				echo json_encode($list);			
			}
		}else{
			http_response_code(400);
			echo "Search not found.";
		}
		break;
	case "PUT":
		try{
			$data = json_decode(file_get_contents('php://input'),true);			
			$card = new card($data["code"],$data["description"]);
			echo json_encode($controller->updateCard($card));		
		}catch (Exception $e){
			http_response_code(400);
			echo "Problem deserializing or updating card.";		
		}
		break;
	case "POST":
		try{
			$data = json_decode(file_get_contents('php://input'),true);			
			$card = new country($data["code"],$data["description"]);
			echo json_encode($controller->createCard($card));		
		}catch (Exception $e){
			http_response_code(400);
			echo "Problem deserializing or creating card.";		
		}
		break;
	case "DELETE":
		if (isset($_GET["code"]) && $controller->deleteCard($_GET["code"])){			
			echo json_encode(true);			
		}else{
			http_response_code(404);
			echo json_encode("Card Not Found.");			
		}
		break;
	default:
		http_response_code(501);
		echo "Unknown method.";
		break;
}
	
?>
