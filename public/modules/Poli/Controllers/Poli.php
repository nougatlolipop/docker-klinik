<?php

/* 
This is Controller Krs
 */

namespace Modules\Poli\Controllers;

use App\Controllers\BaseController;
use Modules\Poli\Models\PoliModel;


class Poli extends BaseController
{
    protected $poli;

    public function __construct()
    {
        $this->poliModel = new PoliModel();
    }

    public function index()
    {
        $currentPage = $this->request->getVar('page_poli') ? $this->request->getVar('page_poli') : 1;
        $keyword = $this->request->getVar('keyword');
        $poli = $this->poliModel->getPoli($keyword);
        $data = [
            'menu' => $this->fetchMenu(),
            'title' => "Poli",
            'breadcrumb' => ['Data', 'Poli'],
            'poli' => $poli->paginate($this->numberPage, 'poli'),
            'currentPage' => $currentPage,
            'numberPage' => $this->numberPage,
            'pager' => $poli->pager,
            'validation' => \Config\Services::validation(),
        ];
        return view('Modules\Poli\Views\poli', $data);
    }

    public function add()
    {
        $url = $this->request->getServer('HTTP_REFERER');
        $rules = [
            'poliName' => rv('required', ['required' => 'Nama Poli Harus Diisi']),
        ];
        if (!$this->validate($rules)) {
            return redirect()->to($url)->withInput();
        };
        $data = array(
            'uuid' => uuid(),
            'poliName' => $this->request->getVar('poliName'),
            'poliCretatedBy' => user()->email
        );

        if ($this->poliModel->insert($data)) :
            session()->setFlashdata('success', 'Poli Berhasil DiTambah');
        else :
            session()->setFlashdata('error', 'Poli Gagal DiTambah');
        endif;
        return redirect()->to($url);
    }

    public function edit($id)
    {
        $url = $this->request->getServer('HTTP_REFERER');
        $rules = [
            'poliName' => rv('required', ['required' => 'Nama Poli Harus Diisi']),
        ];
        if (!$this->validate($rules)) {
            return redirect()->to($url)->withInput();
        };
        $data = array(
            'poliName' => $this->request->getVar('poliName'),
            'poliModifiedBy' => user()->email
        );

        if ($this->poliModel->updateData(['uuid' => $id], $data)) :
            session()->setFlashdata('success', 'Poli Berhasil Diupdate');
        else :
            session()->setFlashdata('error', 'Poli Gagal Diupdate');
        endif;
        return redirect()->to($url);
    }

    public function delete($id)
    {
        $url = $this->request->getServer('HTTP_REFERER');
        if ($this->poliModel->deleteData(['uuid' => $id])) :
            session()->setFlashdata('success', 'Poli Berhasil Dihapus');
        else :
            session()->setFlashdata('error', 'Poli Gagal Dihapus');
        endif;
        return redirect()->to($url);
    }
}
