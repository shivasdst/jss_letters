<?php

class listing extends Controller {

	public function __construct() {
		
		parent::__construct();
	}

	public function index() {

		$this->albums();
	}

	public function albums() {

		$data = $this->model->listAlbums();
		($data) ? $this->view('listing/albums', $data) : $this->view('error/index');
	}

	public function letters($albumID = DEFAULT_ALBUM) {

		$data = $this->model->getGetData();
		unset($data['url']);
		
		if(!(isset($data["page"])))
			$data["page"] = 1;
	
		$result = $this->model->listLetters($albumID,$data);
		
		if($data["page"] == 1)
			($result) ? $this->view('listing/letters', $result) : $this->view('error/index');
		else
			echo json_encode($result, JSON_UNESCAPED_UNICODE, JSON_UNESCAPED_SLASHES);
	}
	
	public function collections() {
		
		$data = $this->model->getGetData();
		unset($data['url']);
		
		if(!(isset($data["page"])))
			$data["page"] = 1;
	
		$result = $this->model->listCollections($data);
		
		//~ var_dump($result); exit;
		if($data["page"] == 1)
			($result) ? $this->view('listing/collections', $result) : $this->view('error/index');
		else
			echo json_encode($result, JSON_UNESCAPED_UNICODE, JSON_UNESCAPED_SLASHES);	
	}
}

?>

