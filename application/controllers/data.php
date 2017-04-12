<?php

class data extends Controller {

	public function __construct() {
		
		parent::__construct();
	}

	public function index() {

		$this->insertPhotoDetails();
	}

	public function insertDetails(){

		$this->model->db->createDB(DB_NAME, DB_SCHEMA);
		$dbh = $this->model->db->connect(DB_NAME);

		$this->model->db->dropTable(METADATA_TABLE_L1, $dbh);
		$this->model->db->createTable(METADATA_TABLE_L1, $dbh, METADATA_TABLE_L1_SCHEMA);

		$this->model->db->dropTable(METADATA_TABLE_L2, $dbh);
		$this->model->db->createTable(METADATA_TABLE_L2, $dbh, METADATA_TABLE_L2_SCHEMA);

		$this->model->db->createTable(METADATA_TABLE_L3, $dbh, METADATA_TABLE_L3_SCHEMA);
		$this->model->db->createTable(METADATA_TABLE_L4, $dbh, METADATA_TABLE_L4_SCHEMA);
		
		//List albums
		$albums = $this->model->listFiles(PHY_LETTER_URL, 'json');
		if($albums) {

			$this->model->insertAlbums($albums, $dbh);

			foreach ($albums as $album) {
			

				// List photos
				$letters = $this->model->listFiles(str_replace('.json', '/', $album), 'json');

				if($letters) {

					$this->model->insertLetters($letters, $dbh);
				}
				else{

					echo 'Album ' . $album . ' does not have any letters' . "<br/>";
				}
			}
		}
		else{

			echo 'No albums to insert';
		}

		$dbh = null;
	}
	
	public function updateLetterJson($albumID) {
		
		$data = $this->model->getPostData();

		$fileContents = array();
		
		foreach($data as $value){

			$fileContents[$value[0]] = $value[1];
		}
		$letterID = $fileContents['id'];
		$path = PHY_LETTER_URL . $fileContents['albumID'] . "/" . $fileContents['id'] . ".json";

		$letterUrl = BASE_URL . 'describe/letter/' . $fileContents['albumID'] . "/" . $fileContents['albumID'] . "__" . $fileContents['id'];

		$fileContents = json_encode($fileContents,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

		if(file_put_contents($path,$fileContents))
		{
			$this->updateLetterDetails($letterID,$albumID,$fileContents);
			$this->updateRepo();
		}
		else
		{
			echo "Problem in writing data to a file";
		}
		
		// ($data) ? $this->postman($data) : $this->view('error/prompt', array('msg' => FB_FAILURE_MSG));
	}
	private function updateLetterDetails($letterID,$albumID,$photoJsonData){

			$dbh = $this->model->db->connect(DB_NAME);
			$albumDescription = $this->model->getAlbumDetails($albumID);
			$albumDescription = $albumDescription->description;
			$photoDescription = $photoJsonData;

			$combinedDescription = json_encode(array_merge(json_decode($photoDescription, true), json_decode($albumDescription, true)),JSON_UNESCAPED_UNICODE);

			$photoID = $albumID . "__" . $letterID;

			$this->model->db->updatePhotoDescription($photoID,$albumID,$combinedDescription,$dbh);
	}


	public function updateAlbumJson() {
		
		$data = $this->model->getPostData();
		$fileContents = array();
		
		foreach($data as $value){

			$fileContents[$value[0]] = $value[1];
		}

		$albumID = $fileContents['albumID'];

		$path = PHY_LETTER_URL . $albumID . ".json";

		$albumUrl = BASE_URL . 'listing/photos/' . $fileContents['albumID'];

		$fileContents = json_encode($fileContents,JSON_UNESCAPED_UNICODE);


		if(file_put_contents($path,$fileContents))
		{
			$this->updateAlbumDetails($albumID, $fileContents);
			$this->updateRepo();
		}
		else
		{
			echo "Problem in writing data to a file";
		}

	}
	private function updateAlbumDetails($albumID, $fileContents){
		
		$dbh = $this->model->db->connect(DB_NAME);
		$this->model->db->updateAlbumDescription($albumID, $fileContents, $dbh);
		$this->model->updateDetailsForEachPhoto($albumID, $fileContents, $dbh);
	}

	private function updateRepo(){

		$statusMsg = array();

		$repo = Git::open(PHY_BASE_URL . '.git');

		// Before all operations, a git pull is done to sync local and remote repos.
		$repo->run('pull ' . GIT_REMOTE . ' master');
		array_push($statusMsg, 'Repo synced with remote');

		$files = $this->model->getChangesFromGit($repo);
		array_push($statusMsg, 'Files to be updated listed');

		$user['email'] = $_SESSION['email'];
		$user['password'] = $_SESSION['password'];
		$split = explode('@', $_SESSION['email']);
		$user['name'] = $split[0];

		if($files['A']){ 
				$this->model->gitProcess($repo, $files['A'], 'add', GIT_ADD_MSG, $user);
				array_push($statusMsg, ' Addition of JSON for Albums and Photos are completed');
		}	
		if($files['M']){ 
				$this->model->gitProcess($repo, $files['M'], 'add', GIT_MOD_MSG, $user);
				array_push($statusMsg, ' Modification of JSON for Albums and Photos are completed');
		}		
		if($files['D']){ 
				$this->model->gitProcess($repo, $files['D'], 'rm', GIT_DEL_MSG, $user);
				array_push($statusMsg, ' Deleted of JSON for Albums / Photos are completed');
		}	
		
		$repo->run('push ' . GIT_REMOTE . ' master');
		
		array_push($statusMsg, 'Local changes pushed to remote');

		$this->view('data/taskCompleted', $statusMsg, '');
	}
	
}

?>
