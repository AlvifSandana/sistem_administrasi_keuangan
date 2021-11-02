<script>
  // global data
  let data_mhs;
  let data_tagihan;
  // number format
  var numFormat = Intl.NumberFormat();

  /** 
   * function pencarian data tagihan mahasiswa
   * berdasarkan NIM
   */
  function searchMhs() {
    var nim = $("#nim").val();
    $.ajax({
      url: "<?php site_url() ?>" + "/tagihan/search/" + nim,
      type: "GET",
      dataType: "JSON",
      success: function(data) {
        if (data == null || data.status == "failed") {
          showSWAL('error', data.message);
        } else {
          $('.detail-tagihan').empty();
          $('.hasil').css('visibility', 'hidden');
          showAfterSearch(data.data.detail_mahasiswa.id_mahasiswa, data.data.detail_mahasiswa.nim, data.data.detail_mahasiswa.nama_mahasiswa);
          // data detail_tagihan    
          var detail_tagihan = data.data.detail_tagihan;
          data_tagihan = detail_tagihan;
          data_mhs = data.data.detail_mahasiswa;
          // total tagihan & total terbayar
          var global_tagihan = 0;
          var global_terbayar = 0;
          var total_tagihan = 0;
          var total_terbayar = 0;
          var tmp_terbayar = 0;
          var new_tagihan = ``;
          var new_row = ``;
          // iterate data.detail_tagihan
          for (let index = 0; index < detail_tagihan.length; index++) {
            // iterate item_paket tagihan
            for (let i = 0; i < detail_tagihan[index].detail_item_paket.length; i++) {
              total_tagihan += parseInt(detail_tagihan[index].detail_item_paket[i].nominal_item);
              // iterate detail pembayaran per item_paket
              for (let j = 0; j < detail_tagihan[index].detail_item_paket[i].detail_pembayaran.length; j++) {
                tmp_terbayar += parseInt(detail_tagihan[index].detail_item_paket[i].detail_pembayaran[j].nominal_pembayaran);
              }
              new_row += `
              <tr>
                <td>${detail_tagihan[index].detail_item_paket[i].nama_item}</td>
                <td>Rp ${numFormat.format(parseInt(detail_tagihan[index].detail_item_paket[i].nominal_item))}</td>
                <td>RP ${numFormat.format(tmp_terbayar)}</td>
                <td class="text-center"><button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalDetailTagihan" onclick="showDetailPembayaran(${index}, ${i})"><i class="fas fa-info"></i></button></td>
              </tr>
              `;
              total_terbayar += tmp_terbayar;
              tmp_terbayar = 0;
            }
            new_tagihan += `
            <div class="tagihan-${index} mb-3">
              <h5 class="h5">Tagihan ${detail_tagihan[index].detail_paket[0].nama_paket} <span class="float-right badge badge-${detail_tagihan[index].status_tagihan.toLowerCase() == 'lunas' ? 'success' : 'warning'}">${detail_tagihan[index].status_tagihan}</span></h5>
              <table class="table table-hover table-bordered">
                <thead class="text-center">
                  <th>ITEM TAGIHAN</th>
                  <th>NOMINAL</th>
                  <th>TERBAYAR</th>
                  <th>ACTION</th>
                </thead>
                <tbody>
                  ${new_row}
                  <tr class="font-weight-bold">
                    <td>TOTAL</td>
                    <td>Rp ${numFormat.format(total_tagihan)}</td>
                    <td>Rp ${numFormat.format(total_terbayar)}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <hr/>`;
            $('.detail-tagihan').append(new_tagihan);
            new_row = ``;
            new_tagihan = ``;
            global_tagihan += total_tagihan;
            global_terbayar += total_terbayar;
            total_tagihan = 0;
            total_terbayar = 0;
          }
        }
      },
      error: function(jqXHR) {
        console.log(jqXHR);
        showSWAL('error', jqXHR);
      }
    });
  }

  /** 
   * function untuk menampilkan card hasil 
   * pencarian data mahasiswa
   */
  function showAfterSearch(id, nim, nama) {
    $('#list_tagihan').empty();
    var new_row = `
    <tr>
      <td>${id}</td>
      <td>${nim}</td>
      <td>${nama}</td>
      <td><button class="btn btn-primary btn-sm" onclick="$('.hasil').css('visibility', 'visible')"><i class="fas fa-info"></i></button></td>
    </tr>`;
    $('#list_tagihan').append(new_row);
    $('#search_result').css('visibility', 'visible');
  }

  /** 
   * function untuk menampilkan 
   * modal detail tagihan per item paket
   */
  function showDetailPembayaran(idx_detail_tagihan, idx_detail_item_paket) {
    var pembayaran_row = ``;
    // clear field
    $('#detail_nim').val('');
    $('#detail_nama_mhs').val('');
    $('#detail_nama_paket').val('');
    $('#list_item_pembayaran').empty();
    // set field value
    $('#detail_nim').val(data_mhs.nim);
    $('#detail_nama_mhs').val(data_mhs.nama_mahasiswa);
    $('#detail_nama_paket').val(data_tagihan[idx_detail_tagihan].detail_paket[0].nama_paket);
    $('#detail_nama_item_paket').val(data_tagihan[idx_detail_tagihan].detail_item_paket[idx_detail_item_paket].nama_item);
    // iterate pembayaran
    for (let i = 0; i < data_tagihan[idx_detail_tagihan].detail_item_paket[idx_detail_item_paket].detail_pembayaran.length; i++) {
      var tanggal_pembayaran = data_tagihan[idx_detail_tagihan].detail_item_paket[idx_detail_item_paket].detail_pembayaran[i].tanggal_pembayaran;
      pembayaran_row += `
      <tr>
        <td>${Intl.DateTimeFormat('id-id', {dateStyle: 'full'}).format(Date.parse(tanggal_pembayaran))}</td>
        <td>${data_tagihan[idx_detail_tagihan].detail_item_paket[idx_detail_item_paket].detail_pembayaran[i].nominal_pembayaran}</td>
        <td>${data_tagihan[idx_detail_tagihan].detail_item_paket[idx_detail_item_paket].detail_pembayaran[i].keterangan_pembayaran}</td>
      </tr>
      `;
    }
    $('#list_item_pembayaran').append(pembayaran_row);
  }
</script>