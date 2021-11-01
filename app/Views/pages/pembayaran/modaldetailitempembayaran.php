<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modalDetailPembayaranPerItem" aria-labelledby="modalDetailPembayaranPerItem" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Pembayaran</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="">
          <div class="form-group">
            <label for="dp_nama_item">NAMA ITEM</label>
            <input type="text" name="dp_nama_item" id="dp_nama_item" class="form-control" disabled>
          </div>
          <table class="table" id="tbl_detail_pembayaran_per_item">
            <thead class="text-center">
              <th>ID Pembayaran</th>
              <th>Tanggal Pembayaran</th>
              <th>Nominal Pembayaran</th>
            </thead>
            <tbody class="text-center"></tbody>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>