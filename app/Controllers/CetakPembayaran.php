<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ItemPaketModel;
use App\Models\MahasiswaModel;
use App\Models\PaketModel;
use App\Models\PembayaranModel;
use App\Models\SemesterModel;
use App\Models\TagihanModel;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CetakPembayaran extends BaseController
{
    public function index()
    {
        //
    }

    /**
     * Cetak detail pembayaran by NIM
     */
    public function byNIM($nim)
    {
        try {
            // total tagihan & sisa tagihan
            $total_tagihan = 0;
            $sisa_tagihan = 0;
            $col = 7;
            // create model instance
            $m_mahasiswa = new MahasiswaModel();
            $m_paket = new PaketModel();
            $m_semester = new SemesterModel();
            $m_tagihan = new TagihanModel();
            $m_itemtagihan = new ItemPaketModel();
            $m_pembayaran = new PembayaranModel();
            // get mahasiswa by nim
            $mahasiswa = $m_mahasiswa->where('nim', $nim)->first();
            if ($mahasiswa) {
                // get tagihan by mahasiswa_id
                $tagihan = $m_tagihan->where('mahasiswa_id', $mahasiswa['id_mahasiswa'])->findAll();
                if (count($tagihan) > 0) {
                    // create spreadsheet instance
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                    $spreadsheet = $reader->load(WRITEPATH . 'templates/template_cetak_pembayaran_by_mhs.xlsx');
                    // set spreadsheet styles
                    $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
                    $spreadsheet->getDefaultStyle()->getFont()->setSize(12);
                    // set NIM and Nama Mahasiswa
                    $spreadsheet
                        ->setActiveSheetIndex(0)
                        ->setCellValue('B3', $mahasiswa['nim'])
                        ->setCellValue('B4', $mahasiswa['nama_mahasiswa']);
                    // iterate all tagihan
                    foreach ($tagihan as $t) {
                        // get paket tagihan by paket_id
                        $paket = $m_paket->where('id_paket', $t['paket_id'])->first();
                        // write nama paket pembayaran
                        $spreadsheet
                            ->setActiveSheetIndex(0)
                            ->setCellValue('A' . $col, $paket['nama_paket']);
                        // get item tagihan
                        $item_tagihan = $m_itemtagihan->where('paket_id', $t['paket_id'])->findAll();
                        foreach ($item_tagihan as $it) {
                            $tmp_total_pembayaran = 0;
                            $tmp_nominal_tagihan = 0;
                            // write item name & nominal tagihan
                            $spreadsheet
                                ->setActiveSheetIndex(0)
                                ->setCellValue('B' . $col, $it['nama_item'])
                                ->setCellValue('C' . $col, $it['nominal_item']);
                            $tmp_nominal_tagihan += $it['nominal_item'];
                            // get pembayaran by id item, paket_id, mahasiswa_id
                            $pembayaran = $m_pembayaran
                                ->where('mahasiswa_id', $mahasiswa['id_mahasiswa'])
                                ->where('paket_id', $t['paket_id'])
                                ->where('item_id', $it['id_item'])
                                ->findAll();
                            if (count($pembayaran) > 0) {
                                foreach ($pembayaran as $p) {
                                    $tmp_total_pembayaran += $p['nominal_pembayaran'];
                                }
                                // write sisa tagihan item
                                $spreadsheet
                                    ->setActiveSheetIndex(0)
                                    ->setCellValue('D' . $col, ($tmp_nominal_tagihan - $tmp_total_pembayaran));
                            } else {
                                // write sisa tagihan item
                                $spreadsheet
                                    ->setActiveSheetIndex(0)
                                    ->setCellValue('D' . $col, $tmp_nominal_tagihan);
                            }
                            $col++;
                            $tmp_total_pembayaran = 0;
                            $tmp_nominal_tagihan = 0;
                        }
                    }
                } else {
                    // redirect and show error message
                    return redirect()->back()->with('error', 'Data tagihan tidak tersedia!');
                }
            } else {
                // redirect and show error message
                return redirect()->back()->with('error', 'Data mahasiswa tidak tersedia!');
            }
            // write spreadsheet to file
            $writer = new Xlsx($spreadsheet);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename=' . $nim . '_detail_pembayaran.xlsx');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
