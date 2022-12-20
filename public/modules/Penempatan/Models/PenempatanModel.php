<?php

namespace Modules\Penempatan\Models;

use CodeIgniter\Model;

class PenempatanModel extends Model
{
    protected $table = 'penempatan';
    protected $primaryKey = 'penempatanId';
    protected $allowedFields = ['dokterId', 'poliId', 'penempatanCretatedBy', 'penempatanCreatedDate', 'penempatanModifiedBy', 'penampatanModifiedDate', 'penempatanDeletedDate'];
    protected $useTimestamps = 'false';
    protected $useSoftDeletes = 'true';
    protected $createdField = 'penempatanCreatedDate';
    protected $updatedField = 'penampatanModifiedDate';
    protected $deletedField = 'penempatanDeletedDate';
    protected $returnType = 'object';

    public function getPenempatan($keyword = null)
    {
        $builder = $this->table($this->table);
        $builder->join('poli', 'poli.poliId = ' . $this->table . '.poliId', 'LEFT');
        $builder->join('dokter', 'dokter.dokterId = ' . $this->table . '.dokterId', 'LEFT');
        if ($keyword) {
            $builder->like($this->table . '.penempatanName', $keyword)->where($this->table . '.penempatanDeletedDate', null);
        }
        $builder->orderBy($this->table . '.penempatanId', 'DESC');
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
