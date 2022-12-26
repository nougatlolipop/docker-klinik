<?php

/* 
This is Controller Krs
 */

namespace Modules\Pendaftaran\Controllers;

use App\Controllers\BaseController;
use Modules\Pasien\Models\PasienModel;
use Modules\Pemeriksaan\Models\PemeriksaanModel;
use Modules\Pendaftaran\Models\PendaftaranModel;
use Modules\Penempatan\Models\PenempatanModel;

class Pendaftaran extends BaseController
{
    protected $pendaftaranModel;
    protected $pasienModel;
    protected $penempatanModel;
    protected $pemeriksaanModel;

    public function __construct()
    {
        $this->pendaftaranModel = new PendaftaranModel();
        $this->pasienModel = new PasienModel();
        $this->penempatanModel = new PenempatanModel();
        $this->pemeriksaanModel = new PemeriksaanModel();
    }

    public function index()
    {
        $data = [
            'menu' => $this->fetchMenu(),
            'title' => "Pasien",
            'breadcrumb' => ['Administrasi', 'Pasien'],
            'pasien' => $this->pasienModel->findAll(),
            'penempatan' => $this->penempatanModel->getPenempatan()->findall()
        ];
        // dd($data['rekamMedis']);
        return view('Modules\Pendaftaran\Views\pendaftaran', $data);
    }

    public function pasienAdd()
    {
        // $url = $this->request->getServer('HTTP_REFERER');

        $rules = [
            'pasienName' => rv('required', ['required' => 'Nama Pasien Harus Diisi']),
        ];
        if (!$this->validate($rules)) {
            return redirect()->to('/pendaftaran')->withInput();
        };
        $data = array(
            'nik' => $this->request->getVar('nik'),
            'pasienName' => $this->request->getVar('pasienName'),
            'pasienAge' => $this->request->getVar('pasienAge'),
            'pasienSex' => $this->request->getVar('jk'),
            'pasienAddress' => $this->request->getVar('address'),
            'pasienAccupation' => $this->request->getVar('occupation'),
            'pasienUnit' => $this->request->getVar('unit'),
            'pasienCreatedBy' => user()->email
        );

        if ($this->pasienModel->insert($data)) :
            session()->setFlashdata('success', 'Pasien Berhasil Ditambah');
        else :
            session()->setFlashdata('error', 'Pasien Gagal Ditambah');
        endif;
        return redirect()->to('/pendaftaran' . `?n={$this->request->getVar('nik')}`);
    }
}
