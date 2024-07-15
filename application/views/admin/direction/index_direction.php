<div class="card shadow">
    <div class="card-header">
        <h3><?php echo $title; ?></h3>
        <hr>
        <?php include "create_direction.php"; ?>
    </div>
    <div class="card-body">
        <?php
        //Notifikasi
        if ($this->session->flashdata('message')) {
            echo '<div class="alert alert-success">';
            echo $this->session->flashdata('message');
            echo '</div>';
        }
        echo validation_errors('<div class="alert alert-warning">', '</div>');

        ?>


        <div class="table-responsive">
            <table class="table table-bordered zero-configuration" id="dataTable" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th>Alamat</th>
                        <th>Distance</th>
                        <th width="15%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($direction as $direction) { ?>
                        <tr>
                            <td><?php echo $direction->address; ?></td>
                            <td><?php echo $direction->distance; ?></td>
                            <td>
                                <?php include "update_direction.php"; ?>
                                <?php include "delete_direction.php"; ?>
                            </td>
                        </tr>

                    <?php }; ?>


                </tbody>
            </table>
        </div>
    </div>
</div>