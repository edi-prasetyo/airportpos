<?php $meta = $this->meta_model->get_meta(); ?>

<div class="invoice p-3 mb-3">

    <div class="row">
        <div class="col-12">
            <h4>
                <i class="fas fa-globe"></i>PT. Mandar Gama Indonesia

            </h4>
        </div>

    </div>

    <div class="row invoice-info">


        <div class="col-sm-6 invoice-col">
            Customer
            <address>
                <strong><?php echo $transaksi->passenger_name; ?> </strong><br>
                <?php echo $transaksi->destination; ?> <br>
            </address>
        </div>

        <div class="col-sm-6 invoice-col float-right">
            <b>Invoice : <?php echo $transaksi->invoice_no; ?> </b><br>
            <b>Order ID:</b> <?php echo $transaksi->order_id; ?><br>
            <b>Tanggal:</b> <?php echo date('d/m/Y', strtotime($transaksi->trans_date)); ?> - <?php echo date('H:i', strtotime($transaksi->trans_time)); ?><br>
        </div>

    </div>


    <div class="row">
        <div class="col-12 table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Qty</th>
                        <th>Product</th>
                        <th>Destinasi</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $transaksi->item_qty; ?></td>
                        <td><?php echo $transaksi->item_name; ?></td>
                        <td><?php echo $transaksi->destination; ?></td>
                        <td>Rp. <?php echo number_format($transaksi->item_price_amount, 0, ",", "."); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <div class="row">

        <div class="col-6">

            <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">

            </p>
        </div>

        <div class="col-6">

            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th style="width:50%">Subtotal:</th>
                        <td>Rp. <?php echo number_format($transaksi->item_total_price_amount, 0, ",", "."); ?></td>
                    </tr>
                    <tr>
                        <th>Tax (<?php echo $meta->tax; ?>% )</th>
                        <td>Rp. <?php echo number_format($transaksi->item_total_vat, 0, ",", "."); ?></td>
                    </tr>

                    <tr>
                        <th>Total:</th>
                        <td>Rp. <?php echo number_format($transaksi->transaction_amount, 0, ",", "."); ?></td>
                    </tr>
                </table>
            </div>
        </div>

    </div>



</div>