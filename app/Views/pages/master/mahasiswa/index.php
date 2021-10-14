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
            <h5 class="h5">Data Mahasiswa <button class="btn btn-success float-right" data-toggle="modal" data-target="#modalAddMahasiswa"><i class="fas fa-plus"></i> Tambah Data Mahasiswa</button></h5>
            <table class="table mt-3" id="tbl_list_mhs">
              <thead class="text-center">
                <th>ID</th>
                <th>NIM</th>
                <th>NAMA MAHASISWA</th>
                <th>ACTION</th>
              </thead>
              <tbody class="text-center">
                <?php foreach ($data_mahasiswa as $m) {
                  echo '<tr data-idmhs="' . $m['id_mahasiswa'] . '"><td>' . $m['id_mahasiswa'] . '</td><td>' . $m['nim'] . '</td><td>' . $m['nama_mahasiswa'] . '</td><td><button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalUpdateMahasiswa" onclick="fillModalUpdateForm(' . $m["id_mahasiswa"] . ')"><i class="fas fa-edit"></i></button><button class="btn btn-sm btn-danger mx-1" onclick="deleteMahasiswa(' . $m['id_mahasiswa'] . ')"><i class="fas fa-trash"></i></button></td></tr>';
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
            <h5 class="h5 mb-3">Import dan Export Data</h5>
            <form action="<?php base_url() ?>/master-mahasiswa/import/upload" method="post" enctype="multipart/form-data">
            <div class="input-group">
              <div class="custom-file">
                <input type="file" class="custom-file-input" name="file_import" id="file_import" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet`">
                <label class="custom-file-label" for="file_import">Choose file (.xlsx)</label>
              </div>
              <div class="input-group-append">
                <button class="btn btn-success" type="submit" >Import</button>
              </div>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Modal Create new Data Mahasiswa -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modalAddMahasiswa" aria-labelledby="modalAddMahasiswa" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Data Mahasiswa</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="nim">NIM</label>
          <input type="text" name="nim" id="nim" class="form-control">
        </div>
        <div class="form-group">
          <label for="nama_mahasiswa">NAMA MAHASISWA</label>
          <input type="text" name="nama_mahasiswa" id="nama_mahasiswa" class="form-control">
        </div>
        <div class="form-group">
          <label for="progdi_id">PROGRAM STUDI</label>
          <select name="progdi_id" id="progdi_id" class="form-control">
            <?php
            foreach ($data_progdi as $p) {
              echo '<option value="' . $p['id_progdi'] . '">' . $p['nama_progdi'] . '</option>';
            }
            ?>
          </select>
        </div>
        <div class="form-group mb-3">
          <label for="angkatan_id">ANGKATAN</label>
          <select name="angkatan_id" id="angkatan_id" class="form-control">
            <?php
            foreach ($data_angkatan as $a) {
              echo '<option value="' . $a['id_angkatan'] . '">' . $a['nama_angkatan'] . '</option>';
            }
            ?>
          </select>
        </div>
        <button class="btn btn-success float-right" onclick="createMahasiswa()">Tambah</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal Update Data Mahasiswa -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modalUpdateMahasiswa" aria-labelledby="modalUpdateMahasiswa" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Data Mahasiswa</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="text" name="id_mahasiswa" id="id_mahasiswa" disabled hidden>
        <div class="form-group">
          <label for="nim">NIM</label>
          <input type="text" name="update_nim" id="update_nim" class="form-control">
        </div>
        <div class="form-group">
          <label for="nama_mahasiswa">NAMA MAHASISWA</label>
          <input type="text" name="nama_mahasiswa" id="update_nama_mahasiswa" class="form-control">
        </div>
        <div class="form-group">
          <label for="progdi_id">PROGRAM STUDI</label>
          <select name="progdi_id" id="update_progdi_id" class="form-control">
            <?php
            foreach ($data_progdi as $p) {
              echo '<option value="' . $p['id_progdi'] . '">' . $p['nama_progdi'] . '</option>';
            }
            ?>
          </select>
        </div>
        <div class="form-group mb-3">
          <label for="angkatan_id">ANGKATAN</label>
          <select name="angkatan_id" id="update_angkatan_id" class="form-control">
            <?php
            foreach ($data_angkatan as $a) {
              echo '<option value="' . $a['id_angkatan'] . '">' . $a['nama_angkatan'] . '</option>';
            }
            ?>
          </select>
        </div>
        <button class="btn btn-warning float-right" onclick="updateMahasiswa()">Update</button>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('custom-script') ?>
<script>
  function createMahasiswa() {
    var data_mahasiswa = {
      nim: $('#nim').val(),
      nama_mahasiswa: $('#nama_mahasiswa').val(),
      progdi_id: parseInt($('#progdi_id').val()),
      angkatan_id: parseInt($('#angkatan_id').val()),
    };
    console.log(data_mahasiswa);
    $.ajax({
      url: '<?php base_url() ?>' + '/master-mahasiswa/create',
      type: 'POST',
      data: data_mahasiswa,
      dataType: 'JSON',
      success: function(data) {
        console.log(data);
        if (data.status != 'success') {
          showSWAL('error', data.message);
        } else {
          showSWAL('success', data.message);
          $('#nim').val('');
          $('#nama_mahasiswa').val('');
          window.location.reload();
        }
      },
      error: function(jqXHR) {
        showSWAL('error', jqXHR.error);
      }
    });
  }

  function fillModalUpdateForm(id_mahasiswa) {
    $.ajax({
      url: '<?php base_url() ?>' + '/mahasiswa/get/' + id_mahasiswa,
      type: 'GET',
      dataType: 'JSON',
      success: function(data) {
        if (data.status != 'success') {
          showSWAL('error', data.message);
        } else {
          // clear form
          $('#id_mahasiswa').val('');
          $('#update_nim').val('');
          $('#update_nama_mahasiswa').val('');
          // fill data
          $('#id_mahasiswa').val(data.data.id_mahasiswa);
          $('#update_nim').val(data.data.nim);
          $('#update_nama_mahasiswa').val(data.data.nama_mahasiswa);
          $(`#update_progdi_id option[value="${data.data.progdi_id}"]`).prop('selected', true);
          $(`#update_angkatan_id option[value="${data.data.angkatan_id}"]`).prop('selected', true);
        }
      },
      error: function(jqXHR) {
        showSWAL('error', jqXHR);
      }
    });
  }

  function updateMahasiswa() {
    var data_mahasiswa = {
      nim: $('#update_nim').val(),
      nama_mahasiswa: $('#update_nama_mahasiswa').val(),
      progdi_id: parseInt($('#update_progdi_id').val()),
      angkatan_id: parseInt($('#update_angkatan_id').val()),
    };

    console.log(data_mahasiswa);
    $.ajax({
      url: '<?php base_url() ?>' + '/mahasiswa/update/' + $('#id_mahasiswa').val(),
      type: 'POST',
      data: data_mahasiswa,
      dataType: 'JSON',
      success: function(data) {
        console.log(data);
        if (data.status != 'success') {
          showSWAL('error', data.message);
        } else {
          showSWAL('success', data.message);
        }
      },
      error: function(jqXHR) {
        showSWAL('error', jqXHR);
      }
    });
  }

  function deleteMahasiswa(id_mahasiswa) {
    Swal.fire({
      title: 'Apakah Anda yakin ingin menghapus data ini?',
      text: "Tindakan ini tidak dapat dikembalikan!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Hapus'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '<?php base_url() ?>' + '/mahasiswa/delete/' + id_mahasiswa,
          type: 'DELETE',
          dataType: 'JSON',
          success: function(data) {
            if (data.status != 'success') {
              showSWAL('error', data.message);
            } else {
              showSWAL('success', data.message);
            }
          },
          error: function(jqXHR) {
            showSWAL('error', jqXHR);
          }
        });
      }
    });
  }

  function uploadImportFile() {
    var formData = new FormData();
    formData.append('file', $('#file_import')[0].files[0]);

    $.ajax({
      url: '<?php base_url() ?>'+ '/master-mahasiswa/import/upload',
      type: 'POST',
      data: formData,
      processData: false, // tell jQuery not to process the data
      contentType: 'multipart/form-data',
      dataType: 'JSON',
      success: function(data) {
        console.log(data);
      },
      error: function(jqXHR){
        console.log(jqXHR);
      }
    });
  }
</script>
<?= $this->endSection() ?>