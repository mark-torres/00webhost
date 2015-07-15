<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Product extends CI_Model {

	private $table_name;

	function __construct() {
		parent::__construct();
		$this->table_name = 'products';
	}

	public function getAll() {
		$products = array();
		$this->db->order_by( 'name' );
		$query = $this->db->get( $this->table_name );
		if ( $query->num_rows() > 0 ) {
			$products = $query->result_array();
		}
		return $products;
	} // - - end of findAll - - - - -

	public function getByPlace($place_id) {
		$products = array();
		$this->db->order_by( 'name' );
		$this->db->where( 'place_id', $place_id );
		$query = $this->db->get( $this->table_name );
		if ( $query->num_rows() > 0 ) {
			$products = $query->result_array();
		}
		return $products;
	} // - - end of findByName - - - - -

	public function save( $data ) {
		$saved = false;
		if( empty( $data['id'] ) ) {
			// insert new record
			$inserted = $this->db->insert( $this->table_name, $data );
			$saved = $this->db->insert_id();
		}
		return $saved;
	} // - - end of save - - - - -
	
	public function delete($product_id, $place_id = 0) {
		$conditions = array('id' => $product_id);
		if(!empty($place_id))
			$conditions['place_id'] = $place_id;
		$this->db->where($conditions)->limit(1);
		return $this->db->delete($this->table_name);
	}
}
