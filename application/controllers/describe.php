<?php

class describe extends Controller {

	public function __construct() {
		
		parent::__construct();
	}

	public function index() {

		$this->photo();
	}

	public function letter($albumID = DEFAULT_ALBUM, $id = '') {

		$data = $this->model->getLetterDetails($albumID, $id);
		$data->neighbours = $this->model->getNeighbourhood($albumID, $id);
		
		($data) ? $this->view('describe/letter', $data) : $this->view('error/index');
	}
	
	public function collection($collection = DEFAULT_COLLECTION) {

		$data = $this->model->getGetData();
		
		unset($data['url']);
		
		if(!(isset($data["page"])))
			$data["page"] = 1;
			
		$result = $this->model->getAlbums($collection,$data);

		if($data["page"] == 1)
			($result) ? $this->view('describe/collection', $result) : $this->view('error/index');
		else
			echo json_encode($result, JSON_UNESCAPED_UNICODE, JSON_UNESCAPED_SLASHES);
	}
}


?>
