<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\ItemPaketModel;
use App\Models\MahasiswaModel;
use App\Models\PaketModel;
use App\Models\PembayaranModel;
use App\Models\SemesterModel;
use App\Models\TagihanModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanController extends BaseController
{
    public function index()
    {
        //
    }

    /**
     * Create laporan tagihan mahasiswa
     * by NIM
     */
    public function generate_laporan_tagihan($nim)
    {
        helper(['url']);
        // total tagihan & sisa tagihan
        $total_tagihan = 0;
        $total_pembayaran = 0;
        $sisa_tagihan = 0;
        $col = 5;
        $result['data'] = [];
        // create model instance
        $m_mahasiswa = new MahasiswaModel();
        $m_paket = new PaketModel();
        $m_semester = new SemesterModel();
        $m_tagihan = new TagihanModel();
        $m_itemtagihan = new ItemPaketModel();
        $m_pembayaran = new PembayaranModel();
        // get mahasiswa by id
        $mahasiswa = $m_mahasiswa->where('nim', $nim)->first();
        if ($mahasiswa) {
            // get tagihan by mahasiswa_id
            $tagihan = $m_tagihan->where('mahasiswa_id', $mahasiswa['id_mahasiswa'])->findAll();
            if (count($tagihan) > 0) {
                // create spreadsheet instance
                $spreadsheet = new Spreadsheet();
                // create title
                $spreadsheet
                    ->setActiveSheetIndex(0)
                    ->setCellValue('B1', 'LAPORAN TAGIHAN MAHASISWA')
                    ->setCellValue('A2', 'NIM')
                    ->setCellValue('A3', 'NAMA')
                    ->setCellValue('B2', ':')
                    ->setCellValue('B3', ':')
                    ->setCellValue('C2', $mahasiswa['nim'])
                    ->setCellValue('C3', $mahasiswa['nama_mahasiswa'])
                    ->setCellValue('A4', 'SEMESTER')
                    ->setCellValue('B4', 'TOTAL TAGIHAN')
                    ->setCellValue('C4', 'SISA TAGIHAN');
                // iterate all tagihan
                foreach ($tagihan as $t) {
                    // get paket tagihan
                    $paket = $m_paket->where('id_paket', $t['paket_id'])->first();
                    // get semester
                    $semester = $m_semester->where('id_semester', $paket['semester_id'])->first();
                    // get item paket tagihan
                    $item_tagihan = $m_itemtagihan->where('paket_id', $t['paket_id'])->findAll();
                    // count total tagihan 
                    foreach ($item_tagihan as $it) {
                        $total_tagihan += $it['nominal_item'];
                        // get pembayaran by item tagihan, paket_id, mahasiswa_id
                        $pembayaran = $m_pembayaran
                            ->where('mahasiswa_id', $mahasiswa['id_mahasiswa'])
                            ->where('paket_id', $t['paket_id'])
                            ->where('item_id', $it['id_item'])
                            ->findAll();
                        // count total pembayaran
                        if (count($pembayaran) > 0) {
                            foreach ($pembayaran as $p) {
                                $total_pembayaran += $p['nominal_pembayaran'];
                            }
                        }
                    }
                    // create table value
                    $spreadsheet
                        ->setActiveSheetIndex(0)
                        ->setCellValue('A' . $col, $semester['nama_semester'])
                        ->setCellValue('B' . $col, $total_tagihan)
                        ->setCellValue('C' . $col, ($total_tagihan - $total_pembayaran));
                    array_push($result['data'], [
                        'nim' => $mahasiswa['nim'],
                        'nama' => $mahasiswa['nama_mahasiswa'],
                        'semester' => $semester['nama_semester'],
                        'tagihan' => $total_tagihan,
                        'sisa' => ($total_tagihan - $total_pembayaran)
                    ]);

                    $col++;
                    $total_tagihan = 0;
                    $total_pembayaran = 0;
                }
            } else {
                return redirect()->back()->with('error', 'Data tagihan tidak tersedia!');
            }
        } else {
            return redirect()->back()->with('error', 'Data tagihan tidak tersedia!');
        }
        // return json_encode($result);
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=data.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    /**
     * Create laporan tagihan seluruh mahasiswa
     */
    public function generate_laporan_tagihan_all_mhs()
    {
        # code...
    }
}
