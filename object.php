<?php

class card {

	public $code;
	public $description;

	public function  __construct($code,$description){
		$this->code = $code;	
		$this->name =$description;
	}

}

class cardController{

	private $db;
	private $selectCardCmd;
	private $deleteCardCmd;
	private $updateCardCmd;
	private $insertCardCmd;

	public function  __construct(){
		$this->db = new mysqli('localhost', 'root', '', 'tripsorter');
		$this->selectCardCmd = $this->db->prepare("SELECT * FROM tblcard WHERE code = ?");
		$this->deleteCardCmd = $this->db->prepare("DELETE FROM tblcard WHERE code = ?");
		$this->updateCardCmd = $this->db->prepare("UPDATE tblcard SET description = ? WHERE code = ?");
		$this->insertCardCmd = $this->db->prepare("INSERT INTO tblcard(code, description) VALUES (?,?)");
	}
	
	
	public function createCard($card){ //C - Create	card
		$this->selectCardCmd->bind_param("s", $card->code);
		$this->selectCardCmd->execute();
		$result = $this->selectCardCmd->get_result();
		if ($result->num_rows > 0){
			$this->updateCardCmd->bind_param("ss", $card->description,$card->code);			
			if (!$this->updateCardCmd->execute()){
				echo "Command failed: (" . $this->db->errno . ") " . $this->db->error;
				return false;
			}else{
				return true;			
			}
		}else{
			$this->insertCardCmd->bind_param("ss", $card->code, $card->description);
			if (!$this->insertCardCmd->execute()){
				echo "Command failed: (" . $this->db->errno . ") " . $this->db->error;
				return false;
			}else{
				return true;			
			}
		}
	}
	
	public function readCard($keyword){ //R - Read card
		$arr=explode(',',$keyword);		
		if(count($arr)>0){
		$return_arr=array();
		for($i=0;$i<count($arr);$i++){
		$this->selectCardCmd->bind_param("s", $arr[$i]);
		$this->selectCardCmd->execute();
		$result = $this->selectCardCmd->get_result();

			if ($result->num_rows > 0){
				$cardRow = $result->fetch_assoc();
				$return_arr[$cardRow['id']]=$cardRow['description'];
				}
		}
		
		ksort($return_arr, SORT_NATURAL | SORT_FLAG_CASE);
			return $return_arr;
		}else{
			return null;
		}
	}

	public function updateCard($card){ //U - Update card
		return $this->createCard($card);
	}
	
	public function deleteCard($code){ //D - Delete card
		if($this->readCard($code) != null){
			$this->deleteCardCmd->bind_param("s", $code);
			$this->deleteCardCmd->execute();
			return true;
		}else{
			return false;
		}
	}

}


?>
