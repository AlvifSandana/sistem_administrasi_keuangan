<?= $this->extend('layout/master') ?>

<?= $this->section('content-header') ?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Account Settings</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item">Settings</li>
                    <li class="breadcrumb-item active">Account Settings</li>
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
                        <h4 class="h4">Create New User</h4>
                        <p>Membuat data user baru dengan atribut <b>Nama</b>, <b>Username</b>, <b>Email</b>, dan <b>Password</b> <br>
                            <button class="btn btn-primary float-right" data-toggle="modal" data-target="#modalCreateUser"><i class="fas fa-user-plus"></i> Create</button>
                            dengan mengklik tombol <b class="text-primary">Create</b> berikut.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h4 class="h4">Update User Details</h4>
                        <p>Perbarui data user saat ini (signed in) seperti <b>Nama</b>, <b>Username</b>, <b>Email</b>, dan <b>Password</b> <br>
                            <button class="btn btn-warning float-right" data-toggle="modal" data-target="#modalUpdateUser"><i class="fas fa-sync-alt"></i> Update</button>
                            dengan mengklik tombol <b class="text-warning">Update</b> berikut.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Modals -->
<?= $this->include('pages/settings/account/modal/modal_create_user') ?>
<?= $this->include('pages/settings/account/modal/modal_update_current_user') ?>
<!-- /Modals -->
<?= $this->endSection() ?>

<?= $this->section('custom-script') ?>
<?= $this->include('pages/settings/account/script') ?>
<?= $this->endSection() ?>