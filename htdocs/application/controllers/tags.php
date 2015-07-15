<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Tags extends CI_Controller {

	// Array containing user data
	private $user;

	public function __construct() {
		parent::__construct();
		// Check session
		$this->user = false;
		$user = $this->session->userdata( 'user_id' );
		if( !empty( $user ) ) {
			$this->user = array(
			                  'id'   => $this->session->userdata( 'user_id' ),
			                  'name' => $this->session->userdata( 'user_name' ),
			              );
		}
	}

	private function clean_name( $dirty_name ) {
		$clean_name = "";
		// load text helper
		$this->load->helper( 'text' );
		$clean_name = convert_accented_characters( $dirty_name );
		$clean_name = preg_replace( "/[^a-z\d\s]+/i", "", $clean_name );
		return $clean_name;
	} // - - end of sanitizeName - - - - -

	public function save() {
		$response = array();
		$response['success'] = false;
		$response['message'] = "Nothing done";
		// check user in session
		if( empty( $this->user ) ) {
			$response['message'] = "Unauthorized";
		} else {
			// Lazy add: /tags/save/Hardware%20Store/X
			$tag_data = $this->input->post( 'tag' );
			if( empty( $tag_data ) ) {
				$tag_data = array();
				$display_name = $this->uri->segment( 3 );
				$place_cat_id = $this->uri->segment( 4 );
				if ( empty( $place_cat_id ) ) {
					$place_cat_id = 1;
				}
				$display_name = urldecode( $display_name );
				$tag_data['display_name'] = $display_name;
				$tag_data['place_category_id'] = $place_cat_id;
			}
			if( empty( $tag_data['display_name'] ) ) {
				$response['message'] = "Tag name not found";
			} else {
				$this->load->model( 'Tag', 'tags_db' );
				$tag_name = strtolower( $this->clean_name( $tag_data['display_name'] ) );
				$tag_name = trim( $tag_name );
				$tag_name = preg_replace( "/\s+/",'_',$tag_name );
				$tag_data['name'] = $tag_name;
				$response['data'] = $tag_data;
				$existing = $this->tags_db->findByName( $tag_name );
				if( !empty( $existing ) ) {
					$response['message'] = "Tag already exists";
				} else {
					$saved = $this->tags_db->save( $tag_data );
					if( !empty( $saved ) ) {
						$response['success'] = true;
						$response['message'] = "Tag saved";
					} else {
						$response['message'] = "Error saving tag";
					}
				}
			}
		}
		echo json_encode( $response );
	} // - - end of save - - - - -
}

/* End of file tags.php */
/* Location: ./application/controllers/tags.php */
