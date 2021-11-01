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