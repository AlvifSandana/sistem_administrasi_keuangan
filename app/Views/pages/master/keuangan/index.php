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
            <h5 class="h5">Data Paket Tagihan</h5>
            <select name="paket" class="form-control" id="select_paket" onchange="getItemPaket()" onload="getItemPaket()">
              <?php
              foreach ($data_paket as $p) {
                if ($p['id_paket'] == 1) {
                  echo '<option value="' . $p['id_paket'] . '" selected>' . $p['nama_paket'] . '</option>';
                }else{
                  echo '<option value="' . $p['id_paket'] . '">' . $p['nama_paket'] . '</option>';
                }
              }
              ?>
            </select>
            <h5 class="h5 mt-3">Detail Item Paket</h5>
            <table class="table table-bordered tbl_master_paket" id="tbl_master_paket">
              <thead class="text-center">
                <th>ID Item</th>
                <th>Nama Item Tagihan</th>
                <th>Nominal</th>
                <th>Keterangan</th>
                <th>Action</th>
              </thead>
              <tbody class="text-center">
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('custom-script') ?>
<script>
  // TODO - jquery function: retrieve data, update, and delete paket tagihan & item tagihan - 2021/10/08
  // get data on selected paket tagihan
  function getItemPaket() {
    $("#tbl_master_paket > tbody").empty();
    $.ajax({
      url: "<?php base_url() ?>" + "/itempaket/" + $('select#select_paket').children('option:selected').val(),
      type: "GET",
      success: function(data) {
        var response = JSON.parse(data);
        console.log(response);
        console.log(response.status);
        if (response.status != "success") {
          alert(data.message);
        } else {
          var row_item_tagihan = "";
          var total_tagihan = 0;
          var numformat = Intl.NumberFormat();
          for (let index = 0; index < response.data.length; index++) {
            row_item_tagihan += `<tr>
              <td>${response.data[index].id_item}</td>
              <td>${response.data[index].nama_item}</td>
              <td class="text-left">Rp. ${numformat.format(parseInt(response.data[index].nominal_item))}</td>
              <td>${response.data[index].keterangan_item}</td>
              <td>
                <button class="btn btn-sm btn-warning"><i class="far fa-edit"></i></button>
                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
              </td>
              </tr>`;
              total_tagihan += parseInt(response.data[index].nominal_item);
          }
          Intl
          $("#tbl_master_paket > tbody").append(row_item_tagihan);
          $("#tbl_master_paket > tbody").append(`<tr class="font-weight-bold"><td colspan="3">Total Tagihan</td><td colspan="2">Rp. ${numformat.format(total_tagihan)}</td></tr>`);
        }
      },
      error: function(jqXHR) {
        console.log(jqXHR)
      }
    });
  }
</script>
<?= $this->endSection() ?>