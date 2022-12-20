<?php

/* 
This is Controller Krs
 */

namespace Modules\Dokter\Controllers;

use App\Controllers\BaseController;
use Modules\Dokter\Models\DokterModel;
use Myth\Auth\Entities\User;

class Dokter extends BaseController
{
    protected $dokterModel;
    protected $config;
    protected $session;
    protected $auth;

    public function __construct()
    {
        $this->dokterModel = new DokterModel();
        $this->session = service('session');
        $this->config = config('Auth');
        $this->auth = service('authentication');
    }

    public function index()
    {
        $currentPage = $this->request->getVar('page_dokter') ? $this->request->getVar('page_dokter') : 1;
        $keyword = $this->request->getVar('keyword');
        $dokter = $this->dokterModel->getDokter($keyword);
        $data = [
            'menu' => $this->fetchMenu(),
            'title' => "Dokter",
            'breadcrumb' => ['Data', 'Dokter'],
            'dokter' => $dokter->paginate($this->numberPage, 'dokter'),
            'currentPage' => $currentPage,
            'numberPage' => $this->numberPage,
            'pager' => $dokter->pager,
            'validation' => \Config\Services::validation(),
        ];
        return view('Modules\Dokter\Views\dokter', $data);
    }

    public function add()
    {
        $url = $this->request->getServer('HTTP_REFERER');
        $rules = [
            'dokterName' => rv('required', ['required' => 'Nama Dokter Harus Diisi']),
            'dokterEmail' => rv('required', ['required' => 'Email Dokter Harus Diisi']),
            'username' => rv('required|min_length[3]|max_length[30]|is_unique[users.username]', [
                'required' => 'Username Harus Diisi',
                'min_length' => 'Username Kurang Dari 3 Karakter',
                'max_length' => 'Username Setidaknya Tidak Lebih Dari 30 Karakter',
                'is_unique' => 'Username Sudah Digunakan'
            ]),
            'password' => rv('required', ['required' => 'Password Harus Diisi']),
        ];
        if (!$this->validate($rules)) {
            return redirect()->to($url)->withInput();
        };

        // Start Save the user
        $users = model(UserModel::class);
        $dataUser = [
            'password' => trim($this->request->getPost('password')),
            'email' => trim($this->request->getPost('dokterEmail')),
            'username' => trim($this->request->getPost('username')),
            'active' => 1,
        ];
        $user = new User($dataUser);

        $this->config->requireActivation === null ? $user->activate() : $user->generateActivateHash();
        $users = $users->withGroup('dokter');

        if (!$users->save($user)) {
            return redirect()->to($url)->withInput()->with('errors', $users->errors());
        }
        // End Save the user

        $data = array(
            'uuid' => uuid(),
            'dokterName' => $this->request->getVar('dokterName'),
            'dokterEmail' => $this->request->getVar('dokterEmail'),
            'dokterCretatedBy' => user()->email
        );

        if ($this->dokterModel->insert($data)) :
            session()->setFlashdata('success', 'Dokter Berhasil Ditambah');
        else :
            session()->setFlashdata('error', 'Dokter Gagal Ditambah');
        endif;
        return redirect()->to($url);
    }

    public function edit($id)
    {
        $url = $this->request->getServer('HTTP_REFERER');
        $rules = [
            'dokterName' => rv('required', ['required' => 'Nama Dokter Harus Diisi']),
        ];
        if (!$this->validate($rules)) {
            return redirect()->to($url)->withInput();
        };
        $data = array(
            'dokterName' => $this->request->getVar('dokterName'),
            'dokterEmail' => $this->request->getVar('dokterEmail'),
            'dokterModifiedBy' => user()->email
        );

        if ($this->dokterModel->updateData(['uuid' => $id], $data)) :
            session()->setFlashdata('success', 'Dokter Berhasil Diupdate');
        else :
            session()->setFlashdata('error', 'Dokter Gagal Diupdate');
        endif;
        return redirect()->to($url);
    }

    public function delete($id)
    {
        $url = $this->request->getServer('HTTP_REFERER');
        if ($this->dokterModel->deleteData(['uuid' => $id])) :
            session()->setFlashdata('success', 'Dokter Berhasil Dihapus');
        else :
            session()->setFlashdata('error', 'Dokter Gagal Dihapus');
        endif;
        return redirect()->to($url);
    }
}
