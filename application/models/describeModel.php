<?php

class describeModel extends Model {

	public function __construct() {

		parent::__construct();
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
			$details[$count]["Randomimage"] = $this->getRandomImageInAlbum($album);
			
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
