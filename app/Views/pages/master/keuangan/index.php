<?= $this->extend('layout/master') ?>

<?= $this->section('content-header') ?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Master Keuangan</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Master</a></li>
                    <li class="breadcrumb-item active">Keuangan</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<?= $this->endSection() ?>

<?= $this->section('content-body') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="h5 mb-4">Data Paket Tagihan <button class="btn btn-success float-right" data-toggle="modal" data-target="#modalAddPaket"><i class="fas fa-plus"></i> Tambah Paket</button></h5>
                        <select name="paket" class="form-control" id="select_paket" onchange="getItemPaket()" onload="getItemPaket()">
                            <?php
                            foreach ($data_paket as $p) {
                                if ($p['id_paket'] == 1) {
                                    echo '<option value="' . $p['id_paket'] . '" selected>' . $p['nama_paket'] . '</option>';
                                } else {
                                    echo '<option value="' . $p['id_paket'] . '">' . $p['nama_paket'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <h5 class="h5 mt-3 mb-4">Detail Item Paket <button class="btn btn-success float-right" data-toggle="modal" data-target="#modalAddItemPaket"><i class="fas fa-plus"></i> Tambah Item Tagihan</button></h5>
                        <table class="table table-bordered tbl_master_paket" id="tbl_master_paket">
                            <thead class="text-center">
                                <th>ID Item</th>
                                <th>Nama Item Tagihan</th>
                                <th>Nominal</th>
                                <th>Keterangan</th>
                                <th>Action</th>
                            </thead>
                            <tbody class="text-center">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Add Paket -->
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modalAddPaket" aria-labelledby="modalAddPaket" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Paket Tagihan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_paket">NAMA PAKET</label>
                        <input type="text" name="add_nama_paket" id="add_nama_paket" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="semester">SEMESTER</label>
                        <select name="add_semester_id" id="add_semester_id" class="form-control">
                            <?php
                            foreach ($data_semester as $s) {
                                echo '<option value="' . $s['id_semester'] . '">' . $s['nama_semester'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="keterangan_paket">KETERANGAN</label>
                        <textarea name="add_keterangan_paket" id="add_keterangan_paket" cols="30" rows="4" class="form-control"></textarea>
                    </div>
                    <button class="btn btn-success float-right" onclick="addPaket()">Tambah</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Add Pembayaran -->
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modalAddItemPaket" aria-labelledby="modalAddItemPaket" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Item Tagihan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="paket_id">PAKET</label>
                        <select name="paket_id" id="paket_id" class="form-control">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="dp_nama_item">NAMA ITEM</label>
                        <input type="text" name="nama_item" id="nama_item" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="nominal_item">NOMINAL</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp. </span>
                            </div>
                            <input type="number" class="form-control" name="nominal_item" id="nominal_item" aria-label="NOMINAL ITEM">
                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label for="keterangan_item">KETERANGAN</label>
                        <textarea class="form-control" name="keterangan_item" id="keterangan_item" cols="30" rows="4"></textarea>
                    </div>
                    <button class="btn btn-success float-right mb-4" onclick="addItemPaket()">Tambah</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Edit Pembayaran -->
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modalEditItemPaket" aria-labelledby="modalEditItemPaket" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Item Tagihan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="number" name="edit_paket_id" id="edit_paket_id" hidden disabled>
                    <input type="number" name="edit_id_item" id="edit_id_item" hidden disabled>
                    <div class="form-group">
                        <label for="dp_nama_item">NAMA ITEM</label>
                        <input type="text" name="nama_item" id="edit_nama_item" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="nominal_item">NOMINAL</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp. </span>
                            </div>
                            <input type="number" class="form-control" name="edit_nominal_item" id="edit_nominal_item" aria-label="NOMINAL ITEM">
                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label for="keterangan_item">KETERANGAN</label>
                        <textarea class="form-control" name="keterangan_item" id="edit_keterangan_item" cols="30" rows="4"></textarea>
                    </div>
                    <button class="btn btn-warning float-right mb-4" onclick="updateItemPaket()">Perbarui</button>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('custom-script') ?>
<script>
    // get data on selected paket tagihan
    function getItemPaket() {
        $("#tbl_master_paket > tbody").empty();
        $.ajax({
            url: "<?php base_url() ?>" + "/itempaket/" + $('select#select_paket').children('option:selected').val(),
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                var response = data;
                if (response.status != "success") {
                    showSWAL('error', data.message);
                } else {
                    var row_item_tagihan = "";
                    var total_tagihan = 0;
                    var numformat = Intl.NumberFormat();
                    for (let index = 0; index < response.data.length; index++) {
                        row_item_tagihan += `<tr>
              <td>${response.data[index].id_item}</td>
              <td>${response.data[index].nama_item}</td>
              <td class="text-left">Rp. ${numformat.format(parseInt(response.data[index].nominal_item))}</td>
              <td>${response.data[index].keterangan_item}</td>
              <td>
                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalEditItemPaket" onclick="getItemPaketById(${response.data[index].id_item})"><i class="far fa-edit"></i></button>
                <button class="btn btn-sm btn-danger" onclick="deleteItemPaket(${response.data[index].id_item})"><i class="fas fa-trash"></i></button>
              </td>
              </tr>`;
                        total_tagihan += parseInt(response.data[index].nominal_item);
                    }
                    Intl
                    $("#tbl_master_paket > tbody").append(row_item_tagihan);
                    $("#tbl_master_paket > tbody").append(`<tr class="font-weight-bold"><td colspan="3">Total Tagihan</td><td colspan="2">Rp. ${numformat.format(total_tagihan)}</td></tr>`);
                }
            },
            error: function(jqXHR) {
                showSWAL('error', jqXHR);
            }
        });
    }

    /** 
     * get paket
     */
    function getPaket() {
        $.ajax({
            url: '<?php base_url() ?>' + '/paket/all',
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                var paket = ``;
                for (let index = 0; index < data.data.paket.length; index++) {
                    paket += `<option value="${data.data.paket[index].id_paket}">${data.data.paket[index].nama_paket}</option>`;
                }
                $('#paket_id').append(paket);
            },
            error: function(jqXHR) {
                showSWAL('error', jqXHR);
            }
        });
    }
    getPaket();

    /** 
     * get item paket berdasarkan id_item
     */
    function getItemPaketById(id_item) {
        $.ajax({
            url: '<?php base_url() ?>' + '/itempaket/find/' + id_item,
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                if (data.status != 'success') {
                    showSWAL('error', data.message);
                } else {
                    // clean input
                    $('#edit_id_item').val(0);
                    $('#edit_paket_id').val(0);
                    $('#edit_nama_item').val('');
                    $('#edit_nominal_item').val(0);
                    $('#edit_keterangan_item').val('data.data.keterangan_item');
                    // fill with data
                    $('#edit_id_item').val(data.data.id_item);
                    $('#edit_paket_id').val(data.data.paket_id);
                    $('#edit_nama_item').val(data.data.nama_item);
                    $('#edit_nominal_item').val(parseInt(data.data.nominal_item));
                    $('#edit_keterangan_item').val(data.data.keterangan_item);
                }
            },
            error: function(jqXHR) {
                showSWAL('error', jqXHR);
            }
        });
    }

    /** 
     * tambah paket tagihan
     */
    function addPaket() {
        var data_paket = {
            nama_paket: $('#add_nama_paket').val(),
            keterangan_paket: $('#add_keterangan_paket').val(),
            semester_id: parseInt($('#add_semester_id').val()),
        };
        $.ajax({
            url: '<?php base_url() ?>' + '/paket/create',
            type: 'POST',
            data: data_paket,
            dataType: 'JSON',
            success: function(data) {
                if (data.status != 'success') {
                    showSWAL('error', data.message);
                } else {
                    showSWAL('success', data.message);
                    $('#add_nama_paket').val('');
                    $('#add_keterangan_paket').val('');
                    window.location.reload();
                }
            },
            error: function(jqXHR) {
                showSWAL('error', jqXHR)
            }
        });
    }

    /** 
     * tambah item paket
     */
    function addItemPaket() {
        var data_item = {
            paket_id: parseInt($('#paket_id').val()),
            nama_item: $('#nama_item').val(),
            nominal_item: parseInt($('#nominal_item').val()),
            keterangan_item: $('#keterangan_item').val(),
        };
        $.ajax({
            url: '<?php base_url() ?>' + '/itempaket/create',
            type: 'POST',
            data: data_item,
            dataType: 'JSON',
            success: function(data) {
                if (data.status != 'success') {
                    showSWAL('error', data.message);
                } else {
                    showSWAL('success', data.message);
                    $("#tbl_master_paket > tbody").empty();
                    getItemPaket();
                }
            },
            error: function(jqXHR) {
                showSWAL('error', jqXHR);
            }
        });
    }

    /** 
     * hapus item paket
     */
    function deleteItemPaket(id_item) {
        Swal.fire({
            title: 'Apakah Anda yakin ingin menghapus item ini?',
            text: "Tindakan ini tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?php base_url() ?>' + '/itempaket/delete/' + id_item,
                    type: 'DELETE',
                    dataType: 'JSON',
                    success: function(data) {
                        if (data.status != 'success') {
                            showSWAL('error', data.message);
                        } else {
                            showSWAL('success', data.message);
                            $("#tbl_master_paket > tbody").empty();
                            getItemPaket();
                        }
                    },
                    error: function(jqXHR) {
                        showSWAL('error', jqXHR);
                    }
                });
            }
        });
    }

    /** 
     * update item paket
     */
    function updateItemPaket() {
        var data_item = {
            id_item: parseInt($('#edit_id_item').val()),
            paket_id: parseInt($('#edit_paket_id').val()),
            nama_item: $('#edit_nama_item').val(),
            nominal_item: parseInt($('#edit_nominal_item').val()),
            keterangan_item: $('#edit_keterangan_item').val(),
        };
        $.ajax({
            url: '<?php base_url() ?>' + '/itempaket/update/' + data_item.id_item,
            type: 'POST',
            data: data_item,
            dataType: 'JSON',
            success: function(data) {
                if (data.status != 'success') {
                    showSWAL('error', data.message);
                } else {
                    showSWAL('success', data.message);
                    $("#tbl_master_paket > tbody").empty();
                    getItemPaket();
                }
            },
            error: function(jqXHR) {
                showSWAL('error', jqXHR);
            }
        });
    }
</script>
<?= $this->endSection() ?>