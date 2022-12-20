<?php

namespace Modules\Poli\Models;

use CodeIgniter\Model;

class PoliModel extends Model
{
    protected $table = 'poli';
    protected $primaryKey = 'poliId';
    protected $allowedFields = ['uuid', 'poliName', 'poliCretatedBy', 'poliCreatedDate', 'poliModifiedBy', 'poliModifiedDate', 'poliDeletedDate'];
    protected $useTimestamps = 'false';
    protected $useSoftDeletes = 'true';
    protected $createdField = 'poliCreatedDate';
    protected $updatedField = 'poliModifiedDate';
    protected $deletedField = 'poliDeletedDate';
    protected $returnType = 'object';

    public function getPoli($keyword = null)
    {
        $builder = $this->table($this->table);
        if ($keyword) {
            $builder->like($this->table . '.poliName', $keyword)->where($this->table . '.poliDeletedDate', null);
        }
        $builder->orderBy($this->table . '.poliId', 'DESC');
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
