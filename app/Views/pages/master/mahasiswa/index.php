<?= $this->extend('layout/master') ?>

<?= $this->section('content-header') ?>
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Master Mahasiswa</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item"><a href="#">Master</a></li>
          <li class="breadcrumb-item active">Mahasiswa</li>
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
            <h5 class="h5">Data Mahasiswa <button class="btn btn-success float-right"><i class="fas fa-plus"></i> Tambah Data Mahasiswa</button></h5>
            <table class="table mt-3" id="tbl_list_mhs">
              <thead class="text-center">
                <th>ID</th>
                <th>NIM</th>
                <th>NAMA MAHASISWA</th>
                <th>ACTION</th>
              </thead>
              <tbody class="text-center">
                <?php foreach ($data_mahasiswa as $m) {
                  echo '<tr data-idmhs="'.$m['id_mahasiswa'].'"><td>'.$m['id_mahasiswa'].'</td><td>'.$m['nim'].'</td><td>'.$m['nama_mahasiswa'].'</td><td><button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button><button class="btn btn-sm btn-danger mx-1"><i class="fas fa-trash"></i></button></td></tr>';
                } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-2">
      <div class="col">
        <div class="card">
          <div class="card-body">
            <h5 class="h5">Import dan Export Data</h5>
            <!-- TODO - create 2 button input for import and export data -->
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Model Create new Data Mahasiswa -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modalCreatePembayaran" aria-labelledby="modalCreatePembayaran" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Tambah Data Mahasiswa</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="<?php base_url() ?>/mahasiswa/create" method="post">
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
<?= $this->endSection() ?>

<?= $this->section('custom-script') ?>
<script>
  // TODO - jquery script for show details, edit, retrieve data, and delete data mahasiswa - 2021/10/08
</script>
<?= $this->endSection() ?>