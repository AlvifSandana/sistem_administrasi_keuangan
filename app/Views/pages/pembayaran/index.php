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
                <table class="table table-hover table-bordered" id="tbl_detail_tagihan">
                  <thead class="text-center">
                    <th>ID</th>
                    <th>ITEM TAGIHAN</th>
                    <th>KETERANGAN</th>
                    <th>NOMINAL</th>
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
                <table class="table table-hover table-bordered" id="tbl_detail_pembayaran">
                  <thead class="text-center">
                    <th>ITEM PEMBAYARAN</th>
                    <th>TERBAYAR</th>
                    <th>ACTION</th>
                  </thead>
                  <tbody class="text-center"></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal Tambah Pembayaran -->
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
  // TODO - SPESIFIK PER ITEM TAGIHAN UNTUK KETERANGAN PEMBAYARAN - 2021/10/11
  var num_format = Intl.NumberFormat();
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
          // fill id_mahasiswa to modal create pembayaran
          $("#mahasiswa_id").val(data.data.mahasiswa.id_mahasiswa);
          // show table with data row
          $("#search_result").css("visibility", "visible");
          $("#search_result").addClass("animate__animated animate__fadeIn")
          $("#list_search_result").append(row);
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
      var pembayaran_row = ``;
      var total_tagihan = 0;
      var total_pembayaran
      // get data tagihan by nim
      $.ajax({
        url: "<?php site_url() ?>" + "/tagihan/search/" + $("#nim").val(),
        type: "GET",
        dataType: "JSON",
        success: function(data) {
          console.log(data);
          // iterate response data and fill to the
          // count total_tagihan
          for (let index = 0; index < data.data.item_paket.length; index++) {
            tagihan_row += `
            <tr>
              <td>${data.data.item_paket[index].id_item}</td>
              <td>${data.data.item_paket[index].nama_item}</td>
              <td>${data.data.item_paket[index].keterangan_item}</td>
              <td class="text-left">Rp ${num_format.format(parseInt(data.data.item_paket[index].nominal_item))}</td>
            </tr>`;
            pembayaran_row += `<tr><td>${data.data.item_paket[index].nama_item}</td>`;
            var nominal_item_terbayar = 0;
            for (let index1 = 0; index1 < data.data.item_paket_terbayar.length; index1++) {
              if (data.data.item_paket[index].id_item == data.data.item_paket_terbayar[index1].item_id) {
                nominal_item_terbayar += parseInt(data.data.item_paket_terbayar[index1].nominal_pembayaran);
              }
            }
            pembayaran_row += `<td>Rp ${num_format.format(nominal_item_terbayar)}</td><td><button class="btn btn-sm btn-primary"><i class="fas fa-info" ttile="Detail"></i></button></td></tr>`;
            total_tagihan += parseInt(data.data.item_paket[index].nominal_item);
            total_pembayaran += parseInt(nominal_item_terbayar);
            $("#item_id").append(`<option value="${data.data.item_paket[index].id_item}">${data.data.item_paket[index].nama_item} - Rp. ${data.data.item_paket[index].nominal_item}</option>`)
          }
          var row_total = `<tr class="font-weight-bold">
          <td colspan="3">Total Tagihan</td>
          <td>Rp ${num_format.format(total_tagihan)}</td>
          `;
          // fill paket_id to modalCreatePembayaran
          $("#paket_id").val(data.data.detail_paket.id_paket);
          // append table
          $("#tbl_detail_tagihan > tbody").append(tagihan_row);
          $("#tbl_detail_pembayaran > tbody").append(pembayaran_row);
          $("#tbl_detail_tagihan > tbody").append(row_total);
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
        var new_tagihan_row = `
        <tr>
          <td>${response.data}</td>
          <td>${mydata.item_id}</td>
          <td>Rp ${num_format.format(parseInt(mydata.nominal_pembayaran))}</td>
          <td>${Intl.DateTimeFormat('id-id', {dateStyle: 'full'}).format(Date.parse(mydata.tanggal_pembayaran))}</td>
        </tr>`;
        $("#tbl_detail_pembayaran > tbody").append(new_tagihan_row)
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