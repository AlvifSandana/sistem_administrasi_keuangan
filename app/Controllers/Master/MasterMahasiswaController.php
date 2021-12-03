<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\AngkatanModel;
use App\Models\MahasiswaModel;
use App\Models\PaketModel;
use App\Models\ProgdiModel;
use App\Models\TagihanModel;
use CodeIgniter\I18n\Time;

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
        $data['status'] = '';
        $data['message'] = '';
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
            // validate uploaded file
            if (!$file->isValid()) {
                // throw error 
                throw new \RuntimeException($file->getErrorString() . '(' . $file->getError() . ')');
                return redirect()->to(base_url() . '/master-mahasiswa')
                    ->with('error', $file->getErrorString() . '(' . $file->getError() . ')');
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
                foreach ($active_sheet as $idx => $data) {
                    // bypass first row (title row)
                    if ($idx ==  1) {
                        continue;
                    }
                    // instantiate model
                    $db = \Config\Database::connect();
                    $m_progdi = new ProgdiModel();
                    $m_angkatan = new AngkatanModel();
                    $m_mahasiswa = new MahasiswaModel();
                    $m_tagihan = new TagihanModel();
                    // get progdi, angkatan, and paket tagihan
                    $progdi = $m_progdi->where('nama_progdi', $data['C'])->first();
                    $angkatan = $m_angkatan->where('nama_angkatan', $data['D'])->first();
                    $query_paket = $db->table('paket')
                        ->like('nama_paket', $data['C'])
                        ->get();
                    $paket = $query_paket->getResultArray();
                    // validate query result
                    if ($progdi != null && $angkatan != null) {
                        // insert new mahasiswa
                        $mahasiswa = $m_mahasiswa->insert([
                            'nim' => $data['A'],
                            'nama_mahasiswa' => $data['B'],
                            'progdi_id' => $progdi['id_progdi'],
                            'angkatan_id' => $angkatan['id_angkatan'],
                        ]);
                        // validate query result
                        if ($mahasiswa != null) {
                            /**
                             * create new tagihan by paket name
                             * 
                             * if paket name contains 'D3' or 'D-III',
                             * iterate 6x (D3 = 6 semester, etc.)
                             */
                            if (strpos($paket[0]['nama_paket'], 'D3') !== false || strpos($paket[0]['nama_paket'], 'D-III') !== false) {
                                for ($i = 0; $i < 6; $i++) {
                                    // insert new tagihan 
                                    $tagihan = $m_tagihan->insert([
                                        'paket_id' => $paket[$i]['id_paket'],
                                        'mahasiswa_id' => $mahasiswa,
                                        'tanggal_tagihan' => Time::parse('now', 'Asia/Jakarta')->toDateTimeString(),
                                        'keterangan_tagihan' => $paket[$i]['nama_paket'],
                                        'status_tagihan' => 'belum_lunas',
                                        'user_id' => 1
                                    ]);
                                    if ($tagihan != null) {
                                        continue;
                                    }
                                }
                            } else if (strpos($paket[0]['nama_paket'], 'D4') !== false || strpos($paket[0]['nama_paket'], 'D-IV') !== false) {
                                for ($i = 0; $i < 8; $i++) {
                                    // insert new tagihan 
                                    $tagihan = $m_tagihan->insert([
                                        'paket_id' => $paket[$i]['id_paket'],
                                        'mahasiswa_id' => $mahasiswa,
                                        'tanggal_tagihan' => Time::parse('now', 'Asia/Jakarta')->toDateTimeString(),
                                        'keterangan_tagihan' => $paket[$i]['nama_paket'],
                                        'status_tagihan' => 'belum_lunas',
                                        'user_id' => 1
                                    ]);
                                    if ($tagihan != null) {
                                        continue;
                                    }
                                }
                            } else if (strpos($paket[0]['nama_paket'], 'S1') !== false || strpos($paket[0]['nama_paket'], 'S1') !== false) {
                                for ($i = 0; $i < 8; $i++) {
                                    // insert new tagihan 
                                    $tagihan = $m_tagihan->insert([
                                        'paket_id' => $paket[$i]['id_paket'],
                                        'mahasiswa_id' => $mahasiswa,
                                        'tanggal_tagihan' => Time::parse('now', 'Asia/Jakarta')->toDateTimeString(),
                                        'keterangan_tagihan' => $paket[$i]['nama_paket'],
                                        'status_tagihan' => 'belum_lunas',
                                        'user_id' => 1
                                    ]);
                                    if ($tagihan != null) {
                                        continue;
                                    }
                                }
                            }

                            // foreach ($paket as $p) {
                            //     // insert new tagihan 
                            //     $tagihan = $m_tagihan->insert([
                            //         'paket_id' => $p['id_paket'],
                            //         'mahasiswa_id' => $mahasiswa,
                            //         'tanggal_tagihan' => Time::parse('now', 'Asia/Jakarta')->toDateTimeString(),
                            //         'keterangan_tagihan' => $p['nama_paket'],
                            //         'status_tagihan' => 'belum_lunas',
                            //         'user_id' => 1
                            //     ]);
                            //     if ($tagihan != null) {
                            //         continue;
                            //     }
                            // }
                        }
                    }
                }
                // return view
                return redirect()->to(base_url() . '/master-mahasiswa')
                    ->with('success', 'File berhasil diupload, data berhasil diimport!');
            }
        } catch (\Throwable $th) {
            return redirect()->to(base_url() . '/master-mahasiswa')->with('error', $th->getMessage());
        }
    }

    /**
     * update tagihan mahasiswa by nim
     */
    public function update_tagihan_by_nim()
    {
        try {
            $session = session();
            // create validator
            $validator = \Config\Services::validation();
            // set validation rules
            $validator->setRules([
                'nim' => 'required'
            ]);
            // begin validation process
            $isDataValid = $validator->withRequest($this->request)->run();
            if ($isDataValid) {
                // get post data
                $nim = $this->request->getPost('nim');
                $paket_tagihan = $this->request->getPost('paket_tagihan');
                // create model instance
                $m_mahasiswa = new MahasiswaModel();
                $m_tagihan = new TagihanModel();
                // get id_mahasiswa
                $mahasiswa = $m_mahasiswa->where('nim', $nim)->first();
                if ($mahasiswa != null) {
                    // get current tagihan by id_mahasiswa
                    $current_tagihan = $m_tagihan->where('mahasiswa_id', $mahasiswa['id_mahasiswa'])->findAll();
                    // get paket_id from current_tagihan
                    $paket_id = [];
                    for ($i = 0; $i < count($current_tagihan); $i++) {
                        array_push($paket_id, $current_tagihan[$i]['paket_id']);
                    }
                    /**
                     * Update tagihan:
                     * 
                     * When N of current tagihan > 0 AND < N of paket_tagihan, 
                     * insert new tagihan from paket_tagihan.
                     * 
                     * When N of current tagihan > 0 AND = N of paket_tagihan AND current tagihan != paket_tagihan, 
                     * update each tagihan with paket_tagihan.
                     * 
                     * When N of current tagihan == 0 AND N of paket_tagihan > 0,
                     * insert each paket_tagihan
                     * 
                     */
                    if (count($paket_id) > 0 && count($paket_id) < count($paket_tagihan)) {
                        // get different paket_id
                        $union_arr = array_merge($paket_id, $paket_tagihan);
                        $intersect_arr = array_intersect($paket_id, $paket_tagihan);
                        $differents = array_values(array_diff($union_arr, $intersect_arr));
                        // insert different paket_id
                        if (sizeof($differents) > 0) {
                            for ($i = 0; $i < count($differents) + 0; $i++) {
                                $new_tagihan = $m_tagihan->insert([
                                    'paket_id' => $differents[$i],
                                    'mahasiswa_id' => $mahasiswa['id_mahasiswa'],
                                    'tanggal_tagihan' => Time::parse('now', 'Asia/Jakarta')->toDateTimeString(),
                                    'keterangan_tagihan' => '',
                                    'status_tagihan' => 'belum_lunas',
                                    'user_id' => $session->get('id_user')
                                ]);
                            }
                            return json_encode([
                                'status' => 'success',
                                'message' => 'Berhasil memperbarui data tagihan mahasiswa dengan NIM ' . $mahasiswa['nim'],
                                'data' => []
                            ]);
                        }
                    } else if (count($paket_id) > 0 && count($paket_id) == count($paket_tagihan)) {
                        // get different paket_id
                        $union_arr = array_merge($paket_id, $paket_tagihan);
                        $intersect_arr = array_intersect($paket_id, $paket_tagihan);
                        $differents = array_values(array_diff($union_arr, $intersect_arr));
                        // insert different paket_id
                        if (sizeof($differents) > 0) {
                            for ($i = 0; $i < sizeof($differents); $i++) {
                                $new_tagihan = $m_tagihan->insert([
                                    'paket_id' => $differents[$i],
                                    'mahasiswa_id' => $mahasiswa['id_mahasiswa'],
                                    'tanggal_tagihan' => Time::parse('now', 'Asia/Jakarta')->toDateTimeString(),
                                    'keterangan_tagihan' => '',
                                    'status_tagihan' => 'belum_lunas',
                                    'user_id' => $session->get('id_user')
                                ]);
                            }
                            return json_encode([
                                'status' => 'success',
                                'message' => 'Berhasil memperbarui data tagihan mahasiswa dengan NIM ' . $mahasiswa['nim'],
                                'data' => []
                            ]);
                        }
                    } else if ($current_tagihan == null && count($paket_tagihan) > 0) {
                        // iterate paket_tagihan 
                        for ($i = 0; $i < count($paket_tagihan); $i++) {
                            $new_tagihan = $m_tagihan->insert([
                                'paket_id' => $paket_tagihan[$i],
                                'mahasiswa_id' => $mahasiswa['id_mahasiswa'],
                                'tanggal_tagihan' => Time::parse('now', 'Asia/Jakarta')->toDateTimeString(),
                                'keterangan_tagihan' => '',
                                'status_tagihan' => 'belum_lunas',
                                'user_id' => $session->get('id_user')
                            ]);
                        }
                        // return response
                        return json_encode([
                            'status' => 'success',
                            'message' => 'Berhasil memperbarui data tagihan mahasiswa dengan NIM ' . $mahasiswa['nim'],
                            'data' => []
                        ]);
                    } else {
                        // return response
                        return json_encode([
                            'status' => 'failed',
                            'message' => 'Gagal memperbarui data tagihan mahasiswa dengan NIM ' . $mahasiswa['nim'],
                            'data' => []
                        ]);
                    }
                } else {
                    // return response
                    return json_encode([
                        'status' => 'failed',
                        'message' => 'Mahasiswa dengan NIM ' . $mahasiswa['nim'] . ' tidak ditemukan!',
                        'data' => []
                    ]);
                }
            } else {
                // return response
                return json_encode([
                    'status' => 'failed',
                    'message' => 'Validasi gagal, silahkan isi data dengan benar!',
                    'data' => $validator->getErrors()
                ]);
            }
        } catch (\Throwable $th) {
            // return response
            return json_encode([
                'status' => 'error',
                'message' => $th->getMessage(),
                'data' => [
                    'code' => $th->getCode(),
                    'stack' => $th->getTrace()
                ]
            ]);
        }
    }
}
