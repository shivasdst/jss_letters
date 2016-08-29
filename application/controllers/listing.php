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

	public function photos($album = DEFAULT_ALBUM) {

		$data = $this->model->listPhotos($album);
		($data) ? $this->view('listing/photos', $data) : $this->view('error/index');
	}
}

?>