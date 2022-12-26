<?php

/* 
This is Controller Krs
 */

namespace Modules\Pasien\Controllers;

use App\Controllers\BaseController;
use Modules\Pasien\Models\PasienModel;


class Pasien extends BaseController
{
    protected $pasienModel;

    public function __construct()
    {
        $this->pasienModel = new PasienModel();
    }

    public function index()
    {
        $currentPage = $this->request->getVar('page_pasien') ? $this->request->getVar('page_pasien') : 1;
        $keyword = $this->request->getVar('keyword');
        $pasien = $this->pasienModel->getPasien($keyword);
        $data = [
            'menu' => $this->fetchMenu(),
            'title' => "Pasien",
            'breadcrumb' => ['Data', 'Pasien'],
            'pasien' => $pasien->paginate($this->numberPage, 'pasien'),
            'currentPage' => $currentPage,
            'numberPage' => $this->numberPage,
            'pager' => $pasien->pager,
            'validation' => \Config\Services::validation(),
        ];
        return view('Modules\Pasien\Views\pasien', $data);
    }

    public function getById($nik)
    {
        $pasien = $this->pasienModel->getById(['nik' => $nik])->getResult();
        echo json_encode(['data' => $pasien]);
    }

    public function add()
    {
        // $url = $this->request->getServer('HTTP_REFERER');
        // $rules = [
        //     'pasienName' => rv('required', ['required' => 'Nama Pasien Harus Diisi']),
        // ];
        // if (!$this->validate($rules)) {
        //     return redirect()->to($url)->withInput();
        // };
        $data = array(
            'uuid' => uuid(),
            'nik' => $this->request->getVar('nik'),
            'pasienName' => $this->request->getVar('pasienName'),
            'pasienAge' => $this->request->getVar('pasienAge'),
            'pasienAddress' => $this->request->getVar('address'),
            'pasienAccupation' => $this->request->getVar('accupation'),
            'pasienUnit' => $this->request->getVar('unit'),
            'pasienSex' => $this->request->getVar('jk'),
            'pasienStatus' => $this->request->getVar('status'),
            'pasienCretaedBy' => $this->request->getVar('email')
        );

        if ($this->pasienModel->insert($data)) :
            echo json_encode(['status' => true, 'msg' => 'Pasien Berhasil Ditambah', 'data' => json_encode($this->pasienModel->insertId())]);
        else :
            echo json_encode(['status' => false, 'msg' => 'Pasien Gagal Ditambah', 'data' => null]);
        endif;
    }

    // public function edit($id)
    // {
    //     $url = $this->request->getServer('HTTP_REFERER');
    //     $rules = [
    //         'pasienName' => rv('required', ['required' => 'Nama Pasien Harus Diisi']),
    //     ];
    //     if (!$this->validate($rules)) {
    //         return redirect()->to($url)->withInput();
    //     };
    //     $data = array(
    //         'pasienName' => $this->request->getVar('pasienName'),
    //         'pasienModifiedBy' => user()->email
    //     );

    //     if ($this->pasienModel->updateData(['uuid' => $id], $data)) :
    //         session()->setFlashdata('success', 'Pasien Berhasil Diupdate');
    //     else :
    //         session()->setFlashdata('error', 'Pasien Gagal Diupdate');
    //     endif;
    //     return redirect()->to($url);
    // }

    // public function delete($id)
    // {
    //     $url = $this->request->getServer('HTTP_REFERER');
    //     if ($this->pasienModel->deleteData(['uuid' => $id])) :
    //         session()->setFlashdata('success', 'Pasien Berhasil Dihapus');
    //     else :
    //         session()->setFlashdata('error', 'Pasien Gagal Dihapus');
    //     endif;
    //     return redirect()->to($url);
    // }
}
