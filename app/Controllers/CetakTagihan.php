<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ItemPaketModel;
use App\Models\MahasiswaModel;
use App\Models\PaketModel;
use App\Models\PembayaranModel;
use App\Models\SemesterModel;
use App\Models\TagihanModel;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CetakTagihan extends BaseController
{
    public function index()
    {
        //
    }

    /**
     * Cetak detail tagihan by NIM
     */
    public function byNIM($nim)
    {
        try {
            // total tagihan & sisa tagihan
            $total_tagihan = 0;
            $total_pembayaran = 0;
            $col = 7;
            $col1 = 4;

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
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                    $spreadsheet = $reader->load(WRITEPATH . 'templates/template_cetak_tagihan_by_mhs.xlsx');
                    $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
                    $spreadsheet->getDefaultStyle()->getFont()->setSize(12);
                    // create title
                    $spreadsheet
                        ->setActiveSheetIndex(0)
                        ->setCellValue('B3', $mahasiswa['nim'])
                        ->setCellValue('B4', $mahasiswa['nama_mahasiswa']);
                    // iterate all tagihan
                    foreach ($tagihan as $t) {
                        // get paket tagihan
                        $paket = $m_paket->where('id_paket', $t['paket_id'])->first();
                        // write nama tagihan
                        $spreadsheet
                            ->setActiveSheetIndex(1)
                            ->setCellValue('A' . $col1, $paket['nama_paket']);
                        // get semester
                        $semester = $m_semester->where('id_semester', $paket['semester_id'])->first();
                        // get item paket tagihan
                        $item_tagihan = $m_itemtagihan->where('paket_id', $t['paket_id'])->findAll();
                        // count total tagihan 
                        foreach ($item_tagihan as $it) {
                            // write nama & nominal item tagihan
                            $spreadsheet
                                ->setActiveSheetIndex(1)
                                ->setCellValue('B' . $col1, $it['nama_item'])
                                ->setCellValue('C' . $col1, $it['nominal_item']);
                            // set total tagihan
                            $total_tagihan += $it['nominal_item'];
                            // get pembayaran by item tagihan, paket_id, mahasiswa_id
                            $pembayaran = $m_pembayaran
                                ->where('mahasiswa_id', $mahasiswa['id_mahasiswa'])
                                ->where('paket_id', $t['paket_id'])
                                ->where('item_id', $it['id_item'])
                                ->findAll();
                            // count total pembayaran
                            if (count($pembayaran) > 0) {
                                $tmp_pembayaran = 0;
                                foreach ($pembayaran as $p) {
                                    // set total pembayaran
                                    $tmp_pembayaran += $p['nominal_pembayaran'];
                                    $total_pembayaran += $p['nominal_pembayaran'];
                                }
                                // write nominal pembayaran
                                $spreadsheet
                                    ->setActiveSheetIndex(1)
                                    ->setCellValue('D' . $col1, $tmp_pembayaran);
                            } else {
                                // write nominal pembayaran
                                $spreadsheet
                                    ->setActiveSheetIndex(1)
                                    ->setCellValue('D' . $col1, 0);
                            }
                            $col1++;
                        }
                        // create table value
                        $spreadsheet
                            ->setActiveSheetIndex(0)
                            ->setCellValue('A' . $col, $semester['nama_semester'])
                            ->setCellValue('B' . $col, $total_tagihan)
                            ->setCellValue('C' . $col, $total_pembayaran)
                            ->setCellValue('D' . $col, ($total_tagihan - $total_pembayaran));
                        // increment column
                        $col++;
                        // set total to zero
                        $total_tagihan = 0;
                        $total_pembayaran = 0;
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
            header('Content-Disposition: attachment;filename=' . $nim . '_detail_tagihan.xlsx');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Cetak detail tagihan by NIM & ID paket tagihan
     */
    public function byNimPaket($nim, $id_paket)
    {
        try {
            // total tagihan & sisa tagihan
            $total_tagihan = 0;
            $total_pembayaran = 0;
            $col = 8;
            $col1 = 8;
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
                $tagihan = $m_tagihan
                    ->where('mahasiswa_id', $mahasiswa['id_mahasiswa'])
                    ->where('paket_id', $id_paket)
                    ->first();
                if ($tagihan) {
                    // create spreadsheet instance
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                    $spreadsheet = $reader->load(WRITEPATH . 'templates/template_cetak_tagihan_by_nim_idpaket.xlsx');
                    $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
                    $spreadsheet->getDefaultStyle()->getFont()->setSize(12);
                    // create title
                    $spreadsheet
                        ->setActiveSheetIndex(0)
                        ->setCellValue('B3', $mahasiswa['nim'])
                        ->setCellValue('B4', $mahasiswa['nama_mahasiswa']);
                    // get paket tagihan
                    $paket = $m_paket->where('id_paket', $tagihan['paket_id'])->first();
                    // write nama tagihan
                    $spreadsheet
                        ->setActiveSheetIndex(0)
                        ->setCellValue('A' . $col, $paket['nama_paket']);
                    // get semester
                    $semester = $m_semester->where('id_semester', $paket['semester_id'])->first();
                    $spreadsheet
                        ->setActiveSheetIndex(0)
                        ->setCellValue('B5', $semester['nama_semester']);
                    // get item paket tagihan
                    $item_tagihan = $m_itemtagihan->where('paket_id', $tagihan['paket_id'])->findAll();
                    // count total tagihan 
                    foreach ($item_tagihan as $it) {
                        // write nama & nominal item tagihan
                        $spreadsheet
                            ->setActiveSheetIndex(0)
                            ->setCellValue('B' . $col1, $it['nama_item'])
                            ->setCellValue('C' . $col1, $it['nominal_item']);
                        // set total tagihan
                        $total_tagihan += $it['nominal_item'];
                        // get pembayaran by item tagihan, paket_id, mahasiswa_id
                        $pembayaran = $m_pembayaran
                            ->where('mahasiswa_id', $mahasiswa['id_mahasiswa'])
                            ->where('paket_id', $tagihan['paket_id'])
                            ->where('item_id', $it['id_item'])
                            ->findAll();
                        // count total pembayaran
                        if (count($pembayaran) > 0) {
                            $tmp_pembayaran = 0;
                            foreach ($pembayaran as $p) {
                                // set total pembayaran
                                $tmp_pembayaran += $p['nominal_pembayaran'];
                                $total_pembayaran += $p['nominal_pembayaran'];
                            }
                            // write nominal pembayaran
                            $spreadsheet
                                ->setActiveSheetIndex(0)
                                ->setCellValue('D' . $col1, $tmp_pembayaran);
                        } else {
                            // write nominal pembayaran
                            $spreadsheet
                                ->setActiveSheetIndex(0)
                                ->setCellValue('D' . $col1, 0);
                        }
                        $col1++;
                    }
                    // create table value
                    $spreadsheet
                        ->setActiveSheetIndex(0)
                        ->setCellValue('B' . $col1, 'TOTAL TAGIHAN')
                        ->setCellValue('C' . $col1, $total_tagihan)
                        ->setCellValue('D' . $col1, $total_pembayaran);
                    // set total to zero
                    $total_tagihan = 0;
                    $total_pembayaran = 0;
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
            header('Content-Disposition: attachment;filename=' . $nim . '_detail_tagihan.xlsx');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
