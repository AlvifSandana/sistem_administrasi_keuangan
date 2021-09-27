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
        <div class="card" style="visibility: hidden;">
          <div class="card-body">

          </div>
        </div>
      </div>
      <div class="col-6">
        <div class="card" style="visibility: hidden;">
          <div class="card-body">
            <div class="row">
              <div class="col">
                <table class="table table-hover">
                  <thead class="text-center">
                    <th>ID</th>
                    <th>NAMA PEMBAYARAN</th>
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
  function searchPembayaran(){
    var nim  = $("#nim").val();
    $.ajax({
      url: "<?php site_url() ?>" + "/pembayaran/search/" + nim,
      type: "GET",
      dataType: "JSON",
      success: function(data){
        if (data == null || data.data.mahasiswa == null) {
          alert("Data tidak ditemukan")
        } else {
          console.log(data.data);
          // kosongkan tbody
          $("#list_search_result").empty();
          // create new data row
          var row = `
            <tr>
              <td>${data.data.mahasiswa.id_mahasiswa}</td>
              <td>${data.data.mahasiswa.nim}</td>
              <td>${data.data.mahasiswa.nama_mahasiswa}</td>
              <td><button class="btn btn-primary btn-sm" onclick=""><i class="fas fa-info"></i></button></td>
            </tr>
            </tr>
          `;
          // show table with data row
          $("#search_result").css("visibility", "visible");
          $("#list_search_result").append(row);
          // call fillDetail() function 
          fillDetail(data);
        }
      },
      error: function(jqXHR){
        console.log(jqXHR);
        alert("Data tidak ditemukan");
      }
    });
  }

  function fillDetail(data){
    try {
      // declare data row
      var tagihan_row = ``;
      var pembayaran_row = ``;
      // check if pembayaran undefined or not
      if (data.data.pembayaran != undefined) {
        $.ajax({
          url: "<?php site_url() ?>" + "/pembayaran/detail-item-pembayaran/" + data.data.pembayaran[0].paket_id,
          type: "GET",
          dataType: "JSON", 
          success: function(data){

          },
          error: function(){
            
          }
        });
      } else {
        
      }
    } catch (error) {
      
    }
  }
</script>
<?= $this->endSection() ?>