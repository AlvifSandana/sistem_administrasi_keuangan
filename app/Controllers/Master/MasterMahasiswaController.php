<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\AngkatanModel;
use App\Models\MahasiswaModel;
use App\Models\PaketModel;
use App\Models\ProgdiModel;
use App\Models\TagihanModel;

class MasterMahasiswaController extends BaseController
{
    public function index()
    {
        // create model instance
        $m_mahasiswa = new MahasiswaModel();
        $m_angkatan = new AngkatanModel();
        $m_progdi = new ProgdiModel();
        $m_paket = new PaketModel();
        // create request instance
        $request = \Config\Services::request();
        // get uri segment for dynamic sidebar active item
        $data['uri_segment'] = $request->uri->getSegment(1);
        // get data from model
        $data['data_mahasiswa'] = $m_mahasiswa->findAll();
        $data['data_angkatan'] = $m_angkatan->findAll();
        $data['data_progdi'] = $m_progdi->findAll();
        $data['data_paket'] = $m_paket->findAll();
        // return view
        return view('pages/master/mahasiswa/index', $data);
    }

    /**
     * Tambah data mahasiswa
     * 
     * @return JSON
     */
    public function create_mahasiswa()
    {
        $result = [
            'status' => '',
            'message' => '',
            'data' => null,
        ];
        try {
            // create validator
            $validator = \Config\Services::validation();
            // set validator rules
            $validator->setRules([
                'nim' => 'required',
                'nama_mahasiswa' => 'required',
                'progdi_id' => 'required',
                'angkatan_id' => 'required',
                'paket_tagihan' => 'required',
            ]);
            // validation check
            $isDataValid = $validator->withRequest($this->request)->run();
            if ($isDataValid) {
                // create model instance
                $m_mahasiswa = new MahasiswaModel();
                $m_tagihan = new TagihanModel();
                // insert data
                $mahasiswa = $m_mahasiswa->insert([
                    'nim' => $this->request->getPost('nim'),
                    'nama_mahasiswa' => $this->request->getPost('nama_mahasiswa'),
                    'progdi_id' => $this->request->getPost('progdi_id'),
                    'angkatan_id' => $this->request->getPost('angkatan_id'),
                ]);
                if ($mahasiswa) {
                    // get id_mahasiswa
                    $id_mahasiswa = $mahasiswa;
                    // get paket_tagihan id and tanggal_tagihan
                    $paket_tagihan = $this->request->getPost('paket_tagihan');
                    $tanggal_tagihan = $this->request->getPost('tanggal_tagihan');
                    // iterate and insert 
                    for ($i = 0; $i < count($paket_tagihan); $i++) {
                        $m_tagihan->insert([
                            'paket_id' => (int) $paket_tagihan[$i],
                            'mahasiswa_id' => $id_mahasiswa,
                            'tanggal_tagihan' => $tanggal_tagihan,
                            'keterangan_tagihan' => '-',
                            'status_tagihan' => 'belum_lunas',
                            'user_id' => 1,
                        ]);
                    }
                    $result['status'] = 'success';
                    $result['message'] = 'Berhasil menambahkan data mahasiswa.';
                    $result['data'] = $mahasiswa;
                    return json_encode($result);
                } else {
                    $result['status'] = 'failed';
                    $result['message'] = 'Gagal menambahkan data mahasiswa.';
                    $result['data'] = $mahasiswa;
                    return json_encode($result);
                }
            } else {
                $result['status'] = 'Failed';
                $result['message'] = 'Validasi gagal. Mohon isi form dengan lengkap!';
                $result['data'] = $validator->getErrors();
                return json_encode($result);
            }
        } catch (\Throwable $th) {
            $result['status'] = 'error';
            $result['message'] = $th->getMessage();
            $result['data'] = [];
            return json_encode($result);
        }
    }

    /**
     * Import dari file excel (.xlsx)
     */
    public function import()
    {
        $result = [
            'status' => '',
            'message' => '',
            'data' => null,
        ];
        try {
            // get file from POST requst
            $file = $this->request->getFile('file_import');
            // dd($file->getTempName());
            // validate uploaded file
            if (!$file->isValid()) {
                // throw error 
                $result['status'] = 'error';
                $result['message'] = $file->getErrorString() . '(' . $file->getError() . ')';
                throw new \RuntimeException($file->getErrorString() . '(' . $file->getError() . ')');
                return json_encode($result);
            } else {
                // random filename
                $fn = $file->getRandomName();
                // move file to uploaded folder
                $path = $file->store('import/', $fn);
                // create file reader
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                // load file (only data sheet)
                $reader->setLoadSheetsOnly(['DATA IMPORT']);
                $sheet = $reader->load(WRITEPATH . 'uploads/' . $path);
                // get data, convert to array
                $active_sheet = $sheet->getActiveSheet()->toArray(null, true, true, true);
                // processing data
                $data_mahasiswa = [];
                foreach ($active_sheet as $idx => $data) {
                    // bypass first row
                    if ($idx ==  1) {
                        continue;
                    }
                    array_push($data_mahasiswa, [
                        'nim' => $data['A'],
                        'nama_mahasiswa' => $data['B'],
                        'program_studi' => $data['C'],
                        'angkatan' => $data['D'],
                        'paket_tagihan' => $data['E'],
                    ]);
                }
                // test data
                $result['status'] = 'success';
                $result['message'] = 'File berhasil diupload';
                $result['data'] = $data_mahasiswa;
                return json_encode($result);
            }
        } catch (\Throwable $th) {
            $result['status'] = 'error';
            $result['message'] = $th->getMessage();
            return json_encode($result);
        }
    }
}
