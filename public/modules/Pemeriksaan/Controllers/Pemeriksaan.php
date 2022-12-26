<?php

/* 
This is Controller Krs
 */

namespace Modules\Pemeriksaan\Controllers;

use App\Controllers\BaseController;
use Modules\Pemeriksaan\Models\PemeriksaanModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;


class Pemeriksaan extends BaseController
{
    protected $pemeriksaanModel;
    protected $spreadsheet;
    protected $ReaderHtml;


    public function __construct()
    {
        $this->pemeriksaanModel = new PemeriksaanModel();
        $this->spreadsheet = new Spreadsheet();
        $this->ReaderHtml = new \PhpOffice\PhpSpreadsheet\Reader\Html();
    }

    public function index()
    {
        $currentPage = $this->request->getVar('page_pemeriksaan') ? $this->request->getVar('page_pemeriksaan') : 1;
        $keyword = $this->request->getVar('keyword');
        $email = user()->email;
        $pemeriksaan = $this->pemeriksaanModel->getPemeriksaan($email, $keyword);
        $data = [
            'menu' => $this->fetchMenu(),
            'title' => "Pemeriksaan",
            'breadcrumb' => ['Administrasi', 'Pemeriksaan'],
            'pemeriksaan' => $pemeriksaan->paginate($this->numberPage, 'pemeriksaan'),
            'currentPage' => $currentPage,
            'numberPage' => $this->numberPage,
            'pager' => $pemeriksaan->pager,
            'validation' => \Config\Services::validation(),
        ];
        return view('Modules\Pemeriksaan\Views\pemeriksaan', $data);
    }

    public function getById($nik)
    {
        $pemeriksaan = $this->pemeriksaanModel->getById(['nik' => $nik])->findAll();
        echo json_encode(['data' => $pemeriksaan]);
    }

    public function getDetail($id)
    {
        $pemeriksaan = $this->pemeriksaanModel->getById(['pemeriksaan."pemeriksaanId"' => $id])->findAll();
        echo json_encode(['data' => $pemeriksaan]);
    }

    // public function getByPasienId($pasienId)
    // {
    //     $pemeriksaan = $this->pemeriksaanModel->getById(['"pasienId"' => $pasienId]);
    //     echo json_encode(['data' => $pemeriksaan->findAll()]);
    // }

    public function add()
    {
        $data = array(
            'pemeriksaanPasienId' => $this->request->getVar('pemeriksaanPasienId'),
            'pemeriksaanPoliId' => $this->request->getVar('pemeriksaanPoliId'),
            'pemeriksaanCreatedBy' => $this->request->getVar('pemeriksaanCreatedBy'),
        );

        if ($this->pemeriksaanModel->insert($data)) :
            echo json_encode(['status' => true, 'msg' => 'Pemeriksaan Berhasil Ditambah']);
        else :
            echo json_encode(['status' => false, 'msg' => 'Pemeriksaan Gagal Ditambah']);
        endif;
    }

    public function edit($id)
    {
        $url = $this->request->getServer('HTTP_REFERER');
        $rules = [
            'anamnese' => rv('required', ['required' => 'Anamnese Harus Diisi']),
            'diagnosa' => rv('required', ['required' => 'Diagnosa Harus Diisi']),
            'therapy' => rv('required', ['required' => 'Therapy Harus Diisi']),
        ];
        if (!$this->validate($rules)) {
            return redirect()->to($url)->withInput();
        };
        $data = array(
            'pemeriksaanAnamnese' => $this->request->getVar('anamnese'),
            'pemeriksaanDiagnosa' => $this->request->getVar('diagnosa'),
            'pemeriksaanTherapy' => $this->request->getVar('therapy'),
            'pemeriksaanModifiedBy' => user()->email
        );

        if ($this->pemeriksaanModel->updateData(['pemeriksaanId' => $id], $data)) :
            session()->setFlashdata('success', 'Pemeriksaan Berhasil Diupdate');
        else :
            session()->setFlashdata('error', 'Pemeriksaan Gagal Diupdate');
        endif;
        return redirect()->to($url);
    }

