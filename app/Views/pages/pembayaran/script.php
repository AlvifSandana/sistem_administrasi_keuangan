<script>
  var num_format = Intl.NumberFormat();
  var item_tagihan_terbayar = [];
  var nama_paket = "";
  /**
   * search pembayaran by nim
   * and show result table
   */
  function searchPembayaran() {
    // change visibility
    $("#card_detail_tagihan").css("visibility", "hidden");
    $("#card_detail_pembayaran").css("visibility", "hidden");
    $("#tbl_detail_tagihan > tbody").empty();
    $("#tbl_detail_pembayaran > tbody").empty();
    $("#mahasiswa_id").val(0);
    $("#paket_id").val(0);
    var nim = $("#nim").val();
    var pembayaran_row = ``;
    $.ajax({
      url: "<?php site_url() ?>" + "/pembayaran/search/" + nim,
      type: "GET",
      dataType: "JSON",
      success: function(data) {
        if (data == null || data.data.mahasiswa == null) {
          showSWAL('error', 'Data tidak ditemukan!');
        } else {
          // call fillDetail() function 
          fillDetail();
          // kosongkan tbody
          $("#list_search_result").empty();
          // create new data row
          var row = `
            <tr>
              <td>${data.data.mahasiswa.id_mahasiswa}</td>
              <td>${data.data.mahasiswa.nim}</td>
              <td>${data.data.mahasiswa.nama_mahasiswa}</td>
              <td>
                <button class="btn btn-primary btn-sm" onclick="showDetail()" data-toggle="tooltip" data-placement="top" title="Lihat Detail"><i class="fas fa-info"></i></button>
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalCreatePembayaran"><i class="fas fa-plus"></i></button>
              </td>
            </tr>
            </tr>
          `;
          // fill id_mahasiswa to modal create pembayaran
          $("#mahasiswa_id").val(data.data.mahasiswa.id_mahasiswa);
          // show table with data row
          $("#search_result").css("visibility", "visible");
          $("#search_result").addClass("animate__animated animate__fadeIn")
          $("#list_search_result").append(row);
        }
      },
      error: function(jqXHR) {
        console.log(jqXHR);
        showSWAL('error', 'Data tidak ditemukan!');
      }
    });
  }

  /**
   * fill detail of tagihan mahasiswa
   */
  function fillDetail() {
    try {
      // declare data row
      var tagihan_row = ``;
      var pembayaran_row = ``;
      var total_tagihan = 0;
      var total_pembayaran = 0;
      // get data tagihan by nim
      $.ajax({
        url: "<?php site_url() ?>" + "/tagihan/search/" + $("#nim").val(),
        type: "GET",
        dataType: "JSON",
        success: function(data) {
          $("#item_id").empty();
          // console.log(data);
          item_tagihan_terbayar = data.data.item_paket_terbayar;
          // iterate response data and fill to the
          // count total_tagihan
          for (let index = 0; index < data.data.item_paket.length; index++) {
            tagihan_row += `
            <tr>
              <td>${data.data.item_paket[index].id_item}</td>
              <td>${data.data.item_paket[index].nama_item}</td>
              <td class="text-left">Rp ${num_format.format(parseInt(data.data.item_paket[index].nominal_item))}</td>
            </tr>`;
            pembayaran_row += `<tr><td>${data.data.item_paket[index].nama_item}</td>`;
            var nominal_item_terbayar = 0;
            for (let index1 = 0; index1 < data.data.item_paket_terbayar.length; index1++) {
              if (data.data.item_paket[index].id_item == data.data.item_paket_terbayar[index1].item_id) {
                nominal_item_terbayar += parseInt(data.data.item_paket_terbayar[index1].nominal_pembayaran);
              }
            }
            pembayaran_row += `
            <td>Rp ${num_format.format(nominal_item_terbayar)}</td>
            <td>
              <button class="btn btn-sm btn-primary" ttile="Detail" data-toggle="modal" data-target="#modalDetailPembayaranPerItem" onclick="showDetailPembayaranItem(${data.data.item_paket[index].id_item}, '${data.data.item_paket[index].nama_item}')">
                <i class="fas fa-info"></i>
              </button>
            </td></tr>`;
            total_tagihan += parseInt(data.data.item_paket[index].nominal_item);
            total_pembayaran += parseInt(nominal_item_terbayar);
            $("#item_id").append(`<option value="${data.data.item_paket[index].id_item}">${data.data.item_paket[index].nama_item} - Rp. ${data.data.item_paket[index].nominal_item}</option>`)
          }
          var row_total = `<tr class="font-weight-bold">
          <td colspan="2">Total Tagihan</td>
          <td>Rp ${num_format.format(total_tagihan)}</td>
          `;
          var row_total_pembayaran = `<tr class="font-weight-bold">
          <td>Total Terbayar</td>
          <td colspan="2">Rp ${num_format.format(total_pembayaran)}</td>
          `;
          // fill paket_id to modalCreatePembayaran
          $("#paket_id").val(data.data.detail_paket.id_paket);
          // append table
          $("#tbl_detail_tagihan > tbody").append(tagihan_row);
          $("#tbl_detail_tagihan > tbody").append(row_total);
          $("#tbl_detail_pembayaran > tbody").append(pembayaran_row);
          $("#tbl_detail_pembayaran > tbody").append(row_total_pembayaran);
        },
        error: function(jqXHR) {
          alert('Error!');
          console.log(jqXHR);
        }
      });
    } catch (error) {
      console.log(error);
      showSWAL('error', error);
    }
  }

  /**
   * create a new pembayaran
   */
  function createPembayaran() {
    $("#btn_tambah_pembayaran").prop('disabled', true);
    $("#btn_tambah_pembayaran").html(`<div class="spinner-border text-light spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>`);
    var mydata = {
      item_id: $("#item_id").val(),
      paket_id: $("#paket_id").val(),
      mahasiswa_id: $("#mahasiswa_id").val(),
      tanggal_pembayaran: $("#tanggal_pembayaran").val(),
      nominal_pembayaran: $("#nominal_pembayaran").val(),
      user_id: 1
    }
    $.ajax({
      url: "<?php base_url() ?>/pembayaran/create",
      type: "POST",
      data: mydata,
      dataType: 'JSON',
      success: function(res) {
        var response = res;
        $("#btn_tambah_pembayaran").prop('disabled', false);
        $("#btn_tambah_pembayaran").html('Tambah Pembayaran');
        showSWAL(response.status, response.message);
        searchPembayaran();
      },
      error: function(jqXHR) {
        console.log(jqXHR)
        $("#btn_tambah_pembayaran").prop('disabled', false);
        $("#btn_tambah_pembayaran").html('Tambah Pembayaran');
        showSWAL('error', jqXHR);
      }
    });
  }

  /**
   * show detail card
   */
  function showDetail() {
    // change visibility
    $("#card_detail_tagihan").css("visibility", "visible");
    $("#card_detail_pembayaran").css("visibility", "visible");
  }

  /**
   * show modal detail pembayaran from item
   */
  function showDetailPembayaranItem(item_id, nama_item) {
    var row_pembayaran = ``;
    $("#tbl_detail_pembayaran_per_item > tbody").empty();
    $("#dp_nama_item").val(nama_item);
    for (let index = 0; index < item_tagihan_terbayar.length; index++) {
      if (item_tagihan_terbayar[index].item_id == item_id) {
        row_pembayaran += `
        <tr>
        <td>${item_tagihan_terbayar[index].id_pembayaran}</td>
        <td>${Intl.DateTimeFormat('id-id', {dateStyle: 'full'}).format(Date.parse(item_tagihan_terbayar[index].tanggal_pembayaran))}</td>
        <td>Rp ${num_format.format(parseInt(item_tagihan_terbayar[index].nominal_pembayaran))}</td>
        </tr>`;
      }
    }
    $("#tbl_detail_pembayaran_per_item > tbody").append(row_pembayaran);
  }
</script>