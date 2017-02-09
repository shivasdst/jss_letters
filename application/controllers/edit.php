<?php


class edit extends Controller {

	public function __construct() {
		
		parent::__construct();
	}

	public function index() {

	}

	public function photo($albumID, $photoID) {

		if(isset($_SESSION['login'])){
	
			$data = $this->model->editPhoto($albumID, $photoID);
			($data) ? $this->view('edit/photo', $data) : $this->view('error/index');
		}
		else
		{
			$this->redirect('user/login');
		}
	}	
	
	public function letter($albumID, $letterID) {

		if(isset($_SESSION['login'])){
	
			$data = $this->model->editLetter($albumID, $letterID);
			//~ var_dump($data);
			($data) ? $this->view('edit/letter', $data) : $this->view('error/index');
		}
		else
		{
			$this->redirect('user/login');
		}
	}	

	public function album($albumID) {

		if(isset($_SESSION['login'])){
			$data = $this->model->editAlbum($albumID);
			($data) ? $this->view('edit/album', $data) : $this->view('error/index');
		}
		else
		{
			$this->redirect('user/login');
		}
	}

}

?>
