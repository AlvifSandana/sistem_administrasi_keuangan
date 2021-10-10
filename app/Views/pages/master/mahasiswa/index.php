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
            <h5 class="h5">Data Mahasiswa</h5>
            <table class="table">
                <!-- TODO - create table for data mahasiswa -->
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
<?= $this->endSection() ?>

<?= $this->section('custom-script') ?>
<script src="">
// TODO - jquery script for show details, edit, retrieve data, and delete data mahasiswa - 2021/10/08
</script>
<?= $this->endSection() ?>