<nav class="site-header bg-white sticky-top py-1 shadow-sm">
    <div class="container py-2 d-flex justify-content-between align-items-center">
        <a style="text-decoration:none;" class="text-dark text-left" href="javascript:history.back()"><i style="font-size: 25px;" class="ri-arrow-left-line"></i></a>
        <span class="text-dark text-center font-weight-bold">
            <?php echo $title; ?>
        </span>
        <div style="color:transparent;"></div>
    </div>
</nav>
<div class="container my-3 pb-5">

    <div class="col-12 mx-auto mb-3">
        <div class="list-wrapper">
            <div class="red-line"></div>
            <div class="list-item-wrapper">
                <div class="list-bullet bg-primary"><i class="ri-stop-fill"></i></div>
                <div class="list-item">
                    <div class="list-title">Pick Up</div>
                    <p><?php echo $transaksi->origin; ?></p>
                </div>
            </div>

            <div class="list-item-wrapper">
                <div class="list-bullet bg-success"><i class="ri-map-pin-2-fill"></i></div>
                <div class="list-item">
                    <div class="list-title">Drop Off</div>
                    <p><?php echo $transaksi->destination; ?></p>
                </div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="card border-0 shadow">
            <div class="card-header bg-white">
                <?php echo date('j M Y', strtotime($transaksi->date_created)); ?> - <?php echo date('H:i', strtotime($transaksi->date_created)); ?>
                <?php echo $transaksi->order_id; ?>
            </div>

            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <td>Jarak</td>
                        <td class="text-right"><?php echo $transaksi->jarak; ?> Km</td>
                    </tr>
                    <tr>
                        <td>Harga</td>
                        <td class="text-right">Rp. <?php echo number_format($transaksi->total_price, 0, ",", "."); ?></td>
                    </tr>
                    <tr>
                        <td>Type</td>
                        <td class="text-right"><?php echo $transaksi->product_name; ?></td>
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
        <?php if ($transaksi->driver_id == 0) : ?>
            <a class="btn btn-primary btn-block my-3" href="<?php echo base_url('counter/transaksi/select_driver/' . $transaksi->id); ?> ">Pilih Driver</a>
        <?php else : ?>
        <?php endif; ?>
    </div>
</div>