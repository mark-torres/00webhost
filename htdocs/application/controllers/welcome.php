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
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
