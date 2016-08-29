<?php

class dataModel extends Model {

	public function __construct() {

		parent::__construct();
	}

	public function listFiles($path, $type) {

		return glob($path . '*.' . $type);
	}

	public function insertAlbums($albums, $dbh) {

		foreach ($albums as $album) {
			
			$data['albumID'] = preg_replace('/.*\/(.*)\.json/', "$1", $album);
			$data['description'] = $this->getJsonFromFile($album);
			
			$this->db->insertData(METADATA_TABLE_L1, $dbh, $data);
		}
	}

	public function insertPhotos($photos, $dbh) {

		foreach ($photos as $photo) {
			
			$data['id'] = preg_replace('/.*\/(.*)\.json/', "$1", $photo);
			$data['albumID'] = preg_replace('/.*\/(.*)\/.*\.json/', "$1", $photo);
			$data['id'] = $data['albumID'] . "__" . $data['id'];
			$albumDescription = $this->getAlbumDetails($data['albumID']);
			$albumDescription = $albumDescription->description;
			$photoDescription = $this->getJsonFromFile($photo);
			
			$data['description'] = json_encode(array_merge(json_decode($photoDescription, true), json_decode($albumDescription, true)));

			$this->db->insertData(METADATA_TABLE_L2, $dbh, $data);
		}
	}

	public function getJsonFromFile($path) {

		return file_get_contents($path);
	}
}

?>
