<?php
namespace App\Controllers\Peserta;

use App\Controllers\BaseController;
use App\Models\UjianModel;
use App\Models\SoalModel;
use App\Models\JawabanUjianModel;
use App\Models\UjianPesertaModel;

class Ujian extends BaseController
{
    protected $ujianModel;
    protected $soalModel;
    protected $jawabanModel;
    protected $ujianPesertaModel;

    public function __construct()
    {
        $this->ujianModel        = new UjianModel();
        $this->soalModel         = new SoalModel();
        $this->jawabanModel      = new JawabanUjianModel();
        $this->ujianPesertaModel = new UjianPesertaModel();
    }

    public function index($ujianId)
    {
        $pesertaId = session('id');
        if (!$pesertaId) {
            return redirect()->to('peserta/dashboard');
        }

        $ujian = $this->ujianModel->where('id', $ujianId)->first();
        if (!$ujian) {
            return redirect()->to('peserta/dashboard')->with('error', 'Ujian tidak ditemukan.');
        }

        $terdaftar = $this->ujianPesertaModel
            ->where('ujian_id', $ujianId)
            ->where('peserta_id', $pesertaId)
            ->first();

        if (!$terdaftar) {
            return redirect()->to('peserta/dashboard')->with('error', 'Anda tidak terdaftar dalam ujian ini.');
        }

        $soalList = $this->soalModel
            ->where('kategori_id', $ujian['kategori_id'])
            ->findAll($ujian['jumlah_soal']);

        if (count($soalList) < $ujian['jumlah_soal']) {
            return redirect()->to('peserta/dashboard')->with('error', 'Soal tidak mencukupi untuk ujian ini.');
        }

        if ($ujian['acak_soal']) {
            shuffle($soalList);
        }

        $jawaban = [];
        foreach ($this->jawabanModel->where([
            'ujian_id' => $ujianId,
            'peserta_id' => $pesertaId
        ])->findAll() as $j) {
            $jawaban[$j['soal_id']] = $j['jawaban'];
        }

        return view('peserta/ujian/index', [
            'ujian'     => $ujian,
            'soalList'  => $soalList,
            'jawaban'   => $jawaban,
            'pesertaId' => $pesertaId,
        ]);
    }

    public function simpanJawaban()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $data      = $this->request->getPost();
        $ujianId   = $data['ujian_id'];
        $soalId    = $data['soal_id'];
        $jawaban   = $data['jawaban'];
        $pesertaId = session('id');

        if (!$pesertaId) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'unauthorized']);
        }

        $existing = $this->jawabanModel
            ->where([
                'ujian_id' => $ujianId,
                'soal_id' => $soalId,
                'peserta_id' => $pesertaId
            ])->first();

        if ($existing) {
            $this->jawabanModel->update($existing['id'], ['jawaban' => $jawaban]);
        } else {
            $this->jawabanModel->insert([
                'ujian_id' => $ujianId,
                'soal_id' => $soalId,
                'peserta_id' => $pesertaId,
                'jawaban' => $jawaban
            ]);
        }

        return $this->response->setJSON(['status' => 'success']);
    }
    public function selesai()
{
    $ujianId = $this->request->getPost('ujian_id');
    $pesertaId = session('id');

    $this->ujianPesertaModel
        ->where('ujian_id', $ujianId)
        ->where('peserta_id', $pesertaId)
        ->set(['selesai' => date('Y-m-d H:i:s'), 'status' => 'selesai'])
        ->update();

    return redirect()->to('peserta/dashboard')->with('success', 'Ujian telah diselesaikan.');
}

}
