<?php

namespace Modules\Dokter\Models;

use CodeIgniter\Model;

class DokterModel extends Model
{
    protected $table = 'dokter';
    protected $primaryKey = 'dokterId';
    protected $allowedFields = ['uuid', 'dokterSip', 'dokterName', 'dokterNoHp', 'dokterEmail', 'dokterAlamat', 'dokterCretatedBy', 'dokterCreatedDate', 'dokterModifiedBy', 'dokterModifiedDate', 'dokterDeletedDate'];
    protected $useTimestamps = 'false';
    protected $useSoftDeletes = 'true';
    protected $createdField = 'dokterCreatedDate';
    protected $updatedField = 'dokterModifiedDate';
    protected $deletedField = 'dokterDeletedDate';
    protected $returnType = 'object';

    public function getDokter($keyword = null)
    {
        $builder = $this->table($this->table);
        if ($keyword) {
            $builder->like($this->table . '.dokterName', $keyword)->where($this->table . '.dokterDeletedDate', null);
        }
        $builder->orderBy($this->table . '.dokterId', 'DESC');
        return $builder;
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
