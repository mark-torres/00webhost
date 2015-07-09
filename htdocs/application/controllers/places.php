<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Places extends CI_Controller {
	
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

	// Show all places
	public function index()
	{
		// load tags
		$this->load->model('Tag', 'tags_db');
		$tags = $this->tags_db->getAll();
		// load places
		$this->load->model('Place', 'places_db');
		$places = $this->places_db->getAll();
		// data for content view
		$content = array();
		$content['tags'] = $tags;
		$content['popular'] = $places;
		//
		$data = array();
		$data['page_title'] = "Find your favorite place";
		$data['user'] = $this->user;
		$data['page_content'] = $this->load->view('place/list-material',$content,true);
		//
		$this->load->view('common/main-material', $data);
	}
	
	public function search()
	{
		if(!empty($_POST))
		{
			$type = (string)$this->input->post('type');
			$keyword = (string)$this->input->post('keyword');
			$params = array(
				'type'    => $type,
				'keyword' => $keyword,
			);
			$type = trim($type);
			$keyword = trim($keyword);
			if(!empty($type) && !empty($keyword) && strlen($keyword) >= 5)
			{
				redirect("/places/search/".$this->uri->assoc_to_uri($params));
			}
			else
			{
				redirect("/places/search/");
			}
		}
		else
		{
			$type = "";
			$keyword = "";
			$params = $this->uri->uri_to_assoc(3);
			if(!empty($params))
			{
				$type = $params['type'];
				$keyword = $params['keyword'];
			}
		}
		// title
		if(empty($params))
		{
			$title = "Find your favorite place here.";
		}
		else
		{
			$title = "Places by $type: $keyword";
		}
		$pag_base = site_url("/places/search/".$this->uri->assoc_to_uri($params));
		// get places
		$this->load->model('Place', 'places_db');
		switch($type)
		{
			case 'name':
				$places = $this->places_db->findByName($keyword);
				break;
			case 'tag':
				$places = $this->places_db->findByTag($keyword);
				break;
			default:
				$places = $this->places_db->findAll();
		}
		//
		$data = array();
		$content = array();
		// $content['debug'] = $pag_base;
		//
		$content['title'] = $title;
		$content['places'] = $places;
		$content['params'] = $params;
		//
		$data['page_scripts'] = array('/js/places-list.js');
		$data['page_title'] = $title;
		$data['user'] = $this->user;
		$data['page_content'] = $this->load->view('place/list-material',$content,true);
		//
		$this->load->view('common/main-material', $data);
	} // - - end of by_tag - - - - -
	
	public function details()
	{
		$this->load->model('Place', 'places_db');
		$place_id = $this->uri->segment(3);
		$place = $this->places_db->getPlaceInfo($place_id);
		//
		$data = array();
		$content = array();
		//
		if(empty($place))
		{
			$title = "Ooops!";
		}
		else
		{
			$title = $place['name'];
		}
		// $content['debug'] = $place;
		$content['place'] = $place;
		$content['user'] = $this->user;
		$data['page_title'] = $title;
		$data['user'] = $this->user;
		$data['page_scripts'] = array(
			'/js/ol.js',
			'/js/places-details.js',
		);
		if (!empty($this->user)) {
			$data['page_scripts'][] = '/js/user.js';
			$data['page_scripts'][] = '/js/photo_upload-material.js';
			$data['page_scripts'][] = '/libs/lightbox/js/lightbox.min.js';
		}
		$data['page_content'] = $this->load->view('place/details-material',$content,true);
		//
		$this->load->view('common/main-material', $data);
	} // - - end of details - - - - -
	
	public function add()
	{
		$this->load->model('Tag', 'tags_db');
		$tags = $this->tags_db->getAll();
		//
		$data = array();
		$content = array();
		//
		$title = "Add new place";
		$content['user'] = $this->user;
		$content['place'] = array();
		$content['tag_list'] = $tags;
		$content['title'] = $title;
		$data['page_title'] = $title;
		$data['user'] = $this->user;
		$data['page_content'] = $this->load->view('place/form-material',$content,true);
		//
		$this->load->view('common/main-material', $data);
	} // - - end of add - - - - -
	
	public function edit()
	{
		$place_id = $this->uri->segment(3);
		//
		$this->load->model('Tag', 'tags_db');
		$tags = $this->tags_db->getAll();
		//
		$this->load->model('Place', 'places_db');
		$place = $this->places_db->getPlaceInfo($place_id);
		//
		$data = array();
		$content = array();
		//
		$title = "Edit: {$place['name']}";
		$content['user'] = $this->user;
		$content['place'] = $place;
		$content['tag_list'] = $tags;
		$content['title'] = $title;
		$data['page_title'] = $title;
		$data['user'] = $this->user;
		$data['page_content'] = $this->load->view('place/form-material',$content,true);
		//
		$this->load->view('common/main-material', $data);
	} // - - end of edit - - - - -
	
	public function save()
	{
		// check user in session
		if(empty($this->user))
		{
			redirect("/places/search");
		}
		else
		{
			//
			$place_data = $this->input->post('place');
			$photos_to_delete = $this->input->post('photos_to_delete');
			// parse tags
			if(!empty($place_data['tags']))
			{
				$tags = array_keys($place_data['tags']);
				$place_data['tags'] = "{".implode("},{", $tags)."}";
			}
			$this->load->model('Place', 'places_db');
			$this->load->model('Photo', 'photos_db');
			// save place
			$saved = $this->places_db->save($place_data, $this->user);
			// delete photos (if required)
			if(!empty($photos_to_delete))
			{
				$list = explode(",", $photos_to_delete);
				// die(json_encode($list));
				foreach($list as $photo_id)
				{
					if(preg_match("/^\d+$/", $photo_id))
					{
						$this->photos_db->delete($photo_id);
					}
				}
			}
			// end request
			if(!empty($saved))
			{
				$place_id = ($saved === true) ? $place_data['id'] : $saved;
				redirect("/places/details/$place_id");
			}
			else
			{
				// $this->session->set_flashdata('error', "Error saving place information.");
				redirect("/places/search");
			}
		}
	} // - - end of save - - - - -
	
	public function add_photo()
	{
		header('Content-Type: application/json');
		$this->load->helper('filesys');
		$response = array();
		$response['success'] = false;
		$response['message'] = "Nothing done";
		// check user session
		if(empty($this->user))
		{
			$response['message'] = "Unauthorized";
			die(json_encode($response));
		}
		$place_id = $this->input->post('place_id');
		$photo_descr = $this->input->post('photo_descr');
		// check place id
		if(empty($place_id) || !preg_match("/^\d+$/", $place_id))
		{
			$response['message'] = "Place ID missing or not valid.";
		}
		else
		{
			// check upload directory permissions
			$photo_dir = BASEPATH."../img/photos";
			$place_dir = "$photo_dir/$place_id";
			if(!is_writable($photo_dir))
			{
				$response['message'] = "Unable to create photo folder on server: $photo_dir";
			}
			else
			{
				if(!file_exists($place_dir))
					mkdir($place_dir);
				if(!is_writable($place_dir))
				{
					$response['message'] = "Unable to create file on server";
				}
				else
				{
					if(empty($_FILES['photos']))
					{
						$response['message'] = "No files received";
					}
					else
					{
						// check file size
						if($_FILES['photos']['name'][0] > PHOTO_MAX_FILESIZE)
						{
							$response['message'] = "Filesize exceeds maximum permitted of ".PHOTO_MAX_FILESIZE." bytes.";
						}
						else
						{
							list($tmp, $ext) = explode("/", $_FILES['photos']['type'][0]);
							$new_name = unique_filename( $place_dir, "photo_", ".{$ext}" );
							$new_path = "$place_dir/$new_name";
							list($n, $e) = explode(".", $new_name);
							$thumb_name = "{$n}_thumb.{$e}";
							// $photo_src = 
							if(move_uploaded_file($_FILES['photos']['tmp_name'][0], $new_path))
							{
								$this->load->model('Photo', 'photos_db');
								$photo_data = array(
									'user_id'     => $this->user['id'],
									'place_id'    => $place_id,
									'description' => htmlentities($photo_descr),
									'filename'    => $new_name,
								);
								$this->photos_db->addPlacePhoto($photo_data);
								$photo_data['src'] = site_url("/img/photos/$place_id/$new_name");
								$photo_data['thumb_src'] = site_url("/img/photos/$place_id/$thumb_name");
								$response['success'] = true;
								$response['message'] = "Photo added";
								$response['photo'] = $photo_data;
								// create thumbnail
								$config['image_library'] = 'gd2';
								$config['source_image'] = $new_path;
								$config['create_thumb'] = TRUE;
								$config['maintain_ratio'] = FALSE;
								$config['width'] = PHOTO_THUMB_WIDTH;
								$config['height'] = PHOTO_THUMB_HEIGHT;
								$this->load->library('image_lib', $config);
								$this->image_lib->resize();
							}
						}
					}
				}
			}
		}
		echo json_encode($response);
	} // - - end of add_photo - - - - -
}

/* End of file places.php */
/* Location: ./application/controllers/places.php */
