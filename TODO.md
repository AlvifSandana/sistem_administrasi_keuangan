# TODO

### List pengerjaan:
- [x] Otomatis menambahkan nama semester ketika menambahkan tagihan baru mahasiswa.
- [x] Perbarui form **Tambah Data Mahasiswa** (fitur tambahkan ke paket tagihan).
- [x] Print laporan keuangan.
- [x] Print Bukti pembayaran & tagihan
- [x] Fix import data dari ***.xlsx** (pembatasan insert new tagihan sesuai progdi => jika S1 maka generate tagihan hingga semester 8, dst.)
- [x] Mengerjakan fitur export data ke **.sql** dan **.xlsx**
- [x] Halaman settings (konsep)
- [x] Modul Master data pendukung
- [x] Event onKeyPress enter untuk pencarian tagihan dan pembayaran
- [x] Show total tagihan dari semua semester
- [x] Fix pembayaran (jika pembayaran melebihi nominal item tagihan, pembayaran ditolak)
- [x] Fix pembayaran (jika total pembayaran yang telah direcord >= total tagihan, set status tagihan menjadi lunas)
- [x] (optional) tambah dokumen bukti pembayaran
- [x] indikator dokumen bukti pembayaran pada setiap item pembayaran
- [x] delete pembayaran
- [x] create akun demo
- [x] set priviledge for menu by akun type
- [x] fix on delete cascade for tagihan & pembayaran table with user_id