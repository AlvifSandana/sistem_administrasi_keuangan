<?= $this->extend('layout/master') ?>

<?= $this->section('content-header') ?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Backup - Restore</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Master</li>
                    <li class="breadcrumb-item active">Backup - Restore</li>
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
        <?= $this->include('layout/flash') ?>
        <div class="row mb-2">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h4 class="h4">Backup Database</h4>
                        <p>Tekan tombol berikut untuk melakukan <b class="text-success">Backup</b> Database.
                            <a class="btn btn-success float-right" href="/backup-restore/backup"><i class="fas fa-undo"></i> Backup</a>
                            <br>
                            Jika proses backup berhasil, silahkan klik link <b class="text-success">Download</b> pada pojok kanan atas.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h4 class="h4">Restore Database</h4>
                        <p>Pilih file .sql lalu tekan tombol <b class="text-primary">Restore</b>.</p>
                        <form action="<?php base_url() ?>/backup-restore/restore" method="post" enctype="multipart/form-data">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="file_restore" id="file_restore" accept=".sql">
                                    <label class="custom-file-label" for="file_restore">Choose file (.sql)</label>
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"><i class="fas fa-redo"></i> Restore</button>
                                </div>
                            </div>
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
    // show upload filename
    $('#file_restore').on('change', function() {
        var filename = $(this).val();
        $(this).next('.custom-file-label').html(filename);
    });
</script>
<?= $this->endSection() ?>