    public function delete($id)
    {
        $url = $this->request->getServer('HTTP_REFERER');
        if ($this->pemeriksaanModel->deleteData(['uuid' => $id])) :
            session()->setFlashdata('success', 'Pemeriksaan Berhasil Dihapus');
        else :
            session()->setFlashdata('error', 'Pemeriksaan Gagal Dihapus');
        endif;
        return redirect()->to($url);
    }

    public function exportRekamMedis()
    {
        $id = $this->request->getVar('identitas');
        $data = $this->pemeriksaanModel->getById(['pasien."nik"' => $id])->findAll();
        $nama = $data[0]->pasienName;
        $umur = $data[0]->pasienAge;
        $alamat = $data[0]->pasienAddress;
        $pekerjaan = $data[0]->pasienAccupation;
        $unit = $data[0]->pasienUnit;
        $status = strtoupper($data[0]->pasienStatus);

        $this->spreadsheet = new Spreadsheet();
        $htmlString = '';
        $htmlString .= '<table>';
        $htmlString .= '<tr>';
        $htmlString .= '<th width="200%" style="font-weight: bold">NAMA</th>';
        $htmlString .= '<td width="200%" >' . $nama . '</td>';
        $htmlString .= '<th width="200%" style="font-weight: bold">PEKERJAAN</th>';
        $htmlString .= '<td width="200%">' . $pekerjaan . '</td>';
        $htmlString .= '</tr>';
        $htmlString .= '<tr>';
        $htmlString .= '<th style="font-weight: bold">UMUR</th>';
        $htmlString .= '<td>' . $umur . '</td>';
        $htmlString .= '<th style="font-weight: bold">UNIT KERJA</th>';
        $htmlString .= '<td>' . $unit . '</td>';
        $htmlString .= '</tr>';
        $htmlString .= '<tr>';
        $htmlString .= '<th style="font-weight: bold">ALAMAT</th>';
        $htmlString .= '<td>' . $alamat . '</td>';
        $htmlString .= '<th style="font-weight: bold">STATUS</th>';
        $htmlString .= '<td>' . $status . '</td>';
        $htmlString .= '</tr>';
        $htmlString .= '<tr>';
        $htmlString .= '<td></td>';
        $htmlString .= '<td></td>';
        $htmlString .= '<td></td>';
        $htmlString .= '<td></td>';
        $htmlString .= '</tr>';
        $htmlString .= '<tr>';
        $htmlString .= '<th align="center" style="font-weight: bold; border: solid black">TANGGAL/WAKTU</th>';
        $htmlString .= '<th align="center" style="font-weight: bold; border: solid black">ANAMNESE</th>';
        $htmlString .= '<th align="center" style="font-weight: bold; border: solid black">DIAGNOSA</th>';
        $htmlString .= '<th align="center" style="font-weight: bold; border: solid black">THERAPY</th>';
        $htmlString .= '</tr>';
        foreach ($data as $key => $value) {
            $htmlString .= '<tr>';
            $htmlString .= '<td valign="center" style="border: solid black">' . $value->pemeriksaanCreatedDate . '</td>';
            $htmlString .= '<td valign="center" style="border: solid black">' . $value->pemeriksaanAnamnese . '  </td>';
            $htmlString .= '<td valign="center" style="border: solid black">' . $value->pemeriksaanDiagnosa . '  </td>';
            $htmlString .= '<td valign="center" style="border: solid black">' . $value->pemeriksaanTherapy . '  </td>';
            $htmlString .= '</tr>';
        }
        $htmlString .= '</table>';

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Html($this->spreadsheet);
        $this->spreadsheet = $reader->loadFromString($htmlString);

        $writer = new Xls($this->spreadsheet);
        $fileName = 'rekam_medis_' . $nama;
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '.xls"');
        header('Cache-Control: max-age=0');

        ob_end_clean();
        $writer->save('php://output');
    }

    public function exportResep()
    {
        $id = $this->request->getVar('pemeriksaanId');
        $data = $this->pemeriksaanModel->getById(['pemeriksaan."pemeriksaanId"' => $id])->findAll();
        $dataCetak = ['data' => $data];
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A5']);
        $mpdf->WriteHTML(view('Modules\Pemeriksaan\Views\cetakResep', $dataCetak));
        return redirect()->to($mpdf->Output('resep_' . $data[0]->pasienName . '.pdf', 'I'));
    }
}
