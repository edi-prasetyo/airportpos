<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Direction extends CI_Controller
{
    //load data
    public function __construct()
    {
        parent::__construct();
        $this->load->model('direction_model');
    }
    //Index Category
    public function index()
    {
        $direction = $this->direction_model->get_direction();
        //Validasi
        $this->form_validation->set_rules(
            'address',
            'Alamat',
            'required|is_unique[directions.address]',
            array(
                'required'                        => '%s Harus Diisi',
                'is_unque'                        => '%s <strong>' . $this->input->post('address') .
                    '</strong>Nama Alamat Sudah Ada. Buat Nama yang lain!'
            )
        );
        if ($this->form_validation->run() === FALSE) {
            $data = [
                'title'                           => 'Alamat',
                'direction'                        => $direction,
                'content'                         => 'admin/direction/index_direction'
            ];
            $this->load->view('admin/layout/wrapp', $data, FALSE);
        } else {

            $data  = [
                'address'                   => $this->input->post('address'),
                'distance'                   => $this->input->post('distance'),
                'date_created'                    => date('Y-m-d H:i:s')
            ];
            $this->direction_model->create($data);
            $this->session->set_flashdata('message', 'Data telah ditambahkan');
            redirect(base_url('admin/direction'), 'refresh');
        }
    }
    //Update
    public function update($id)
    {
        $direction = $this->direction_model->detail_direction($id);
        //Validasi
        $this->form_validation->set_rules(
            'address',
            'Nama Alamat',
            'required',
            array('required'                  => '%s Harus Diisi')
        );
        if ($this->form_validation->run() === FALSE) {
            //End Validasi
            $data = [
                'title'                         => 'Edit Direction',
                'direction'                      => $direction,
                'content'                       => 'admin/direction/update_direction'
            ];
            $this->load->view('admin/layout/wrapp', $data, FALSE);
            //Masuk Database
        } else {
            $data  = [
                'id'                            => $id,
                'address'                 => $this->input->post('address'),
                'distance'                 => $this->input->post('distance'),
                'date_updated'                  => date('Y-m-d H:i:s')
            ];
            $this->direction_model->update($data);
            $this->session->set_flashdata('message', 'Data telah di Update');
            redirect(base_url('admin/direction'), 'refresh');
        }
        //End Masuk Database
    }
    //delete Category
    public function delete($id)
    {
        //Proteksi delete
        is_login();
        $direction = $this->direction_model->detail_direction($id);
        $data = ['id'   => $direction->id];
        $this->direction_model->delete($data);
        $this->session->set_flashdata('message', 'Data telah di Hapus');
        redirect(base_url('admin/direction'), 'refresh');
    }
}
