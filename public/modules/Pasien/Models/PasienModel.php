<?php

namespace Modules\Pasien\Models;

use CodeIgniter\Model;

class PasienModel extends Model
{
    protected $table = 'pasien';
    protected $primaryKey = 'pasienId';
    protected $allowedFields = ['nik', 'pasienCreatedDate', 'pasienModifiedDate', 'pasienCreatedBy', 'pasienModifiedBy', 'pasienDeletedDate', 'pasienName', 'pasienAge', 'pasienSex', 'pasienAddress', 'pasienAccupation', 'pasienUnit', 'pasienStatus'];
    protected $useTimestamps = 'false';
    protected $useSoftDeletes = 'true';
    protected $createdField = 'pasienCreatedDate';
    protected $updatedField = 'pasienModifiedDate';
    protected $deletedField = 'pasienDeletedDate';
    protected $returnType = 'object';


    public function getById($where)
    {
        $builder = $this->table($this->table);
        $builder->where($where);
        return $builder->get();
    }

    public function updateData($where, $data)
    {
        $builder = $this->table($this->table);
        return $builder->set($data)
            ->where($where)
            ->update();
    }

    public function deleteData($where)
    {
        $builder = $this->table($this->table);
        return $builder->where($where)
            ->delete();
    }
}
