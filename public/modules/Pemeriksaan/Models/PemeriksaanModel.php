<?php

namespace Modules\Pemeriksaan\Models;

use CodeIgniter\Model;

class PemeriksaanModel extends Model
{
    protected $table = 'pemeriksaan';
    protected $primaryKey = 'pemeriksaanId';
    protected $allowedFields = ['pemeriksaanPasienId', 'pemeriksaanPoliId', 'pemeriksaanAnamnese', 'pemeriksaanDiagnosa', 'pemeriksaanTherapy', 'pemeriksaanCreatedBy', 'pemeriksaanCreatedDate', 'pemeriksaanModifiedBy', 'pemeriksaanModifiedDate', 'pemeriksaanDeletedDate', 'pemeriksaanNotes', 'pemeriksaanCost'];
    protected $useTimestamps = 'false';
    protected $useSoftDeletes = 'true';
    protected $createdField = 'pemeriksaanCreatedDate';
    protected $updatedField = 'pemeriksaanModifiedDate';
    protected $deletedField = 'pemeriksaanDeletedDate';
    protected $returnType = 'object';

    public function getPemeriksaan($email, $keyword = null)
    {
        $builder = $this->table($this->table);
        $builder->join('pasien', 'pasien."pasienId" =' . $this->table . '."pemeriksaanPasienId"', 'LEFT');
        $builder->join('penempatan', 'penempatan."penempatanId" =' . $this->table . '."pemeriksaanPoliId"', 'LEFT');
        $builder->join('poli', 'poli."poliId" = penempatan."poliId"', 'LEFT');
        $builder->join('dokter', 'dokter."dokterId" = penempatan."dokterId"', 'LEFT');
        if ($keyword) {
            $builder->like(['LOWER(pasien."pasienName")' => strtolower($keyword)]);
        }
        $builder->where(['dokter."dokterEmail"' => $email, $this->table . '."pemeriksaanAnamnese"' => NULL, $this->table . '."pemeriksaanDiagnosa"' => NULL, $this->table . '."pemeriksaanTherapy"' => NULL]);
        $builder->orderBy($this->table . '."pemeriksaanCreatedDate"', 'ASC');
        return $builder;
    }

    public function getById($where)
    {
        $builder = $this->table($this->table);
        $builder->join('pasien', 'pasien."pasienId" =' . $this->table . '."pemeriksaanPasienId"', 'INNER');
        $builder->join('penempatan', 'penempatan."penempatanId" =' . $this->table . '."pemeriksaanPoliId"', 'INNER');
        $builder->join('poli', 'poli."poliId" = penempatan."poliId"', 'INNER');
        $builder->where($where);
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
