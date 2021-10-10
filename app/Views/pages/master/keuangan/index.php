<?= $this->extend('layout/master') ?>

<?= $this->section('content-header') ?>
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Master Keuangan</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item"><a href="#">Master</a></li>
          <li class="breadcrumb-item active">Keuangan</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<?= $this->endSection() ?>

<?= $this->section('content-body') ?>
<section class="content">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col">
        <div class="card">
          <div class="card-body">
            <h5 class="h5">Data Paket dan Item Paket<span></span></h5>
            <select name="paket" class="form-control" id="select_paket">
            <?php
              foreach ($data_paket as $p) {
                echo '<option value="' . $p['id_paket'] . '">' . $p['nama_paket'] . '</option>';
              }
            ?>
            </select>
            <table class="table" id="tbl_master_paket"></table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('custom-script') ?>
<script src="">
  // TODO - jquery function: retrieve data, update, and delete paket tagihan & item tagihan - 2021/10/08
  $(document).ready(function() {

    // get data on selected select paket
    $('#select_paket').change(function(){
      $.ajax({
        url: "<?php base_url()?>" + "/itempaket/" + $(this).val(),
        type: "GET", 
        success: function(data){

        },
        error: function(jqXHR){

        }
      });
    });

  });
</script>
<?= $this->endSection() ?>