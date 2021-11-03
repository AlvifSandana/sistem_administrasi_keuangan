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
            url: '<?php base_url() ?>' + '/master-mahasiswa/import/upload',
            type: 'POST',
            data: formData,
            processData: false, // tell jQuery not to process the data
            contentType: 'multipart/form-data',
            dataType: 'JSON',
            success: function(data) {
                console.log(data);
            },
            error: function(jqXHR) {
                console.log(jqXHR);
            }
        });
    }
</script>