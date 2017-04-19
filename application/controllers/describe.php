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

		$data = $this->model->getAlbums($collection);
		($data) ? $this->view('describe/collection', $data) : $this->view('error/index');		
	}
}

?>
