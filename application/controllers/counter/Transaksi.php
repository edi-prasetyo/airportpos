<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi extends CI_Controller
{
    //Load Model
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('string');
        $this->load->library('pagination');
        $this->load->model('meta_model');
        $this->load->model('main_model');
        $this->load->model('product_model');
        $this->load->model('category_model');
        $this->load->model('transaksi_model');
        $this->load->model('persentase_model');
        $this->load->model('tarif_model');
    }
    //Index
    public function index()
    {
        $user_id = $this->session->userdata('id');
        // var_dump($user_id);
        // die;
        $transaksi  = $this->transaksi_model->get_transaksi_counter($user_id);
        // End Listing Berita dengan paginasi
        $data = array(
            'title'         => 'Data Paket',
            'deskripsi'     => 'Halaman Paket',
            'keywords'      => '',
            'transaksi'     => $transaksi,
            'content'       => 'counter/transaksi/index'
        );
        $this->load->view('counter/layout/wrapp', $data, FALSE);
    }

    public function calculate()
    {

        $this->form_validation->set_rules(
            'address',
            'Nomor Resi',
            'required',
            [
                'required'      => 'Nomor Resi',
            ]
        );
        $this->form_validation->set_rules(
            'jarak',
            'Nomor Resi',
            'required',
            [
                'required'      => 'Nomor Resi',
            ]
        );
        if ($this->form_validation->run() == false) {
            $data = [
                'title'         => 'Buat Pesanan',
                'deskripsi'     => 'Cek Resi Pengiriman',
                'keywords'      => 'Resi',

                'content'       => 'counter/transaksi/calculate'
            ];
            $this->load->view('counter/layout/wrapp', $data, FALSE);
        } else {
            //Validasi Berhasil
            $this->create();
        }
    }

    //Create
    public function create()
    {

        $meta = $this->meta_model->get_meta();
        $api_url_login = $meta->api_login;

        $username = $meta->ap_username;
        $password = $meta->ap_password;


        header('Content-type: text/html; charset=utf-8');
        // URL to fetch
        $url = $api_url_login;
        $User_Agent = 'Mozilla/5.0 (Windows NT 6.1; rv:60.0) Gecko/20100101 Firefox/60.0';

        $request_headers[] = 'X-picturemaxx-api-key: key';
        $request_headers[] = 'Contect-Type:text/html';
        $request_headers[] = 'Accept:text/html';
        $request_headers[] = 'Accept: application/json';
        $request_headers[] = 'Content-type: application/json';
        $request_headers[] = 'Accept-Encoding:  gzip, deflate, identity';
        $request_headers[] = 'Expect: ';

        $dataj = array(
            'password' => $username,
            'username' => $password
        );
        $data_json = json_encode($dataj);
        $request_headers[] = 'Content-Length: ' . strlen($data_json);
        $ch = curl_init($url);
        // Set the url      

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, $User_Agent);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");

        // Execute
        $result = curl_exec($ch);
        curl_close($ch);
        $dataj = json_decode($result, true);

        $store = $dataj['user']['store'];
        // $store_id = $store[0]['store_id'];
        $token = $dataj['token'];

        // var_dump($dataj);
        // die;


        $sales = $this->user_model->get_allcounter();



        $id = $this->session->userdata('id');
        $user = $this->user_model->user_detail($id);

        $origin = $user->user_address;

        $product            = $this->product_model->car_product();
        $address            = $this->input->post('address');
        $jarak              = $this->input->post('jarak');





        $total_price        = $jarak * $product->price + $product->start_price;

        $this->form_validation->set_rules(
            'passenger_name',
            'Harga Paket',
            'required',
            array(
                'required'                        => '%s Harus Diisi'
            )
        );
        if ($this->form_validation->run() === FALSE) {
            $data = [
                'title'                           => 'Buat Transaksi',
                'product'                         => $product,
                'origin'                          => $origin,
                'address'                         => $address,
                'jarak'                           => $jarak,
                'total_price'                     => $total_price,
                'user'                            => $user,
                'store'                         => $store,
                'sales'                         => $sales,
                'content'                         => 'counter/transaksi/create'
            ];
            $this->load->view('counter/layout/wrapp', $data, FALSE);
        } else {

            $tanggal_jam = $this->input->post('tanggal_jam');
            $tanggal = date('Y-m-d', strtotime($tanggal_jam));


            $order_id = strtoupper(random_string('alnum', 7));
            $data  = [
                'user_id'                           => $this->session->userdata('id'),
                'product_id'                        => $this->input->post('product_id'),
                'order_id'                          => $order_id,
                'passenger_name'                    => $this->input->post('passenger_name'),
                'passenger_phone'                   => $this->input->post('passenger_phone'),
                'passenger_email'                   => $this->input->post('passenger_email'),
                'origin'                            => $origin,
                'destination'                       => $this->input->post('destination'),
                'jarak'                             => $this->input->post('jarak'),
                'trans_date'                             => $tanggal,
                'trans_time'                             => $tanggal_jam,
                'start_price'                       => $product->start_price,
                'total_price'                       => $total_price,
                'stage'                             => 1,
                'status'                            => 'Mencari Pengemudi',
                'sales_name'                        => $this->input->post('sales_name'),
                'item_price_per_unit'               => $this->input->post('item_price_per_unit'),
                'item_price_amount'                 => $this->input->post('item_price_amount'),
                'item_total_price_amount'           => $this->input->post('item_total_price_amount'),
                'item_vat'                          => $this->input->post('item_vat'),
                'item_total_vat'                    => $this->input->post('item_total_vat'),
                'transaction_amount'                => $this->input->post('transaction_amount'),
                'date_created'                      => date('Y-m-d H:i:s'),
                'date_updated'                      => date('Y-m-d H:i:s')
            ];
            // $this->transaksi_model->create($data);
            $insert_id = $this->transaksi_model->create($data);
            $this->send_data_ap2($insert_id, $store, $token);
            $this->select_driver($insert_id);
            $this->session->set_flashdata('message', 'Data  telah ditambahkan ');
            redirect(base_url('counter/transaksi/select_driver/' . $insert_id), 'refresh');
        }
    }

    public function send_data_ap2($insert_id, $store, $token)
    {
        $meta = $this->meta_model->get_meta();
        $api_url_transaction = $meta->api_transaction;

        $transaksi  = $this->transaksi_model->last_transaksi($insert_id);
        $invoice_no = rand(0000001, 9999999);
        $item_price_per_unit = $transaksi->item_price_per_unit;
        $item_price_amount = $transaksi->item_price_amount;
        $item_vat = $transaksi->item_vat;
        $item_total_price_amount = $transaksi->item_total_price_amount;
        $transaction_amount = $transaksi->transaction_amount;

        $store_id   = $store[0]['store_id'];


        $data = array(
            "store" => array(
                [
                    "store_id" => $store_id,
                    "transactions" => array(
                        [
                            "invoice_no" => $invoice_no,
                            "trans_date" => $transaksi->trans_date,
                            "trans_time" => $transaksi->trans_time,
                            "sequence_unique" => "1",
                            "item_name" => "Online",
                            "item_code" => "001",
                            "item_barcode" => "",
                            "item_cat_name" => "transportasi",
                            "item_cat_code" => "",
                            "item_qty" => "1",
                            "item_unit" => "mobil",
                            "item_price_per_unit" => $item_price_per_unit,
                            "item_discount" => "0",
                            "item_price_amount" => $item_price_amount,
                            "item_vat" => $item_vat,
                            "item_tax" => "0",
                            "item_total_discount" => "0",
                            "item_total_price_amount" => $item_total_price_amount,
                            "item_total_vat" => $item_vat,
                            "item_total_tax" => "0",
                            "item_total_service_charge" => "0",
                            "invoice_tax" => "0",
                            "transaction_amount" => $transaction_amount,
                            "currency" => "IDR",
                            "rate" => "1",
                            "payment_type" => "Cash",
                            "payment_by" => $transaksi->passenger_name,
                            "username" => $transaksi->sales_name,
                            "buyer_barcode" => "",
                            "buyer_name" =>  $transaksi->passenger_name,
                            "buyer_flight_no" => "",
                            "buyer_destination" => "",
                            "buyer_nationality" => "",
                            "remark" => "",
                            "tax_id" => "PPN",
                            "payment_name" => "Cash",
                            "payment_time" => date('Y-m-d H:i:s'),
                            "distance" => $transaksi->jarak,
                            "journey_time" => "0"
                        ]
                    ),
                ]

            )
        );

        $dataj = json_encode($data, true);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url_transaction);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataj);  //Post Fields
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $headers = [
            'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
            'Authorization:' . $token . ''
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


        $server_output = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $transaksi_ap2 = json_decode($server_output, true);
        // $store_id = $transaksi_ap2['success_data'][0]['store_id'];
        // print_r($store_id);


        $this->update_transaction($httpcode,  $transaksi, $transaksi_ap2);
    }

    public function update_transaction($httpcode,  $transaksi, $transaksi_ap2)
    {
        $store_id = $transaksi_ap2['success_data'][0]['store_id'];
        $invoice_no = $transaksi_ap2['success_data'][0]['invoice_no'];
        $trans_date = $transaksi_ap2['success_data'][0]['trans_date'];
        $trans_time = $transaksi_ap2['success_data'][0]['trans_time'];
        $sequence_unique = $transaksi_ap2['success_data'][0]['sequence_unique'];
        $item_name = $transaksi_ap2['success_data'][0]['item_name'];
        $item_code = $transaksi_ap2['success_data'][0]['item_code'];
        $qty = $transaksi_ap2['success_data'][0]['qty'];
        $item_price_per_unit = $transaksi_ap2['success_data'][0]['item_price_per_unit'];
        $item_price_amount = $transaksi_ap2['success_data'][0]['item_price_amount'];
        $item_vat = $transaksi_ap2['success_data'][0]['item_vat'];
        $item_total_price_amount = $transaksi_ap2['success_data'][0]['item_total_price_amount'];
        $item_total_vat = $transaksi_ap2['success_data'][0]['item_total_vat'];
        $transaction_amount = $transaksi_ap2['success_data'][0]['transaction_amount'];

        $data = [
            'id'                => $transaksi->id,
            'status_code'       => $httpcode,
            'store_id'          => $store_id,
            'invoice_no'          => $invoice_no,
            'trans_date'          => $trans_date,
            'trans_time'          => $trans_time,
            'sequence_unique'          => $sequence_unique,
            'item_name'          => $item_name,
            'item_code'          => $item_code,
            'item_qty'          => $qty,
            'item_price_per_unit'          => $item_price_per_unit,
            'item_price_amount'          => $item_price_amount,
            'item_vat'          => $item_vat,
            'item_total_price_amount'          => $item_total_price_amount,
            'item_total_vat'          => $item_total_vat,
            'transaction_amount'          => $transaction_amount,
        ];
        $this->transaksi_model->update($data);
    }


    //Create
    public function createjk()
    {
        $id = $this->session->userdata('id');
        $user = $this->user_model->user_detail($id);
        $origin = $user->user_address;

        $product            = $this->product_model->car_product();

        $this->form_validation->set_rules(
            'passenger_name',
            'Harga Paket',
            'required',
            array(
                'required'                        => '%s Harus Diisi'
            )
        );
        if ($this->form_validation->run() === FALSE) {
            $data = [
                'title'                           => 'Buat Transaksi',
                'product'                         => $product,
                'origin'                          => $origin,
                'user'                            => $user,
                'content'                         => 'counter/transaksi/createjk'
            ];
            $this->load->view('counter/layout/wrapp', $data, FALSE);
        } else {

            $order_id = strtoupper(random_string('alnum', 7));
            $data  = [
                'user_id'                           => $this->session->userdata('id'),
                'product_id'                        => $this->input->post('product_id'),
                'order_id'                          => $order_id,
                'passenger_name'                    => $this->input->post('passenger_name'),
                'passenger_phone'                   => $this->input->post('passenger_phone'),
                'passenger_email'                   => $this->input->post('passenger_email'),
                'origin'                            => $origin,
                'destination'                       => $this->input->post('destination'),
                'total_price'                       => $this->input->post('total_price'),
                'stage'                             => 1,
                'status'                            => 'Mencari Pengemudi',
                'date_created'                      => date('Y-m-d H:i:s'),
                'date_updated'                      => date('Y-m-d H:i:s')
            ];
            // $this->transaksi_model->create($data);
            $insert_id = $this->transaksi_model->create($data);

            $this->select_driver($insert_id);
            $this->session->set_flashdata('message', 'Data  telah ditambahkan ');
            // redirect(base_url('counter/transaksi/select_driver/' . $insert_id), 'refresh');
        }
    }





    public function select_driver($insert_id)
    {
        $driver                     = $this->user_model->get_driver_unlock();
        $last_transaksi             = $this->transaksi_model->last_transaksi($insert_id);

        $user_id = $this->input->post('driver_id');
        $user_driver = $this->user_model->user_detail($user_id);



        $this->form_validation->set_rules(
            'driver_id',
            'Harga Paket',
            'required',
            array(
                'required'                        => '%s Harus Diisi'
            )
        );
        if ($this->form_validation->run() === FALSE) {
            $data = [
                'title'             => 'Pilih Driver',
                'driver'            => $driver,
                'last_transaksi'    => $last_transaksi,
                'insert_id'         => $insert_id,
                'content'           => 'counter/transaksi/selectdriver'
            ];
            $this->load->view('counter/layout/wrapp', $data, FALSE);
        } else {

            $data = [
                'id'                => $insert_id,
                'driver_id'          => $user_id,
                'stage'             => 2,
            ];
            $this->transaksi_model->update($data);
            $this->_sendWhatsapp($user_id, $last_transaksi);
            $this->_sendWhatsappCustomer($last_transaksi);
            //Update Status Driver
            $this->update_status_driver($user_id);

            $this->session->set_flashdata('message', '<div class="alert alert-success">Data  telah ditambahkan</div> ');
            redirect(base_url('counter/transaksi/success'), 'refresh');
        }
    }



    public function test()
    {


        $test = 1;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-ecsysdev.angkasapura2.co.id/api/v1/transaction/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
            "store": [
                {
                    "store_id": "878fa17d843369e16d26f41151a9f158",
                    "transactions": [
                        {
                            "invoice_no": ' . $test . ',
                            "trans_date": "2022-08-09",
                            "trans_time": "2022-08-09 15:18:53",
                            "sequence_unique": "1",
                            "item_name": "Dropoff",
                            "item_code": "139",
                            "item_barcode": "",
                            "item_cat_name": "transportasi",
                            "item_cat_code": "",
                            "item_qty": "1",
                            "item_unit": "mobil",
                            "item_price_per_unit": "180000",
                            "item_discount": "0",
                            "item_price_amount": "180000",
                            "item_vat": "18000",
                            "item_tax": "0",
                            "item_total_discount": "0",
                            "item_total_price_amount": "180000",
                            "item_total_vat": "0",
                            "item_total_tax": "0",
                            "item_total_service_charge": "0",
                            "invoice_tax": "0",
                            "transaction_amount": "198000",
                            "currency": "IDR",
                            "rate": "1",
                            "payment_type": "Cash",
                            "payment_by": "Hanafi",
                            "username": "sales 1",
                            "buyer_barcode": "",
                            "buyer_name": "Pelanggan",
                            "buyer_flight_no": "",
                            "buyer_destination": "",
                            "buyer_nationality": "",
                            "remark": "",
                            "tax_id": "PPN",
                            "payment_name": "Cash",
                            "payment_time": "2022-08-09 15:18:55",
                            "distance": "30",
                            "journey_time": "0"
                        }
                    ]
                }
            ]
        }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjEwMDAzIiwiZnVsbG5hbWUiOiJBUEkgTWFuZGFyIEdhbWEgSW5kb25lc2lhIiwidXNlcm5hbWUiOiJhcGkubWFuZGFyZ2FtYSIsInRlbmFudF9pZCI6IiIsImxhc3RfbG9naW4iOiIyMDIyLTA4LTEwIDE0OjQxOjAwIiwiY3JlYXRlZF9hdCI6IjIwMjItMDgtMDkgMTM6NTk6MjkiLCJ1cGRhdGVkX2F0IjpudWxsLCJ0aW1lIjoxNjYwMTE3MjYwfQ.kBlSLdFQ1ispA_oL7-KC4VMbgS25UTXYSkTxKWp8D9Y',
                'Content-Type: application/json',
                'Cookie: cookiesession1=678B2870635E9D9B36FF16349EAF8D99'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;



        $array = array(
            "store" => array(
                [
                    "store_id" => "123",
                    "transactions" => array(
                        [
                            'invoice_no' => '3435435345',
                            "trans_date" => "2022-08-09",
                        ]
                    ),
                ]

            )
        );

        // array_values() removes the original keys and replaces
        // with plain consecutive numbers
        // $out = array_values($array);
        $data = json_encode($array);

        echo $data;


        // $data = [
        //     'store' => [
        //         'store_id' => '878fa17d843369e16d26f41151a9f158',
        //         'transaction' => [
        //             "invoice_no" => "2000349",
        //             "trans_date" => "2022-08-09",
        //             "trans_time" => "2022-08-09 15:18:53",
        //             "sequence_unique" => "1",
        //             "item_name" => "Dropoff",
        //             "item_code" => "139",
        //             "item_barcode" => "",
        //             "item_cat_name" => "transportasi",
        //             "item_cat_code" => "",
        //             "item_qty" => "1",
        //             "item_unit" => "mobil",
        //             "item_price_per_unit" => "180000",
        //             "item_discount" => "0",
        //             "item_price_amount" => "180000",
        //             "item_vat" => "18000",
        //             "item_tax" => "0",
        //             "item_total_discount" => "0",
        //             "item_total_price_amount" => "180000",
        //             "item_total_vat" => "0",
        //             "item_total_tax" => "0",
        //             "item_total_service_charge" => "0",
        //             "invoice_tax" => "0",
        //             "transaction_amount" => "198000",
        //             "currency" => "IDR",
        //             "rate" => "1",
        //             "payment_type" => "Cash",
        //             "payment_by" => "Hanafi",
        //             "username" => "sales 1",
        //             "buyer_barcode" => "",
        //             "buyer_name" => "Pelanggan",
        //             "buyer_flight_no" => "",
        //             "buyer_destination" => "",
        //             "buyer_nationality" => "",
        //             "remark" => "",
        //             "tax_id" => "PPN",
        //             "payment_name" => "Cash",
        //             "payment_time" => "2022-08-09 15:18:55",
        //             "distance" => "30",
        //             "journey_time" => "0"
        //         ]
        //     ]
        // ];

        // // as JSON in one line:
        // echo json_encode($data);

        // or pretty printed:
        // echo json_encode($data, JSON_PRETTY_PRINT);
    }
    public function _sendWhatsapp($user_id, $last_transaksi)
    {
        $meta = $this->meta_model->get_meta();
        $whatsapp_key = $meta->whatsapp_api;

        $user = $this->user_model->detail($user_id);
        $hp = $user->user_phone;

        $message = "
        -- Gama Airport --
        Selamat Anda mendapatkan Order
        Dengan ID :
        $last_transaksi->order_id
        Silahkan Buka Aplikasi Driver 
        anda untuk konfirmasi
        ";

        $apikey = $whatsapp_key;
        $tujuan = $hp;
        $pesan = $message;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://starsender.online/api/sendText?message=' . rawurlencode($pesan) . '&tujuan=' . rawurlencode($tujuan . '@s.whatsapp.net'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'apikey: ' . $apikey
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response;
    }
    public function _sendWhatsappCustomer($last_transaksi)
    {
        $meta = $this->meta_model->get_meta();
        $whatsapp_key = $meta->whatsapp_api;


        $hp = $last_transaksi->passenger_phone;

        $message = "
        -- Gama Airport --
        Terima Kasih Telah menggunakan
        Layanan Gama Transportasi
        Berikut Data Transaksi anda
        -----------------
        Order ID : $last_transaksi->order_id
        Nama     : $last_transaksi->passenger_name
        Tujuan   : $last_transaksi->destination
        Harga    : $last_transaksi->total_price
        -----------------
        
        ";

        $apikey = $whatsapp_key;
        $tujuan = $hp;
        $pesan = $message;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://starsender.online/api/sendText?message=' . rawurlencode($pesan) . '&tujuan=' . rawurlencode($tujuan . '@s.whatsapp.net'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'apikey: ' . $apikey
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response;
    }

    public function update_status_driver($user_id)
    {
        $data = [
            'id'          => $user_id,
            'status'      => 1,
        ];
        $this->user_model->update($data);
    }

    public function success()
    {
        $data = [
            'title'     => 'Order Berhasil',
            'content'   => 'counter/transaksi/success'
        ];
        $this->load->view('counter/layout/wrapp', $data, FALSE);
    }
    // Update Transaksi
    public function update($id)
    {
        $user = $this->session->userdata('id');
        $transaksi = $this->transaksi_model->detail($id);
        if ($transaksi->user_id == $user && $transaksi->stage == 1) {

            // Start Update

            $provinsi       = $this->main_model->getProvinsi();
            $product        = $this->product_model->get_product();
            $category       = $this->category_model->get_category();

            $this->form_validation->set_rules(
                'provinsi_id',
                'Provinsi Tujuan',
                'required',
                array(
                    'required'    => 'Pilih %s'
                )
            );
            $this->form_validation->set_rules(
                'kota_id',
                'Kota Tujuan',
                'required',
                array(
                    'required'    => 'Pilih %s'
                )
            );
            $this->form_validation->set_rules(
                'category_id',
                'Kategori Barang',
                'required',
                array(
                    'required'      => 'Pilih %s'
                )
            );
            $this->form_validation->set_rules(
                'product_id',
                'Paket',
                'required',
                array(
                    'required'       => 'Pilih %s'
                )
            );
            $this->form_validation->set_rules(
                'nama_pengirim',
                'Nama Pengirim',
                'required',
                array(
                    'required'                        => '%s Harus Diisi'
                )
            );
            $this->form_validation->set_rules(
                'telp_pengirim',
                'Telp Pengirim',
                'required',
                array(
                    'required'                        => '%s Harus Diisi'
                )
            );
            $this->form_validation->set_rules(
                'alamat_pengirim',
                'Alamat Pengirim',
                'required',
                array(
                    'required'                        => '%s Harus Diisi'
                )
            );
            $this->form_validation->set_rules(
                'kodepos_pengirim',
                'Kode Pos Pengirim',
                'required',
                array(
                    'required'                        => '%s Harus Diisi'
                )
            );
            $this->form_validation->set_rules(
                'nama_penerima',
                'Nama Penerima',
                'required',
                array(
                    'required'                        => '%s Harus Diisi'
                )
            );
            $this->form_validation->set_rules(
                'telp_penerima',
                'Telp Penerima',
                'required',
                array(
                    'required'                        => '%s Harus Diisi'
                )
            );
            $this->form_validation->set_rules(
                'alamat_penerima',
                'Alamat Penerima',
                'required',
                array(
                    'required'                        => '%s Harus Diisi'
                )
            );
            $this->form_validation->set_rules(
                'kodepos_penerima',
                'Kode Pos Penerima',
                'required',
                array(
                    'required'                        => '%s Harus Diisi'
                )
            );
            $this->form_validation->set_rules(
                'nama_barang',
                'Nama Barang',
                'required',
                array(
                    'required'                        => '%s Harus Diisi'
                )
            );
            $this->form_validation->set_rules(
                'berat',
                'Berat Paket',
                'required',
                array(
                    'required'                        => '%s Harus Diisi'
                )
            );
            $this->form_validation->set_rules(
                'harga',
                'Harga Paket',
                'required',
                array(
                    'required'                        => '%s Harus Diisi'
                )
            );
            if ($this->form_validation->run() === FALSE) {
                $data = [
                    'title'                           => 'Buat Transaksi',
                    'provinsi'                        => $provinsi,
                    'product'                         => $product,
                    'category'                        => $category,
                    'user'                            => $user,
                    'transaksi'                       => $transaksi,
                    'content'                         => 'counter/transaksi/update'
                ];
                $this->load->view('counter/layout/wrapp', $data, FALSE);
            } else {


                $asuransi = $this->input->post('asuransi');
                if ($asuransi == 0) {
                    $nilai_asuransi               = $this->input->post('nilai_asuransi_zero');
                    $fix_nilai_asuransi           =  $nilai_asuransi;
                    // Nilai Barang Zero
                    $nilai_barang               = $this->input->post('nilai_barang_zero');
                    $fix_nilai_barang           = preg_replace('/\D/', '', $nilai_barang);
                } else {
                    $nilai_asuransi               = $this->input->post('nilai_asuransi');
                    $fix_nilai_asuransi           = preg_replace('/\D/', '', $nilai_asuransi);
                    // Nilai Barang
                    $nilai_barang               = $this->input->post('nilai_barang');
                    $fix_nilai_barang           = preg_replace('/\D/', '', $nilai_barang);
                }


                $harga               = $this->input->post('harga');
                $fix_harga           = preg_replace('/\D/', '', $harga);

                $total_harga         = (int)$fix_harga + (int)$fix_nilai_asuransi;


                $provinsi_id    = $this->input->post('provinsi_id');
                $kota_id        = $this->input->post('kota_id');

                $Getprovinsi = $this->provinsi_model->detail_provinsi($provinsi_id);
                $Getkota = $this->kota_model->detail($kota_id);

                $provinsi_to = $Getprovinsi->provinsi_name;
                $kota_to = $Getkota->kota_name;


                $data  = [
                    'id'                                => $id,
                    'user_id'                           => $this->session->userdata('id'),
                    'category_id'                       => $this->input->post('category_id'),
                    'product_id'                        => $this->input->post('product_id'),
                    'provinsi_id'                       => $this->input->post('provinsi_id'),
                    'kota_id'                           => $this->input->post('kota_id'),
                    'provinsi_to'                       => $provinsi_to,
                    'kota_to'                           => $kota_to,
                    'nama_pengirim'                     => $this->input->post('nama_pengirim'),
                    'telp_pengirim'                     => $this->input->post('telp_pengirim'),
                    'alamat_pengirim'                   => $this->input->post('alamat_pengirim'),
                    'email_pengirim'                    => $this->input->post('email_pengirim'),
                    'kodepos_pengirim'                  => $this->input->post('kodepos_pengirim'),
                    'nama_penerima'                     => $this->input->post('nama_penerima'),
                    'telp_penerima'                     => $this->input->post('telp_penerima'),
                    'alamat_penerima'                   => $this->input->post('alamat_penerima'),
                    'email_penerima'                    => $this->input->post('email_penerima'),
                    'kodepos_penerima'                  => $this->input->post('kodepos_penerima'),
                    'nama_barang'                       => $this->input->post('nama_barang'),
                    'berat'                             => $this->input->post('berat'),
                    'koli'                              => $this->input->post('koli'),
                    'panjang'                           => $this->input->post('panjang'),
                    'lebar'                             => $this->input->post('lebar'),
                    'tinggi'                            => $this->input->post('tinggi'),
                    'harga'                             => $fix_harga,
                    'asuransi'                          => $this->input->post('asuransi'),
                    'nilai_asuransi'                    => $fix_nilai_asuransi,
                    'total_harga'                       => $total_harga,
                    'nilai_barang'                      => $fix_nilai_barang,
                    'user_stage'                        => $this->session->userdata('id'),
                    'date_updated'                      => date('Y-m-d H:i:s')
                ];
                $this->transaksi_model->update($data);
                //Update Status Lacak
                $this->session->set_flashdata('message', 'Data  telah ditambahkan ');
                redirect(base_url('counter/transaksi'), 'refresh');
            }

            // End Update

        } else {
            redirect('counter/404');
        }
    }
    // Cancel Transaksi
    public function cancel($id)
    {
        $user = $this->session->userdata('id');
        $transaksi = $this->transaksi_model->detail($id);
        if ($transaksi->user_id == $user && $transaksi->stage == 1) {
            //Proteksi delete
            is_login();
            $data = [
                'id'                        => $id,
                'stage'                     => 10,
            ];
            $this->transaksi_model->update($data);
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissable fade show" > Data Telah di Batalkan <button class="close" data-dismiss="alert" aria-label="Close">×</button></div>');
            redirect(base_url('counter/transaksi'), 'refresh');
        } else {
            redirect('counter/404');
        }
    }
    // Riwayat Transaksi
    public function riwayat()
    {
        $user_id = $this->session->userdata('id');
        $search = $this->input->post('search');

        $config['base_url']         = base_url('counter/transaksi/riwayat/index');
        $config['total_rows']       = count($this->transaksi_model->get_row_counter($user_id, $search));
        $config['per_page']         = 10;
        $config['uri_segment']      = 5;

        //Membuat Style pagination untuk BootStrap v4
        $config['first_link']       = 'First';
        $config['last_link']        = 'Last';
        $config['next_link']        = 'Next';
        $config['prev_link']        = 'Prev';
        $config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination justify-content-center">';
        $config['full_tag_close']   = '</ul></nav></div>';
        $config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close']    = '</span></li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['next_tagl_close']  = '<span aria-hidden="true">&raquo;</span></span></li>';
        $config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['prev_tagl_close']  = '</span>Next</li>';
        $config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
        $config['first_tagl_close'] = '</span></li>';
        $config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['last_tagl_close']  = '</span></li>';
        //Limit dan Start
        $limit                      = $config['per_page'];
        $start                      = ($this->uri->segment(5)) ? ($this->uri->segment(5)) : 0;
        //End Limit Start
        $this->pagination->initialize($config);
        $transaksi = $this->transaksi_model->get_riwayat_counter($limit, $start, $user_id, $search);
        $data = [
            'title'                 => 'Riwayat Transaksi',
            'transaksi'             => $transaksi,
            'search'     => '',
            'pagination'            => $this->pagination->create_links(),
            'content'               => 'counter/transaksi/riwayat'
        ];
        $this->load->view('counter/layout/wrapp', $data, FALSE);
    }

    public function detail($id)
    {
        $user_id = $this->session->userdata('id');
        $transaksi = $this->transaksi_model->detail_counter($id, $user_id);

        if ($transaksi->user_id == $user_id) {

            $data = [
                'title'                 => 'Detail Transaksi',
                'transaksi'             => $transaksi,
                'content'               => 'counter/transaksi/detail'
            ];
            $this->load->view('counter/layout/wrapp', $data, FALSE);
        } else {
            redirect(base_url('counter/404'));
        }
    }

    public function print($id)
    {
        $user_id = $this->session->userdata('id');
        $transaksi = $this->transaksi_model->detail_counter($id, $user_id);

        if ($transaksi->user_id == $user_id) {

            $data = [
                'title'                 => 'Detail Transaksi',
                'transaksi'             => $transaksi,
                'content'               => 'counter/transaksi/print'
            ];
            $this->load->view('counter/transaksi/print', $data, FALSE);
        } else {
            redirect(base_url('counter/404'));
        }
    }
}
