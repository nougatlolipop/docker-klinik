<?= $this->extend('layout/templateHome'); ?>

<?= $this->section('content'); ?>

<?= view('layout/templateSidebar', ['menus' => $menu]); ?>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
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
                        <div class="card-header">
                            <h4></h4>
                            <div class="card-header-form">
                                <h4></h4>
                                <div class="card-header-form">
                                    <form action="">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search" name="keyword" value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : "" ?>">
                                            <div class="input-group-btn">
                                                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty(session()->getFlashdata('success'))) : ?>
                                <?= view('layout/templateAlertIcon', ['msg' => ['success', 'fas fa-check', 'Sukses!', session()->getFlashdata('success')]]); ?>
                            <?php endif; ?>
                            <?php if ($validation->hasError('anamnese')) : ?>
                                <?= view('layout/templateAlertIcon', ['msg' => ['danger', 'fas fa-exclamation', 'Gagal!', $validation->getError('anamnese')]]); ?>
                            <?php endif; ?>
                            <?php if ($validation->hasError('diagnosa')) : ?>
                                <?= view('layout/templateAlertIcon', ['msg' => ['danger', 'fas fa-exclamation', 'Gagal!', $validation->getError('diagnosa')]]); ?>
                            <?php endif; ?>
                            <?php if ($validation->hasError('therapy')) : ?>
                                <?= view('layout/templateAlertIcon', ['msg' => ['danger', 'fas fa-exclamation', 'Gagal!', $validation->getError('therapy')]]); ?>
                            <?php endif; ?>
                            <?php if (!empty(session()->getFlashdata('error'))) : ?>
                                <?= view('layout/templateAlertIcon', ['msg' => ['danger', 'fas fa-check', 'Gagal!', session()->getFlashdata('error')]]); ?>
                            <?php endif; ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="text-align:center" scope="col" width="5%">No.</th>
                                            <th scope="col">Tanggal/Waktu</th>
                                            <th scope="col">Nama Pasien</th>
                                            <th scope="col">Umur Pasien</th>
                                            <th scope="col">Alamat Pasien</th>
                                            <th scope="col">Status</th>
                                            <th width="20%" style="text-align:center" scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($pemeriksaan)) : ?>
                                            <?php
                                            $no = 1 + ($numberPage * ($currentPage - 1));
                                            foreach ($pemeriksaan as $pl) : ?>
                                                <tr>
                                                    <td style="text-align:center" scope="row"><?= $no++; ?></td>
                                                    <td><?= $pl->pemeriksaanCreatedDate; ?></td>
                                                    <td><?= $pl->pasienName; ?></td>
                                                    <td><?= $pl->pasienAge; ?></td>
                                                    <td><?= $pl->pasienAddress; ?></td>
                                                    <td>
                                                        <div class="badge badge-<?= ($pl->pemeriksaanDiagnosa === null) ? 'warning' : 'success'; ?>"><?= ($pl->pemeriksaanDiagnosa === null) ? 'Butuh tindakan' : 'Tertangani'; ?></div>
                                                    </td>
                                                    <td style="text-align:center">
                                                        <button class="btn btn-icon icon-left btn-primary" data-toggle="modal" data-target="#tindakan<?= $pl->pemeriksaanId; ?>" <?= in_groups('dokter') ? '' : 'disabled' ?>><i class="fas fa-stethoscope"></i> Tindakan</button>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                        <?php else : ?>
                                            <?= view('layout/templateEmpty', ['jumlahSpan' => 6]); ?>
                                        <?php endif ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- start modal tambah  -->
<?php foreach ($pemeriksaan as $tindakan) : ?>
    <div class="modal fade" tabindex="-1" role="dialog" id="tindakan<?= $tindakan->pemeriksaanId ?>">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data <?= $title ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/pemeriksaan/<?= $tindakan->pemeriksaanId ?>" method="post">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="_method" value="PUT">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Anamnese</label>
                            <textarea name="anamnese" class="summernote"><?= $tindakan->pemeriksaanAnamnese ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Diagnosa</label>
                            <textarea name="diagnosa" class="summernote"><?= $tindakan->pemeriksaanDiagnosa ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Therapy</label>
                            <textarea name="therapy" class="summernote"><?= $tindakan->pemeriksaanTherapy ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach ?>
<!-- end modal tambah -->


<?= view('layout/templateFooter'); ?>

<?= $this->endSection(); ?>