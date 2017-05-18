<?php

class Model {

	public function __construct() {

		$this->db = new Database();
	}

	public function getPostData() {

		if (isset($_POST['submit'])) {

			unset($_POST['submit']);	
		}

		if(!array_filter($_POST)) {
		
			return false;
		}
		else {

			return array_filter(filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS));
		}
	}

	public function getGETData() {

		if(!array_filter($_GET)) {
		
			return false;
		}
		else {

			return filter_input_array(INPUT_GET, FILTER_SANITIZE_SPECIAL_CHARS);
		}
	}

	public function preProcessPOST ($data) {

		return array_map("trim", $data);
	}

	public function encrypt ($data) {

		return sha1(SALT.$data);
	}
	
	public function sendLetterToPostman ($fromName = SERVICE_NAME, $fromEmail = SERVICE_EMAIL, 
		$toName = SERVICE_NAME, $toEmail = SERVICE_EMAIL, $subject = 'Bounce', 
		$message = '', $successMessage = 'Bounce', $errorMessage = 'Error') {

	    $mail = new PHPMailer();
        $mail->isSendmail();
        $mail->isHTML(true);
        $mail->setFrom($fromEmail, $fromName);
        $mail->addReplyTo($fromEmail, $fromName);
        $mail->addAddress($toEmail, $toName);
        $mail->Subject = $subject;
        $mail->Body = $message;
        
        return $mail->send();
 	}

 	public function bindVariablesToString ($str = '', $data = array()) {

 		unset($data['count(*)']);
	    
	    while (list($key, $val) = each($data)) {
	    
	        $str = preg_replace('/:'.$key.'/', $val, $str);
		}
	    return $str;
 	}

 	public function listFiles ($path = '') {

 		if (!(is_dir($path))) return array();

 		$files = scandir($path);
 
 		unset($files[array_search('.', $files)]);
 		unset($files[array_search('..', $files)]);
 
 		return $files;
 	}

	public function getAlbumDetails($albumID) {

		$dbh = $this->db->connect(DB_NAME);
		if(is_null($dbh))return null;
		
		$sth = $dbh->prepare('SELECT * FROM ' . METADATA_TABLE_L1 . ' WHERE albumID = :albumID');
		$sth->bindParam(':albumID', $albumID);

		$sth->execute();
		
		$result = $sth->fetch(PDO::FETCH_OBJ);
		$dbh = null;
		return $result;
	}

	public function getLetterDetails($albumID, $id) {

		$dbh = $this->db->connect(DB_NAME);
		if(is_null($dbh))return null;
		
		$sth = $dbh->prepare('SELECT * FROM ' . METADATA_TABLE_L2 . ' WHERE albumID = :albumID AND id = :id');
		$sth->bindParam(':albumID', $albumID);
		$sth->bindParam(':id', $id);
		$sth->execute();
		
		$result = $sth->fetch(PDO::FETCH_OBJ);
		$dbh = null;

		return $result;
	}

	public function getNeighbourhood($albumID, $id) {

		$albumPath = PHY_LETTER_URL . $albumID;

		$actualID = $this->getActualID($id);

		$letterPath = $albumPath . "/" . $actualID . '.json';

		$files = glob($albumPath . "/*" . '.json');
		$match = array_search($letterPath, $files);

		if(!($match === False)){
			
			$data['prev'] = (isset($files[$match-1])) ? preg_replace("/.*\/(.*)\.json/", "$1", $files[$match-1]) : '';
			$data['next'] = (isset($files[$match+1])) ? preg_replace("/.*\/(.*)\.json/", "$1", $files[$match+1]) : '';

			return $data;
		}	
		else{

			return False;
		}

	}

    public function getActualID($combinedID) {

        return preg_replace('/^(.*)__/', '', $combinedID);
    }

    public function getRandomImageInAlbum($id){
		
		$lettersFolder = glob(PHY_LETTER_JPG_URL . $id . '/*', GLOB_ONLYDIR);
		$randNum = rand(0, count($lettersFolder)-1);
		$selectedFolder = $lettersFolder[$randNum];
		$letter = glob($selectedFolder . '/thumbs/*.JPG');
        $randNum = rand(0, sizeof($letter) - 1);
        $letterSelected = $letter[$randNum];
        return str_replace(PHY_LETTER_JPG_URL, LETTER_JPG_URL, $letterSelected);   	
    }
    
    public function getFirstImageInLetter($albumID, $letterID){
		
		$letters = glob(PHY_LETTER_JPG_URL . $albumID . '/' . $letterID . '/thumbs/*.JPG');
		$randNum = rand(0, sizeof($letters) - 1);
		$fileSelected = $letters[$randNum];
		return str_replace(PHY_LETTER_JPG_URL, LETTER_JPG_URL, $fileSelected);   	
    }

    public function getLetterCount($id = '') {

        $count = sizeof(glob(PHY_LETTER_URL . $id . '/*.json'));
        return ($count > 1) ? $count . ' ಪತ್ರಗಳು' : $count . ' ಪತ್ರ';
    }

    public function getDetailByField($json = '', $firstField = '', $secondField = '') {

        $data = json_decode($json, true);
        if (isset($data[$firstField])) {
      
            return $data[$firstField];
        }
        elseif (isset($data[$secondField])) {
      
            return $data[$secondField];
        }

        return '';
    }
    
    public function getDetailByFieldUsingAlbumID($albumID,$field){
		
    	$result = $this->getAlbumDetails($albumID);
    	$fieldDetails = $this->getDetailByField($result->description,$field);
    	return $fieldDetails;
    }
}

?>
