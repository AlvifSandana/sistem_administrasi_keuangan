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
<!-- Modals -->
<?= $this->include('pages/master/mahasiswa/modaladdmahasiswa') ?>
<?= $this->include('pages/master/mahasiswa/modalupdatemahasiswa') ?>
<!-- ./Modals -->
<?= $this->endSection() ?>

<?= $this->section('custom-script') ?>
<script>
  $('.customselect').select2({
    width: 'resolve',
  });

  function createMahasiswa() {
    var today = new Date();
    var data_mahasiswa = {
      nim: $('#nim').val(),
      nama_mahasiswa: $('#nama_mahasiswa').val(),
      progdi_id: parseInt($('#progdi_id').val()),
      angkatan_id: parseInt($('#angkatan_id').val()),
      paket_tagihan: $('#paket_tagihan').val(),
      tanggal_tagihan: `${today.getFullYear()}-${today.getMonth()}-${today.getDate()}`,
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