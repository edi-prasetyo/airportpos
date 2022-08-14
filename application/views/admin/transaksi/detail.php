<?php $meta = $this->meta_model->get_meta(); ?>

<div class="card">
    <div class="card-header">
        <?php echo $title; ?>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-6">
                <p><span class="font-weight-bold">From</span> : <?php echo $transaksi->origin; ?></p>
                <p><span class="font-weight-bold">To</span> : <?php echo $transaksi->destination; ?></p>
            </div>
            <div class="col-6 text-right">
                <p><span class="font-weight-bold">Nama Penumpang</span> : <?php echo $transaksi->passenger_name; ?></p>
                <p><span class="font-weight-bold">Telp</span> : <?php echo $transaksi->passenger_phone; ?></p>
            </div>
        </div>


        <div class="col-12">
            <div class="card">


                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td>Kode</td>
                            <td class="text-right"><?php echo $transaksi->order_id; ?></td>
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td class="text-right"><?php echo date('d/m/Y', strtotime($transaksi->date_created)); ?> - <?php echo date('H:i', strtotime($transaksi->date_created)); ?></td>
                        </tr>
                        <tr>
                            <td>Jarak</td>
                            <td class="text-right"><?php echo $transaksi->jarak; ?> Km</td>
                        </tr>
                        <tr>
                            <td>Harga</td>
                            <td class="text-right">Rp. <?php echo number_format($transaksi->total_price, 0, ",", "."); ?></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td class="text-right">
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
                        </tr>

                    </tbody>
                </table>

            </div>
        </div>
    </div>

</div>