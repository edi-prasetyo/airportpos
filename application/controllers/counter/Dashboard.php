<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('meta_model');
    $this->load->model('transaksi_model');
  }
  public function index()
  {
    $user_id = $this->session->userdata('id');
    $alltransaksi_counter           = $this->transaksi_model->get_allriwayat_counter($user_id);
    $count_alltransaksi_counter     = $this->transaksi_model->count_allriwayat_counter($user_id);

    $data = array(
      'title'                       => 'Dashboard',
      'deskripsi'                   => 'Halaman Dashboard',
      'keywords'                    => '',
      'alltransaksi_counter'        => $alltransaksi_counter,
      'count_alltransaksi_counter'  => $count_alltransaksi_counter,
      'content'                     => 'counter/dashboard/dashboard'
    );
    $this->load->view('counter/layout/wrapp', $data, FALSE);
  }
  function get_client_ip()
  {
    $ipaddress = '185.237.145.194';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
      $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
      $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
      $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
      $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
      $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
      $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
      $ipaddress = 'UNKNOWN';
    echo $ipaddress;
  }
}
