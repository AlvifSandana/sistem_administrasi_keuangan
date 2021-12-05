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
        $request = \Config\Services::request();
        // get uri segment for dynamic sidebar active item
        $data['uri_segment'] = $request->uri->getSegment(1);
        // return view
        return view('pages/master/laporan/index', $data);
    }

    /**
     * Create laporan tagihan mahasiswa
     * by NIM
     */
    public function generate_laporan_tagihan($nim)
    {
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
            return redirect()->back()->with('error', 'Data mahasiswa tidak tersedia!');
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
        try {
            // total tagihan & sisa tagihan
            $total_tagihan = 0;
            $total_pembayaran = 0;
            $sisa_tagihan = 0;
            $nama_semester = '';
            $col = 4;
            // create model instance
            $m_mahasiswa = new MahasiswaModel();
            $m_semester = new SemesterModel();
            $m_paket = new PaketModel();
            $m_tagihan = new TagihanModel();
            $m_itempaket = new ItemPaketModel();
            $m_pembayaran = new PembayaranModel();
            // get all mahasiswa
            $mahasiswa = $m_mahasiswa->findAll();
            if (count($mahasiswa) > 0) {
                // create spreadsheet instance
                $spreadsheet = new Spreadsheet();
                // create title
                $spreadsheet
                    ->setActiveSheetIndex(0)
                    ->setCellValue('B1', 'LAPORAN TAGIHAN MAHASISWA')
                    ->setCellValue('A3', 'NIM')
                    ->setCellValue('B3', 'NAMA')
                    ->setCellValue('C3', 'SEMESTER')
                    ->setCellValue('D3', 'TOTAL TAGIHAN')
                    ->setCellValue('E3', 'SISA TAGIHAN');
                // iterate each data
                foreach ($mahasiswa as $m) {
                    // get tagihan by mahasiswa_id
                    $tagihan = $m_tagihan->where('mahasiswa_id', $m['id_mahasiswa'])->findAll();
                    if (count($tagihan) > 0) {
                        // iterate each data
                        foreach ($tagihan as $t) {
                            // get paket tagihan by paket_id
                            $paket = $m_paket->where('id_paket', $t['paket_id'])->first();
                            // get semester by semester_id
                            $semester = $m_semester->where('id_semester', $paket['semester_id'])->first();
                            $nama_semester = $semester['nama_semester'];
                            // get item tagihan by paket_id
                            $item_tagihan = $m_itempaket->where('paket_id', $t['paket_id'])->findAll();
                            if (count($item_tagihan) > 0) {
                                // iterate each data
                                foreach ($item_tagihan as $it) {
                                    // add total_tagihan
                                    $total_tagihan += $it['nominal_item'];
                                    // get pembayaran by mahasiswa_id, paket_id, item_id
                                    $pembayaran = $m_pembayaran
                                        ->where('mahasiswa_id', $m['id_mahasiswa'])
                                        ->where('paket_id', $t['paket_id'])
                                        ->where('item_id', $it['id_item'])
                                        ->findAll();
                                    if (count($pembayaran) > 0) {
                                        // iterate each data
                                        foreach ($pembayaran as $p) {
                                            // add total_pembayaran
                                            $total_pembayaran += $p['nominal_pembayaran'];
                                        }
                                    }
                                }
                            }
                            // write to spreadsheet
                            $spreadsheet
                                ->setActiveSheetIndex(0)
                                ->setCellValue('A' . $col, $m['nim'])
                                ->setCellValue('B' . $col, $m['nama_mahasiswa'])
                                ->setCellValue('C' . $col, $semester['nama_semester'])
                                ->setCellValue('D' . $col, $total_tagihan)
                                ->setCellValue('E' . $col, ($total_tagihan - $total_pembayaran));
                            $col++;
                            $total_tagihan = 0;
                            $total_pembayaran = 0;
                        }
                    } else {
                        return redirect()->to(base_url() . '/master-laporan')->with('error', 'Data tagihan kosong!');
                    }
                }
                // write current spreadsheet to .xlsx file
                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename=data.xlsx');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
            } else {
                return redirect()->to(base_url() . '/master-laporan')->with('error', 'Data mahasiswa tidak ditemukan!');
            }
        } catch (\Throwable $th) {
            return redirect()->to(base_url() . '/master-laporan')->with('error', $th->getMessage());
        }
    }

    /**
     * Create laporan pembayaran by NIM
     */
    public function generate_laporan_pembayaran($nim)
    {
        try {
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
                        ->setCellValue('B4', 'NAMA ITEM')
                        ->setCellValue('C4', 'TOTAL TAGIHAN ITEM')
                        ->setCellValue('D4', 'SISA TAGIHAN');
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
                            // create table value
                            $spreadsheet
                                ->setActiveSheetIndex(0)
                                ->setCellValue('A' . $col, $semester['nama_semester'])
                                ->setCellValue('B' . $col, $it['nama_item'])
                                ->setCellValue('C' . $col, $total_tagihan)
                                ->setCellValue('D' . $col, ($total_tagihan - $total_pembayaran));
                            $col++;
                            $total_tagihan = 0;
                            $total_pembayaran = 0;
                        }
                    }
                } else {
                    return redirect()->back()->with('error', 'Data tagihan tidak tersedia!');
                }
            } else {
                return redirect()->back()->with('error', 'Data mahasiswa tidak tersedia!');
            }
            // return json_encode($result);
            $writer = new Xlsx($spreadsheet);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename=laporan_pembayaran_mahasiswa.xlsx');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
        } catch (\Throwable $th) {
            return redirect()->to(base_url() . '/master-laporan')->with('error', $th->getMessage());
        }
    }

    /**
     * Create laporan pembayaran all mahasiswa
     */
    public function generate_laporan_pembayaran_all_mhs()
    {
        try {
            // total tagihan & sisa tagihan
            $total_tagihan = 0;
            $total_pembayaran = 0;
            $sisa_tagihan = 0;
            $col = 4;
            // create model instance
            $m_mahasiswa = new MahasiswaModel();
            $m_semester = new SemesterModel();
            $m_paket = new PaketModel();
            $m_tagihan = new TagihanModel();
            $m_itempaket = new ItemPaketModel();
            $m_pembayaran = new PembayaranModel();
            // get all mahasiswa
            $mahasiswa = $m_mahasiswa->findAll();
            if (count($mahasiswa) > 0) {
                // create spreadsheet instance
                $spreadsheet = new Spreadsheet();
                // create title
                $spreadsheet
                    ->setActiveSheetIndex(0)
                    ->setCellValue('B1', 'LAPORAN PEMBAYARAN MAHASISWA')
                    ->setCellValue('A3', 'NIM')
                    ->setCellValue('B3', 'NAMA')
                    ->setCellValue('C3', 'SEMESTER')
                    ->setCellValue('D3', 'NAMA ITEM')
                    ->setCellValue('E3', 'TOTAL TAGIHAN ITEM')
                    ->setCellValue('F3', 'SISA TAGIHAN ITEM');
                // iterate each data
                foreach ($mahasiswa as $m) {
                    // get tagihan by mahasiswa_id
                    $tagihan = $m_tagihan->where('mahasiswa_id', $m['id_mahasiswa'])->findAll();
                    if (count($tagihan) > 0) {
                        // iterate each data
                        foreach ($tagihan as $t) {
                            // get paket tagihan by paket_id
                            $paket = $m_paket->where('id_paket', $t['paket_id'])->first();
                            // get semester by semester_id
                            $semester = $m_semester->where('id_semester', $paket['semester_id'])->first();
                            // get item tagihan by paket_id
                            $item_tagihan = $m_itempaket->where('paket_id', $t['paket_id'])->findAll();
                            if (count($item_tagihan) > 0) {
                                // iterate each data
                                foreach ($item_tagihan as $it) {
                                    // add total_tagihan
                                    $total_tagihan += $it['nominal_item'];
                                    // get pembayaran by mahasiswa_id, paket_id, item_id
                                    $pembayaran = $m_pembayaran
                                        ->where('mahasiswa_id', $m['id_mahasiswa'])
                                        ->where('paket_id', $t['paket_id'])
                                        ->where('item_id', $it['id_item'])
                                        ->findAll();
                                    if (count($pembayaran) > 0) {
                                        // iterate each data
                                        foreach ($pembayaran as $p) {
                                            // add total_pembayaran
                                            $total_pembayaran += $p['nominal_pembayaran'];
                                        }
                                    }
                                    // write to spreadsheet
                                    $spreadsheet
                                        ->setActiveSheetIndex(0)
                                        ->setCellValue('A' . $col, $m['nim'])
                                        ->setCellValue('B' . $col, $m['nama_mahasiswa'])
                                        ->setCellValue('C' . $col, $semester['nama_semester'])
                                        ->setCellValue('D' . $col, $it['nama_item'])
                                        ->setCellValue('E' . $col, $total_tagihan)
                                        ->setCellValue('F' . $col, ($total_tagihan - $total_pembayaran));
                                    $col++;
                                    $total_tagihan = 0;
                                    $total_pembayaran = 0;
                                }
                            }
                        }
                    } else {
                        return redirect()->to(base_url() . '/master-laporan')->with('error', 'Data tagihan kosong!');
                    }
                }
                // write current spreadsheet to .xlsx file
                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename=laporan_pembayaran_mahasiswa.xlsx');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
            } else {
                return redirect()->to(base_url() . '/master-laporan')->with('error', 'Data mahasiswa tidak ditemukan!');
            }
        } catch (\Throwable $th) {
            return redirect()->to(base_url() . '/master-laporan')->with('error', $th->getMessage());
        }
    }

    /**
     * Create laporan rekam pembayaran by NIM
     */
    public function generate_laporan_rekam_pembayaran($nim)
    {
        try {
            $col = 5;
            // create model instance
            $m_mahasiswa = new MahasiswaModel();
            $m_semester = new SemesterModel();
            $m_paket = new PaketModel();
            $m_itempaket = new ItemPaketModel();
            $m_pembayaran = new PembayaranModel();
            // get data mahasiswa
            $mahasiswa = $m_mahasiswa->where('nim', $nim)->first();
            if ($mahasiswa != null) {
                // create spreadsheet instance
                $spreadsheet = new Spreadsheet();
                // create title
                $spreadsheet
                    ->setActiveSheetIndex(0)
                    ->setCellValue('B1', 'LAPORAN REKAM PEMBAYARAN MAHASISWA')
                    ->setCellValue('A2', 'NIM')
                    ->setCellValue('A3', 'NAMA')
                    ->setCellValue('B2', ':')
                    ->setCellValue('B3', ':')
                    ->setCellValue('C2', $mahasiswa['nim'])
                    ->setCellValue('C3', $mahasiswa['nama_mahasiswa'])
                    ->setCellValue('A4', 'SEMESTER')
                    ->setCellValue('B4', 'TANGGAL PEMBAYARAN')
                    ->setCellValue('C4', 'NAMA ITEM')
                    ->setCellValue('D4', 'NOMINAL PEMBAYARAN');
                // get pembayaran by mahasiswa_id
                $pembayaran = $m_pembayaran->where('mahasiswa_id', $mahasiswa['id_mahasiswa'])->findAll();
                if (count($pembayaran) > 0) {
                    // iterate each data
                    foreach ($pembayaran as $p) {
                        // get paket by paket_id
                        $paket = $m_paket->where('id_paket', $p['paket_id'])->first();
                        // get semester by semester_id
                        $semester = $m_semester->where('id_semester', $paket['semester_id'])->first();
                        // get item by paket_id, item_id
                        $item = $m_itempaket
                            ->where('paket_id', $p['paket_id'])
                            ->where('id_item', $p['item_id'])
                            ->first();
                        // write to spreadsheet
                        $spreadsheet
                            ->setActiveSheetIndex(0)
                            ->setCellValue('A' . $col, $semester['nama_semester'])
                            ->setCellValue('B' . $col, $p['tanggal_pembayaran'])
                            ->setCellValue('C' . $col, $item['nama_item'])
                            ->setCellValue('D' . $col, $p['nominal_pembayaran']);
                        $col++;
                    }
                }
                // return .xlsx file
                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename=laporan_rekam_pembayaran_'.$mahasiswa['nim'].'.xlsx');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
            } else {
                return redirect()->to(base_url() . '/master-laporan')->with('error', 'Data mahasiswa tidak ditemukan!');
            }
        } catch (\Throwable $th) {
            return redirect()->to(base_url() . '/master-laporan')->with('error', $th->getMessage());
        }
    }

    /**
     * Create laporan rekam pembayaran all mahasiswa
     */
    public function generate_laporan_rekam_pembayaran_all_mhs()
    {
        try {
            $col = 4;
            // create model instance
            $m_mahasiswa = new MahasiswaModel();
            $m_semester = new SemesterModel();
            $m_paket = new PaketModel();
            $m_itempaket = new ItemPaketModel();
            $m_pembayaran = new PembayaranModel();
            // get data pembayaran
            $pembayaran = $m_pembayaran->findAll();
            if (count($pembayaran) > 0) {
                // create spreadsheet instance
                $spreadsheet = new Spreadsheet();
                // create title
                $spreadsheet
                    ->setActiveSheetIndex(0)
                    ->setCellValue('B1', 'LAPORAN PEMBAYARAN MAHASISWA')
                    ->setCellValue('A3', 'NIM')
                    ->setCellValue('B3', 'NAMA')
                    ->setCellValue('C3', 'SEMESTER')
                    ->setCellValue('D3', 'TANGGAL PEMBAYARAN')
                    ->setCellValue('E3', 'NAMA ITEM')
                    ->setCellValue('F3', 'NOMINAL PEMBAYARAN');
                // iterate each data
                foreach ($pembayaran as $p) {
                    // get mahasiswa by mahasiswa_id
                    $mahasiswa = $m_mahasiswa->where('id_mahasiswa', $p['mahasiswa_id'])->first();
                    // get paket by paket_id
                    $paket = $m_paket->where('id_paket', $p['paket_id'])->first();
                    // get item paket by item_id
                    $item = $m_itempaket
                        ->where('id_item', $p['item_id'])
                        ->where('paket_id', $p['paket_id'])
                        ->first();
                    // get semester by semester_id
                    $semester = $m_semester->where('id_semester', $paket['semester_id'])->first();
                    // write to spreadsheet
                    $spreadsheet
                        ->setActiveSheetIndex(0)
                        ->setCellValue('A'.$col, $mahasiswa['nim'])
                        ->setCellValue('B'.$col, $mahasiswa['nama_mahasiswa'])
                        ->setCellValue('C'.$col, $semester['nama_semester'])
                        ->setCellValue('D'.$col, $p['tanggal_pembayaran'])
                        ->setCellValue('E'.$col, $item['nama_item'])
                        ->setCellValue('F'.$col, $p['nominal_pembayaran']);
                    $col++;
                }
                // return .xlsx file
                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename=laporan_rekam_pembayaran.xlsx');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
            } else {
                return redirect()->to(base_url() . '/master-laporan')->with('error', 'Data mahasiswa tidak ditemukan!');
            }
        } catch (\Throwable $th) {
            return redirect()->to(base_url() . '/master-laporan')->with('error', $th->getMessage());
        }
    }
}
