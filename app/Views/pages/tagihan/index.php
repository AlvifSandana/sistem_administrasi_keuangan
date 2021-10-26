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
    <div class="row mb-2">
      <div class="col">
        <div class="card" style="visibility: hidden;">
          <div class="card-body detail-tagihan">
            <div class="tagihan-1 mb-2">
              <h5 class="h5">Tagihan nama_paket <span class="badge badge-warning">status_tagihan</span></h5>
              <table class="table table-hover">
                <thead>
                  <th>ITEM TAGIHAN</th>
                  <th>NOMINAL</th>
                  <th>TERBAYAR</th>
                  <th>ACTION</th>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- modal detail tagihan -->
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
          <input type="text" class="form-control" name="nim" id="detail_nim" disabled />
        </div>
        <div class="form-group">
          <label for="detail_nim">NAMA MAHASISWA</label>
          <input type="text" class="form-control" name="nama_mhs" id="detail_nama_mhs" disabled />
        </div>
        <div class="form-group">
          <label for="detail_nim">PAKET</label>
          <input type="text" class="form-control" name="paket" id="detail_nama_paket" disabled />
        </div>
        <label>ITEM PAKET</label>
        <table class="table table-hover table-bordered">
          <thead class="text-center">
            <th>ID</th>
            <th>Item Pembayaran</th>
            <th>Nominal Tagihan</th>
            <th>Nominal Terbayar</th>
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
          showSWAL('error', data.message);
        } else {          
          var detail_tagihan = data.detail_tagihan;
          // total tagihan & total terbayar
          var total_tagihan = 0;
          var total_terbayar = 0;
          var new_tagihan = ``;
          var new_row = ``;
          // number format
          var numFormat = Intl.NumberFormat();
          // iterate data.detail_tagihan
          for (let index = 0; index < detail_tagihan.length; index++) {
            for (let i = 0; i < detail_tagihan[index].detail_item_paket.length; i++) {
              const element = detail_tagihan[index].detail_item_paket[i];
              new_row += `
              <tr>
                <td>${detail_tagihan[index].detail_item_paket[i].nama_item}</td>
                <td>${detail_tagihan[index].detail_item_paket[i].nominal_item}</td>
                <td></td>
                <td></td>
              </tr>
              `;
            }
            new_tagihan += `
            <div class="tagihan-${index} mb-2">
              <h5 class="h5">Tagihan ${detail_tagihan[index].data_paket[0].nama_paket} <span class="badge badge-${detail_tagihan[index].status_tagihan.toLowerCase() == 'lunas' ? 'success' : 'warning'}">${detail_tagihan[index].status_tagihan}</span></h5>
              <table class="table table-hover">
                <thead>
                  <th>ITEM TAGIHAN</th>
                  <th>NOMINAL</th>
                  <th>TERBAYAR</th>
                  <th>ACTION</th>
                </thead>
                <tbody>

                </tbody>
              </table>
            </div>`;
          }
        }
      },
      error: function(jqXHR) {
        console.log(jqXHR);
        showSWAL('error', jqXHR);
      }
    });
  }
</script>
<?= $this->endSection() ?>