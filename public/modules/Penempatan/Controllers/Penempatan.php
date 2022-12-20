<?php

/* 
This is Controller Krs
 */

namespace Modules\Penempatan\Controllers;

use App\Controllers\BaseController;
use Modules\Dokter\Models\DokterModel;
use Modules\Penempatan\Models\PenempatanModel;
use Modules\Poli\Models\PoliModel;

class Penempatan extends BaseController
{
    protected $penempatanModel;
    protected $dokterModel;
    protected $poliModel;

    public function __construct()
    {
        $this->penempatanModel = new PenempatanModel();
        $this->dokterModel = new DokterModel();
        $this->poliModel = new PoliModel();
    }

    public function index()
    {
        $currentPage = $this->request->getVar('page_penempatan') ? $this->request->getVar('page_penempatan') : 1;
        $keyword = $this->request->getVar('keyword');
        $penempatan = $this->penempatanModel->getPenempatan($keyword);
        $data = [
            'menu' => $this->fetchMenu(),
            'title' => "Penempatan",
            'breadcrumb' => ['Setting', 'Penempatan'],
            'penempatan' => $penempatan->paginate($this->numberPage, 'penempatan'),
            'dokter' => $this->dokterModel->findAll(),
            'poli' => $this->poliModel->findAll(),
            'currentPage' => $currentPage,
            'numberPage' => $this->numberPage,
            'pager' => $penempatan->pager,
            'validation' => \Config\Services::validation(),
        ];

        return view('Modules\Penempatan\Views\penempatan', $data);
    }

    public function add()
    {
        $url = $this->request->getServer('HTTP_REFERER');
        $rules = [
            'dokterName' => rv('required', ['required' => 'Nama Dokter Harus Dipilih']),
            'poliName' => rv('required', ['required' => 'Nama Poli Harus Dipilih']),
        ];
        if (!$this->validate($rules)) {
            return redirect()->to($url)->withInput();
        };
        $data = array(
            'penempatanCretatedBy' => user()->email,
            'dokterId' => $this->request->getVar('dokterName'),
            'poliId' => $this->request->getVar('poliName'),
        );

        if ($this->penempatanModel->insert($data)) :
            session()->setFlashdata('success', 'Penempatan Berhasil DiTambah');
        else :
            session()->setFlashdata('error', 'Penempatan Gagal DiTambah');
        endif;
        return redirect()->to($url);
    }

    public function edit($id)
    {
        $url = $this->request->getServer('HTTP_REFERER');
        $rules = [
            'poliName' => rv('required', ['required' => 'Nama Poli Harus Dipilih']),
        ];
        if (!$this->validate($rules)) {
            return redirect()->to($url)->withInput();
        };
        $data = array(
            'poliId' => $this->request->getVar('poliName'),
            'penempatanModifiedBy' => user()->email,
        );
        if ($this->penempatanModel->updateData(['penempatanId' => $id], $data)) :
            session()->setFlashdata('success', 'Penempatan Berhasil Diupdate');
        else :
            session()->setFlashdata('error', 'Penempatan Gagal Diupdate');
        endif;
        return redirect()->to($url);
    }

    public function delete($id)
    {
        $url = $this->request->getServer('HTTP_REFERER');
        if ($this->penempatanModel->deleteData(['penempatanId' => $id])) :
            session()->setFlashdata('success', 'Penempatan Berhasil Dihapus');
        else :
            session()->setFlashdata('error', 'Penempatan Gagal Dihapus');
        endif;
        return redirect()->to($url);
    }
}
