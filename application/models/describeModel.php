<?php

class describeModel extends Model {

	public function __construct() {

		parent::__construct();
	}
	
	public function getDetails($journal = DEFAULT_JOURNAL, $volume = DEFAULT_VOLUME, $issue = DEFAULT_ISSUE, $page = DEFAULT_PAGE) {

		$dbh = $this->db->connect($journal);
		if(is_null($dbh))return null;
			
		$sth = $dbh->prepare('SELECT * FROM ' . METADATA_TABLE . ' WHERE journal=:journal AND volume=:volume AND issue=:issue AND page=:page');
		$sth->bindParam(':journal', $journal);
		$sth->bindParam(':volume', $volume);
		$sth->bindParam(':issue', $issue);
		$sth->bindParam(':page', $page);
		
		$sth->execute();

		$result = $sth->fetch(PDO::FETCH_OBJ);
		$dbh = null;
		return $result;
	}

	public function getDetailsByName($name = '', $fetch = 'FELLOW') {

		$dbh = $this->db->connect(GENERAL_DB_NAME);
		if(is_null($dbh))return null;
		
		$bindName = preg_replace('/\_/', ' ', $name);
		$sth = $dbh->prepare('SELECT * FROM ' . constant($fetch . '_TABLE') . ' WHERE name=:name');
		$sth->bindParam(':name', $bindName);
		
		$sth->execute();

		$result = $sth->fetch(PDO::FETCH_OBJ);
		$dbh = null;
		return $result;
	}
	
	public function getAlbums($collectionID,$pageData) {
		$perPage = 10;

		$page = $pageData["page"];

		$start = ($page-1) * $perPage;

		if($start < 0) $start = 0;

		$data = array();
		$albumList = array();

		$collectionsFile = JSON_PRECAST_URL . "collections.json";
		$jsonData = file_get_contents($collectionsFile);
		$data = json_decode($jsonData,true);
		foreach ($data as $collection){
			if($collection['collectionID'] == $collectionID){

				$albumList = (object)array_slice($collection["albumList"], $start,$perPage);
				break;
			}
		}	

		$count = 0;
		$details = array();

		foreach ($albumList as $album){
			$details[$count]["collectionID"] = $collectionID;
			$details[$count]["albumID"] = $album;
			$details[$count]["Lettercount"] = $this->getLetterCount($album);
			$details[$count]["Title"] = $this->getDetailByFieldUsingAlbumID($album, 'Title');
			$details[$count]["Event"] = $this->getDetailByFieldUsingAlbumID($album, 'Event');
			$details[$count]["Randomimage"] = $this->getRandomImage($album);
			
			$count++;	
		}
	
		if(!empty($details)){

			$details["hidden"] = '<input type="hidden" class="pagenum" value="' . $page . '" />';
		}
		else{

			$details["hidden"] = '<div class="lastpage"></div>';	
		}		

		return $details;
	}
}

?>
