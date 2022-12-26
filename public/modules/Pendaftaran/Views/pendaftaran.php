<?= $this->extend('layout/templateHome'); ?>

<?= $this->section('content'); ?>

<?= view('layout/templateSidebar', ['menus' => $menu]); ?>

<!-- Main Content -->
<div class="main-content">
    <section class=" section">
        <div class="section-header">
            <h1><?= $title; ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="#"><?= $breadcrumb[0]; ?></a></div>
                <div class="breadcrumb-item active"><?= $breadcrumb[1]; ?></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <input name="nik" type="text" value="<?= isset($_GET['n']) ? $_GET['n'] : '' ?>" class="form-control" placeholder="No. Identitas (NIK/No. BPJS/NPM)">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#cari"><i class="fas fa-search"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input name="pasienName" type="text" class="form-control" placeholder="Nama Pasien">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input name="pasienAge" type="number" class="form-control" placeholder="Umur">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <select class="form-control select2" name="jk">
                                                    <option value="">Kelamin</option>
                                                    <option value="P">Pria</option>
                                                    <option value="W">Wanita</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <select class="form-control select2" name="status">
                                                    <option value="">Status Pasien</option>
                                                    <option value="bpjs">BPJS</option>
                                                    <option value="umum">UMUM</option>
                                                    <option value="pegawai">Pegawai</option>
                                                    <option value="mahasiswa">Mahasiswa</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input name="address" type="text" class="form-control" placeholder="Alamat">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input name="accupation" type="text" class="form-control" placeholder="Pekerjaan">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input name="unit" type="text" class="form-control" placeholder="Unit Kerja">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row btnLanjut">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button class="btn btn-primary float-right lanjut" data-toggle="modal" data-target="#anamnese">Lanjutkan >></button>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h4 class="title">Rekam Medis</h4>
                                </div>
                                <div class="col-md-6 btnExport">
                                    <div class="form-group">
                                        <button class="btn btn-danger float-right" data-toggle="modal" data-target="#rekamMedis"><i class="fas fa-folder-plus"></i> Rekam Medis</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="text-align:center" scope="col" width="5%">No.</th>
                                                    <th scope="col" width="13%">Tanggal/Waktu</th>
                                                    <th scope="col">Diagnosa</th>
                                                    <th style="text-align:center" scope="col" width="15%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="data-rekam-medis">
                                                <?= view('layout/templateEmpty', ['jumlahSpan' => 4]); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- start modal cari  -->
<div class="modal fade" role="dialog" id="cari">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Pemeriksaan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="table1">
                        <thead>
                            <tr>
                                <th style=" text-align:center" scope="col" width="5%">Pilih</th>
                                <th scope="col">No. Identitas (NIK/No. BPJS/NPM)</th>
                                <th scope="col">Nama Pasien</th>
                                <th scope="col">Umur</th>
                                <th scope="col">Jenis Kelamin</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pasien as $p => $psn) : ?>
                                <tr>
                                    <td style=" text-align:center" width="5%"><input type="radio" name="pasien" id="pasien" value="<?= $psn->nik ?>"></td>
                                    <td><?= $psn->nik ?></td>
                                    <td><?= $psn->pasienName ?></td>
                                    <td><?= $psn->pasienAge ?></td>
                                    <td><?= $psn->pasienSex ?></td>
                                    <td><?= strtoupper($psn->pasienStatus) ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary pilihPsn">Pilih</button>
            </div>
        </div>
    </div>
</div>
<!-- end modal cari -->

<!-- start modal cari  -->
<div class="modal fade" role="dialog" id="anamnese">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tujuan Poli</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <select class="form-control select2" name="poliId">
                        <option value="">Pilih Dokter</option>
                        <?php foreach ($penempatan as $key => $pnmptn) : ?>
                            <option value="<?= $pnmptn->penempatanId ?>"><?= $pnmptn->dokterName ?> (<?= $pnmptn->poliName ?>)</option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" name="proses">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- end modal cari -->

<!-- start modal more  -->
<div class="modal fade" role="dialog" id="more">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pemeriksaan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="data-pemeriksaan">
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- end modal more -->

<!-- start modal rekam medis  -->
<div class="modal fade" role="dialog" id="rekamMedis">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Rekam Medis</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Silahkan klik tombol <strong>Export</strong> untuk mengexport rekam medis pasien ini
            </div>
            <form action="/pemeriksaan/rekamMedis" method="post">
                <input type="hidden" name="identitas" value="<?= isset($_GET['n']) ? $_GET['n'] : '' ?>">
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Export</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end modal rekam medis -->

<!-- start modal resep  -->
<div class="modal fade" role="dialog" id="resep">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Resep</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Silahkan klik tombol <strong>Export</strong> untuk mengexport resep pasien ini
            </div>
            <form action="/pemeriksaan/resep" method="post">
                <input type="hidden" name="pemeriksaanId" value="">
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Export</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end modal resep -->

<?= view('layout/templateFooter'); ?>

<?= $this->endSection(); ?>