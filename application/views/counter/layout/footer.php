<?php
$meta      = $this->meta_model->get_meta();
// $link      = $this->link_model->get_link();
// $page      = $this->page_model->get_page();

?>


<div class="carbook-menu-fotter fixed-bottom bg-white px-3 py-2 text-center shadow">
  <div class="row">
    <div class="col <?php if ($this->uri->segment(1) == "") {
                      echo 'selected text-info';
                    } ?>">
      <a href="<?php echo base_url('counter/dashboard'); ?>" class="text-dark small font-weight-bold text-decoration-none">
        <p class="h4 m-0"><i class="ri-home-2-line"></i></p>
        Home
      </a>
    </div>


    <!-- <div class="col <?php if ($this->uri->segment(1) == "calculate") {
                            echo 'selected text-info';
                          } ?>">
      <a href="<?php echo base_url('counter/transaksi/calculate'); ?>" class="text-dark small font-weight-bold text-decoration-none">
        <p class="h4 m-0"><i class="ri-car-washing-line"></i></p>
        Order
      </a>
    </div> -->




    <div class="col <?php if ($this->uri->segment(2) == "riwayat") {
                      echo 'selected text-info';
                    } ?>">
      <a href="<?php echo base_url('counter/transaksi/riwayat'); ?>" class="text-dark small font-weight-bold text-decoration-none">
        <p class="h4 m-0"><i class="ri-history-line"></i></p>
        Riwayat
      </a>
    </div>

    <?php if ($this->session->userdata('email')) : ?>

      <div class="col <?php if ($this->uri->segment(1) == "profile") {
                        echo 'selected text-info';
                      } ?>">

        <a href="<?php echo base_url('counter/profile') ?>" class="text-dark small font-weight-bold text-decoration-none">
          <p class="h4 m-0"><i class="ri-user-line"></i></p>
          Profile
        </a>

      </div>

    <?php else : ?>

      <div class="col <?php if ($this->uri->segment(1) == "auth") {
                        echo 'selected text-info';
                      } ?>">

        <a href="<?php echo base_url('auth') ?>" class="text-dark small font-weight-bold text-decoration-none">
          <p class="h4 m-0"><i class="ri-user-line"></i></p>
          Profile
        </a>

      </div>

    <?php endif; ?>
  </div>
</div>

<!-- <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script> -->

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script>

<script type="text/javascript" src="<?php echo base_url('assets/template/mobile/'); ?>js/slick.min.js"></script>
<script src="<?php echo base_url('assets/template/mobile/'); ?>js/main.js"></script>
<script src="<?php echo base_url() ?>assets/template/front/vendor/bootstrap/js/bootstrap.min.js"></script>

<script src="https://www.jqueryscript.net/demo/Cross-browser-Date-Time-Selector-For-jQuery-dateTimePicker/date-time-picker.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('#J-demo-01').dateTimePicker({});
    $('#J-demo-02').dateTimePicker({
      mode: 'dateTime'
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function() {

    $('#address').autocomplete({
      source: "<?php echo site_url('counter/transaksi/get_autocomplete'); ?>",

      select: function(event, ui) {
        $('[name="address"]').val(ui.item.label);
        $('[name="distance"]').val(ui.item.distance);
      }
    });

  });
</script>







<!-- Google Analitycs -->
<?php echo $meta->google_analytics; ?>
<!-- End Google Analitycs -->






<script>
  // Example starter JavaScript for disabling form submissions if there are invalid fields
  (function() {
    'use strict';
    window.addEventListener('load', function() {
      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      var forms = document.getElementsByClassName('needs-validation');
      // Loop over them and prevent submission
      var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
          if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });
    }, false);
  })();
</script>

</body>

</html>