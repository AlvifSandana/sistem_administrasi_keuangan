<?= $this->extend('layout/master') ?>

<?= $this->section('content-header') ?>
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Tagihan</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Tagihan</li>
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
                <button class="btn btn-primary btn-block" onclick="searchMhs()"><i class="fas fa-search"></i> Cari</button>
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
                  <tbody class="text-center" id="list_tagihan">
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<div class="modal fade bd-example-modal-lg" id="modalDetailTagihan" tabindex="-1" role="dialog" aria-labelledby="modalDetailTagihan" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detail Tagihan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="detail_nim">NIM</label>
          <input type="text" class="form-control" name="nim" id="detail_nim" disabled/>
        </div>
        <div class="form-group">
          <label for="detail_nim">NAMA MAHASISWA</label>
          <input type="text" class="form-control" name="nama_mhs" id="detail_nama_mhs" disabled/>
        </div>
        <div class="form-group">
          <label for="detail_nim">PAKET</label>
          <input type="text" class="form-control" name="paket" id="detail_nama_paket" disabled/>
        </div>
        <label>ITEM PAKET</label>
        <table class="table table-hover table-bordered">
          <thead class="text-center">
            <th>ID</th>
            <th>Item Pembayaran</th>
            <th>Nominal</th>
            <th>Keterangan</th>
          </thead>
          <tbody class="text-center" id="list_item"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('custom-script') ?>
<script>
  function searchMhs() {
    var nim = $("#nim").val();
    $.ajax({
      url: "<?php site_url() ?>" + "/tagihan/search/" + nim,
      type: "GET",
      dataType: "JSON",
      success: function(data) {
        if (data == null || data.status == "failed") {
          alert("Data tidak ditemukan");
        } else {
          $("#list_tagihan").empty();
          // hasil pencarian, baris baru
          var row = `
          <tr class="">
            <td>${data.data.id_tagihan}</td>
            <td>${data.data.nim}</td>
            <td>${data.data.nama_mahasiswa}</td>
            <td>
              <button class="btn btn-success btn-sm" id="btn_detail" data-toggle="modal" data-target="#modalDetailTagihan"><i class="fas fa-info"></i></button>
            </td>
          </tr>`;
          // tampilkan hasil pencarian
          $("#search_result").css('visibility', 'visible');
          $("#list_tagihan").append(row);
          // hasil pencarian, detail tagihan mahasiswa
          $("#detail_nim").val(data.data.nim);
          $("#detail_nama_mhs").val(data.data.nama_mahasiswa);
          $("#detail_nama_paket").val(data.data.detail_paket.nama_paket);
          // hasil pencarian, item tagihan
          var total_tagihan = 0;
          for (let index = 0; index < data.data.item_paket.length; index++) {
            total_tagihan += parseInt(data.data.item_paket[index].nominal_item);
            var row1 = `<tr class="">
            <td>${data.data.item_paket[index].id_item}</td>
            <td>${data.data.item_paket[index].nama_item}</td>
            <td>Rp ${data.data.item_paket[index].nominal_item}</td>
            <td>${data.data.item_paket[index].keterangan_item}</td>
            </tr>`;
            $("#list_item").append(row1);
          }
          var row_total = `<tr class="font-weight-bold">
          <td colspan="3">Total Tagihan</td>
          <td>Rp ${total_tagihan}</td>
          `;
          $("#list_item").append(row_total);
        }
      },
      error: function(jqXHR) {
        console.log(jqXHR);
        alert("Data tidak ditemukan");
      }
    });
  }

  function detail() {

  }
</script>
<?= $this->endSection() ?>