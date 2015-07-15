<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Users extends CI_Controller {

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

	public function ajax_login() {
		if( !empty( $_POST ) ) {
			header( 'Content-Type: application/json;charset=UTF-8' );
			$response = array();
			$response['success'] = false;
			$response['message'] = "Nothing done";
			//
			$this->load->model( 'User', 'users_db' );
			$user = $this->input->post( 'user' );
			$pass = $this->input->post( 'pass' );
			$salt = $this->input->post( 'login_salt' );
			if ( $salt != $this->session->userdata( 'login_salt' ) ) {
				$login = false;
			} else {
				$login = $this->users_db->loginUser( $user, $pass );
			}
			if( empty( $login ) ) {
				$response['message'] = "Wrong credentials";
			} else {
				$this->session->set_userdata( 'user_id', $login['id'] );
				$this->session->set_userdata( 'user_name', $login['username'] );
				//
				$response['success'] = true;
				$response['message'] = "OK";
				$response['data'] = array( 'username' => $login['username'] );
				$this->session->unset_userdata( 'login_salt' );
			}
			echo json_encode( $response );
		} else {
			$salt = crypt( "-".mt_rand()."-", base64_encode( microtime() ) );
			$this->session->set_userdata( 'login_salt', $salt );
			//
			$data = array( 'salt' => $salt );
			//
			$this->load->view( 'user/_login_form-material',$data );
		}
	} // - - end of login - - - - -

	public function logout() {
		$this->user = array();
		$this->session->unset_userdata( 'user_id', $login['id'] );
		$this->session->unset_userdata( 'user_name', $login['username'] );
		$this->session->sess_destroy();
		redirect( "/welcome" );
	} // - - end of logout - - - - -

	public function like_place() {
		header( 'Content-Type: application/json;charset=UTF-8' );
		$response = array();
		$response['liked'] = false;
		$response['message'] = "Unauthorized";
		if( empty( $this->user ) ) {
			die( json_encode( $response ) );
		}
		$response['message'] = "OK";
		$place_id = $this->uri->segment( 3 );
		$this->load->model( 'User', 'users_db' );
		$response['liked'] = $this->users_db->likePlace( $this->user['id'], $place_id );
		if( !$response['liked']['success'] ) {
			$response['message'] = "Error saving data.";
		}
		echo json_encode( $response );
	} // - - end of likeplace - - - - -

	public function dislike_place() {
		header( 'Content-Type: application/json;charset=UTF-8' );
		$response = array();
		$response['disliked'] = false;
		$response['message'] = "Unauthorized";
		if( empty( $this->user ) ) {
			die( json_encode( $response ) );
		}
		$response['message'] = "OK";
		$place_id = $this->uri->segment( 3 );
		$this->load->model( 'User', 'users_db' );
		$response['disliked'] = $this->users_db->dislikePlace( $this->user['id'], $place_id );
		if( !$response['disliked']['success'] ) {
			$response['message'] = "Error saving data.";
		}
		echo json_encode( $response );
	} // - - end of likeplace - - - - -
}

/* End of file users.php */
/* Location: ./application/controllers/users.php */
