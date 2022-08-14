<?php
//Notifikasi
if ($this->session->flashdata('message')) {
    echo $this->session->flashdata('message');
}
echo validation_errors('<div class="alert alert-warning">', '</div>');

?>
<div class="card">










    <div class="card-header">
        <ul class="nav nav-pills ml-auto p-2">
            <li class="nav-item"><a class="nav-link " href="<?php echo base_url('admin/transaksi'); ?>">Transaksi Baru</a></li>
            <li class="nav-item"><a class="nav-link " href="<?php echo base_url('admin/transaksi/proses'); ?>">Dalam Perjalanan</a></li>
            <li class="nav-item"><a class="nav-link active" href="<?php echo base_url('admin/transaksi/tolak'); ?>">ditolak Driver</a></li>
            <li class="nav-item"><a class="nav-link " href="<?php echo base_url('admin/transaksi/selesai'); ?>">Selesai</a></li>
        </ul>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <?php echo form_open('admin/transaksi'); ?>
                <div class="input-group mb-3">
                    <input type="text" name="order_id" class="form-control" placeholder="Masukan Order ID" value="<?php echo set_value('order_id'); ?>">
                    <div class="input-group-append">
                        <button class="btn btn-outline-info" type="submit" id="button-addon2">Cari</button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>


        </div>
    </div>

    <div class="card-body table-responsive p-0">
        <table class="table">
            <thead class="thead-white">
                <tr>
                    <th>#</th>
                    <th>Order ID</th>
                    <th>Tanggal</th>
                    <th>Counter</th>
                    <th>Driver</th>
                    <th>Alamat</th>
                    <th>Status</th>
                    <th>Harga</th>
                    <th width="25%">Action</th>
                </tr>
            </thead>
            <?php $no = 1;
            foreach ($transaksi as $transaksi) { ?>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $transaksi->order_id; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($transaksi->date_created)); ?> - <?php echo date('H:i:s', strtotime($transaksi->date_created)); ?></td>
                    <td><?php echo $transaksi->name; ?> </td>
                    <td><?php echo $transaksi->driver_name; ?> </td>
                    <td><?php echo $transaksi->destination; ?> </td>
                    <td>
                        <?php if ($transaksi->status == 'Mencari Pengemudi') : ?>
                            <div class="badge badge-primary"><?php echo $transaksi->status; ?></div>
                        <?php elseif ($transaksi->status == 'Dalam Pengantaran') : ?>
                            <div class="badge badge-info"><?php echo $transaksi->status; ?></div>
                        <?php elseif ($transaksi->status == 'Selesai') : ?>
                            <div class="badge badge-success"><?php echo $transaksi->status; ?></div>
                        <?php else : ?>
                            <div class="badge badge-danger"><?php echo $transaksi->status; ?></div>
                        <?php endif; ?>

                    </td>
                    <td>Rp. <?php echo number_format($transaksi->total_price, 0, ",", "."); ?></td>
                    <!-- <td><img class="img-fluid" src="<?php echo base_url('assets/img/barcode/' . $transaksi->barcode); ?>"></td> -->
                    <td>
                        <a href="<?php echo base_url('admin/transaksi/detail/' . $transaksi->id); ?>" class="btn btn-success btn-sm">
                            <i class="fa fa-eye"></i> Detail
                        </a>

                        <?php //include "cancel.php"; 
                        ?>
                    </td>
                </tr>
            <?php $no++;
            }; ?>
        </table>
    </div>
    <div class="card-footer bg-white border-top">
        <div class="pagination col-md-12 text-center">
            <?php if (isset($pagination)) {
                echo $pagination;
            } ?>
        </div>
    </div>
</div>