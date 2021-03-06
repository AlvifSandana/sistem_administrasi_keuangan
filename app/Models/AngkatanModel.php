<?php

namespace App\Models;

use CodeIgniter\Model;

class AngkatanModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'angkatan';
    protected $primaryKey           = 'id_angkatan';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = ['nama_angkatan'];

    // Dates
    protected $useTimestamps        = true;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks       = true;
    protected $beforeInsert         = [];
    protected $afterInsert          = [];
    protected $beforeUpdate         = [];
    protected $afterUpdate          = [];
    protected $beforeFind           = [];
    protected $afterFind            = [];
    protected $beforeDelete         = [];
    protected $afterDelete          = [];

    /**
     * search angkatan by keyword
     */
    public function searchAngkatan($keyword)
    {
        $result = 'No result found!';
        $this->like('nama_angkatan', $keyword);
        $this->orderBy('nama_angkatan', 'ASC');
        $query = $this->get();
        if ($query->getResultArray()) {
            $result = $query->getResultArray();
        }
        return $result;
    }
}
