<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {
	
	private $user;

	public function __construct()
	{
		parent::__construct();
		// Check session
		$this->user = false;
		$user = $this->session->userdata('user_id');
		if(!empty($user))
		{
			$this->user = array(
				'id'   => $this->session->userdata('user_id'),
				'name' => $this->session->userdata('user_name'),
			);
		}
	}

	public function index()
	{
		// load tags
		$this->load->model('Tag', 'tags_db');
		$tags = $this->tags_db->getAll();
		// load places
		$this->load->model('Place', 'places_db');
		$places = $this->places_db->getPopular(5);
		// data for content view
		$content = array();
		$content['tags'] = $tags;
		$content['popular'] = $places;
		//
		$data = array();
		$data['page_title'] = "Welcome!";
		$data['user'] = $this->user;
		$data['page_content'] = $this->load->view('welcome-material',$content,true);
		//
		$this->load->view('common/main-material', $data);
	}
	
	public function env_info() {
		if(!empty($this->user)) {
			phpinfo();
			die();
		} else {
			echo "<pre>";
			$server = array();
			$server['HTTP_HOST'] = $_SERVER['HTTP_HOST'];
			$server['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
			$server['SERVER_NAME'] = $_SERVER['SERVER_NAME'];
			$server['SERVER_ADDR'] = $_SERVER['SERVER_ADDR'];
			$server['SERVER_PORT'] = $_SERVER['SERVER_PORT'];
			$server['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
			$server['SERVER_PROTOCOL'] = $_SERVER['SERVER_PROTOCOL'];
			$server['REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'];
			$server['QUERY_STRING'] = $_SERVER['QUERY_STRING'];
			$server['REQUEST_URI'] = $_SERVER['REQUEST_URI'];
			$server['SCRIPT_NAME'] = $_SERVER['SCRIPT_NAME'];
			$server['REQUEST_TIME'] = $_SERVER['REQUEST_TIME'];
			print_r($server);
			echo "</pre>";
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
