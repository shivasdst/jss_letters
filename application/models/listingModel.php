<?php


class listingModel extends Model {

	public function __construct() {

		parent::__construct();
	}

	public function listAlbums() {

		$dbh = $this->db->connect(DB_NAME);
		if(is_null($dbh))return null;
		
		$sth = $dbh->prepare('SELECT * FROM ' . METADATA_TABLE_L1 . ' ORDER BY albumID');
		
		$sth->execute();
		$data = array();
		
		while($result = $sth->fetch(PDO::FETCH_OBJ)) {

			array_push($data, $result);
		}
		$dbh = null;
		return $data;
	}

		public function listLetters($albumID, $pagedata) {

		$perPage = 10;
		$page = $pagedata["page"];
		$start = ($page-1) * $perPage;
		
		if($start < 0) $start = 0;

		$dbh = $this->db->connect(DB_NAME);
		
		if(is_null($dbh)) return null;
		
		$sth = $dbh->prepare('SELECT * FROM ' . METADATA_TABLE_L2 . ' WHERE albumID = :albumID ORDER BY id' . ' limit ' . $start . ',' . $perPage);
		$sth->bindParam(':albumID', $albumID);
		$sth->execute();
		
		$data = array();
		
		while($result = $sth->fetch(PDO::FETCH_OBJ)) {
			
			$result->albumID = $result->albumID;
			$result->Caption = $this->getDetailByField($result->description, 'Caption');
			$ids = explode("__", $result->id);
			$result->image = $this->getFirstImageInLetter($result->albumID, $ids[1]);
			$result->letterID = $ids[1];
			array_push($data, $result);
		}
		
		$dbh = null;

		if(!empty($data)){
			
			$data['albumDetails'] = $this->getAlbumDetails($albumID);
			$data["hidden"] = '<input type="hidden" class="pagenum" value="' . $page . '" />';
		}
		else{

			$data["hidden"] = '<div class="lastpage"></div>';	
		}

		return $data;
	}
	
	public function listCollections() {

		$collectionsFile = JSON_PRECAST_URL . "collections.json";
		$jsonData = file_get_contents($collectionsFile);
		$data = json_decode($jsonData,true);
		return $data;
	}
}

?>
