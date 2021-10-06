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
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modalCreatePembayaran" aria-labelledby="modalCreatePembayaran" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Tambah Pembayaran</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="<?php base_url() ?>/pembayaran/create" method="post">
              <input type="number" name="paket_id" id="paket_id" hidden>
              <input type="number" name="mahasiswa_id" id="mahasiswa_id" hidden>
              <div class="form-group">
                <label for="itempembayaran">ITEM PAKET</label>
                <select class="form-control" name="item_id" id="item_id"></select>
              </div>
              <div class="form-group">
                <label for="tanggal_pembayaran">TANGGAL PEMBAYARAN</label>
                <input type="date" class="form-control" name="tanggal_pembayaran" id="tanggal_pembayaran">
              </div>
              <div class="form-group">
                <label for="">NOMINAL PEMBAYARAN</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Rp. </span>
                  </div>
                  <input type="number" class="form-control" name="nominal_pembayaran" id="nominal_pembayaran" aria-label="NOMINAL PEMBAYARAN">
                  <div class="input-group-append">
                    <span class="input-group-text">.00</span>
                  </div>
                </div>
              </div>
              <p id="message"></p>
              <button class="btn btn-success float-right" id="btn_tambah_pembayaran" style="width: 200px;" onclick="createPembayaran()">Tambah Pembayaran</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('custom-script') ?>
<script>
  /**
   * search pembayaran by nim
   * and show result table
   */
  function searchPembayaran() {
    // change visibility
    $("#card_detail_tagihan").css("visibility", "hidden");
    $("#card_detail_pembayaran").css("visibility", "hidden");
    $("#tbl_detail_tagihan > tbody").empty();
    $("#tbl_detail_pembayaran > tbody").empty();
    $("#mahasiswa_id").val(0);
    $("#paket_id").val(0);
    var nim = $("#nim").val();
    var pembayaran_row = ``;
    $.ajax({
      url: "<?php site_url() ?>" + "/pembayaran/search/" + nim,
      type: "GET",
      dataType: "JSON",
      success: function(data) {
        if (data == null || data.data.mahasiswa == null) {
          showSWAL('error', 'Data tidak ditemukan!');
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
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalCreatePembayaran"><i class="fas fa-plus"></i></button>
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
          // fill id_mahasiswa to modal create pembayaran
          $("#mahasiswa_id").val(data.data.mahasiswa.id_mahasiswa);
          // show table with data row
          $("#search_result").css("visibility", "visible");
          $("#list_search_result").append(row);
          $("#tbl_detail_pembayaran > tbody").append(pembayaran_row);
        }
      },
      error: function(jqXHR) {
        console.log(jqXHR);
        showSWAL('error', 'Data tidak ditemukan!');
      }
    });
  }

  /**
   * fill detail of tagihan mahasiswa
   */
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
            $("#item_id").append(`<option value="${data.data.item_paket[index].id_item}">${data.data.item_paket[index].nama_item} - Rp. ${data.data.item_paket[index].nominal_item}</option>`)
          }
          // fill paket_id to modalCreatePembayaran
          $("#paket_id").val(data.data.detail_paket.id_paket);
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

  /**
   * create a new pembayaran
   */
  function createPembayaran() {
    $("#btn_tambah_pembayaran").prop('disabled', true);
    $("#btn_tambah_pembayaran").html(`<div class="spinner-border text-light spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>`);
    console.log(`
    'item_id': ${$("#item_id").val()},
    'paket_id': ${$("#paket_id").val()},
    'mahasiswa_id': ${$("#mahasiswa_id").val()},
    'tanggal_pembayaran': ${$("#tanggal_pembayaran").val()},
    'nominal_pembayaran': ${$("#nominal_pembayaran").val()},
    'user_id': 1
    `);
    var mydata = {
      item_id: $("#item_id").val(),
      paket_id: $("#paket_id").val(),
      mahasiswa_id: $("#mahasiswa_id").val(),
      tanggal_pembayaran: $("#tanggal_pembayaran").val(),
      nominal_pembayaran: $("#nominal_pembayaran").val(),
      user_id: 1
    }
    $.ajax({
      url: "<?php base_url() ?>/pembayaran/create",
      type: "POST",
      data: mydata,
      success: function(res) {
        var response = JSON.parse(res)
        console.log(response);
        $("#btn_tambah_pembayaran").prop('disabled', false);
        $("#btn_tambah_pembayaran").html('Tambah Pembayaran');
        showSWAL(response.status, response.message);
      },
      error: function(jqXHR) {
        console.log(jqXHR)
        $("#btn_tambah_pembayaran").prop('disabled', false);
        $("#btn_tambah_pembayaran").html('Tambah Pembayaran');
        // $("p").html(jqXHR);
        showSWAL('error', jqXHR);
      }
    });
  }

  function showSWAL(type, message) {
    Swal.fire({
      title: type == 'error' || type == 'failed'? 'Error':'Success',
      text: message,
      icon: type == 'error' || type == 'failed'? 'error':'success',
      confirmButtonText: 'OK'
    });
  }

  /**
   * show detail card
   */
  function showDetail() {
    // change visibility
    $("#card_detail_tagihan").css("visibility", "visible");
    $("#card_detail_pembayaran").css("visibility", "visible");
  }
</script>
<?= $this->endSection() ?>