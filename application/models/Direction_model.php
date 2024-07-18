<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Direction_model extends CI_Model
{
    //load database
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function get_direction()
    {
        $this->db->select('*');
        $this->db->from('directions');
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
    public function detail_direction($id)
    {
        $this->db->select('*');
        $this->db->from('directions');
        $this->db->where('id', $id);
        $this->db->order_by('id');
        $query = $this->db->get();
        return $query->row();
    }

    public function create($data)
    {
        $this->db->insert('directions', $data);
    }
    public function update($data)
    {
        $this->db->where('id', $data['id']);
        $this->db->update('directions', $data);
    }
    //Delete Data
    public function delete($data)
    {
        $this->db->where('id', $data['id']);
        $this->db->delete('directions', $data);
    }

    function search_address($address)
    {
        $this->db->like('address', $address, 'both');
        $this->db->order_by('address', 'ASC');
        $this->db->limit(40);
        return $this->db->get('directions')->result();
    }
}
