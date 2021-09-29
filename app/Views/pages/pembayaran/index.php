<?= $this->extend('layout/master') ?>

<?= $this->section('content-header') ?>
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Pembayaran</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Pembayaran</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<?= $this->endSection() ?>

<?= $this->section('content-body') ?>
<section class="content">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-9">
                <input type="text" name="nim" id="nim" class="form-control" placeholder="Cari berdasarkan NIM">
              </div>
              <div class="col-3">
                <button class="btn btn-primary btn-block" onclick="searchPembayaran()"><i class="fas fa-search"></i> Cari</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-2">
      <div class="col">
        <div class="card" id="search_result" style="visibility: hidden;">
          <div class="card-body">
            <div class="row">
              <div class="col">
                <h5 class="h5">Hasil Pencarian</h5>
                <table class="table table-hover">
                  <thead class="text-center">
                    <th>ID</th>
                    <th>NIM</th>
                    <th>NAMA MAHASISWA</th>
                    <th>ACTION</th>
                  </thead>
                  <tbody class="text-center" id="list_search_result"></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-2">
      <div class="col-6">
        <div class="card" id="card_detail_tagihan" style="visibility: hidden;">
          <div class="card-body">
            <h5 class="h5">Tagihan</h5>
            <div class="row">
              <div class="col">
                <table class="table table-hover" id="tbl_detail_tagihan">
                  <thead class="text-center">
                    <th>ID</th>
                    <th>ITEM TAGIHAN</th>
                    <th>NOMINAL</th>
                    <th>KETERANGAN</th>
                  </thead>
                  <tbody class="text-center"></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-6">
        <div class="card" id="card_detail_pembayaran" style="visibility: hidden;">
          <div class="card-body">
            <h5 class="h5">Pembayaran</h5>
            <div class="row">
              <div class="col">
                <table class="table table-hover" id="tbl_detail_pembayaran">
                  <thead class="text-center">
                    <th>ID</th>
                    <th>ID ITEM TAGIHAN</th>
                    <th>NOMINAL</th>
                    <th>TANGGAL PEMBAYARAN</th>
                  </thead>
                  <tbody class="text-center"></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('custom-script') ?>
<script>
  function searchPembayaran() {
    // change visibility
    $("#card_detail_tagihan").css("visibility", "hidden");
    $("#card_detail_pembayaran").css("visibility", "hidden");
    $("#tbl_detail_tagihan > tbody").empty();
    $("#tbl_detail_pembayaran > tbody").empty();
    var nim = $("#nim").val();
    var pembayaran_row = ``;
    $.ajax({
      url: "<?php site_url() ?>" + "/pembayaran/search/" + nim,
      type: "GET",
      dataType: "JSON",
      success: function(data) {
        if (data == null || data.data.mahasiswa == null) {
          alert("Data tidak ditemukan")
        } else {
          // call fillDetail() function 
          fillDetail();
          // kosongkan tbody
          $("#list_search_result").empty();
          // create new data row
          var row = `
            <tr>
              <td>${data.data.mahasiswa.id_mahasiswa}</td>
              <td>${data.data.mahasiswa.nim}</td>
              <td>${data.data.mahasiswa.nama_mahasiswa}</td>
              <td>
                <button class="btn btn-primary btn-sm" onclick="showDetail()" data-toggle="tooltip" data-placement="top" title="Lihat Detail"><i class="fas fa-info"></i></button>
                <button class="btn btn-success btn-sm" onclick="createPembayaran()" data-toggle="tooltip" data-placement="top" title="Tambah Pembayaran"><i class="fas fa-plus"></i></button>
              </td>
            </tr>
            </tr>
          `;
          for (let index = 0; index < data.data.pembayaran.length; index++) {
            pembayaran_row += `
              <tr>
                <td>${data.data.pembayaran[index].id_pembayaran}</td>
                <td>${data.data.pembayaran[index].item_id}</td>
                <td>${data.data.pembayaran[index].nominal_pembayaran}</td>
                <td>${data.data.pembayaran[index].tanggal_pembayaran}</td>
              </tr>
              `;
          }
          // show table with data row
          $("#search_result").css("visibility", "visible");
          $("#list_search_result").append(row);
          $("#tbl_detail_pembayaran > tbody").append(pembayaran_row);
        }
      },
      error: function(jqXHR) {
        console.log(jqXHR);
        alert("Data tidak ditemukan");
      }
    });
  }

  function fillDetail() {
    try {
      // declare data row
      var tagihan_row = ``;
      var total_tagihan = 0;
      // get data tagihan by nim
      $.ajax({
        url: "<?php site_url() ?>" + "/tagihan/search/" + $("#nim").val(),
        type: "GET",
        dataType: "JSON",
        success: function(data) {
          console.log(data);
          // iterate response data and fill to the tagihan_row
          // count total_tagihan
          for (let index = 0; index < data.data.item_paket.length; index++) {
            tagihan_row += `
              <tr>
              <td>${data.data.item_paket[index].id_item}</td>
              <td>${data.data.item_paket[index].nama_item}</td>
              <td>${data.data.item_paket[index].nominal_item}</td>
              <td>${data.data.item_paket[index].keterangan_item}</td>
              </tr>
              `;
            total_tagihan += data.data.item_paket[index].nominal_item;
          }
          // append table
          $("#tbl_detail_tagihan > tbody").append(tagihan_row);
        },
        error: function(jqXHR) {
          alert('Error!');
          console.log(jqXHR);
        }
      });
    } catch (error) {
      console.log(error);
      alert(error);
    }
  }

  function showDetail() {
    // change visibility
    $("#card_detail_tagihan").css("visibility", "visible");
    $("#card_detail_pembayaran").css("visibility", "visible");
  }
</script>
<?= $this->endSection() ?>