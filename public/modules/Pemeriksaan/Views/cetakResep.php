<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>resep_<?= $data[0]->pasienName; ?></title>
    <style>
        div.header {
            height: 120;
            width: 100%;
        }

        div.identitas-dokter {
            float: left;
            position: relative;
            width: 250;
        }

        div.identitas-dokter p {
            margin: 0px 0px 2px 0px;
        }

        div.tanggal {
            float: right;
            position: relative;
            width: 150;
            text-align: right;
        }

        div.tanggal p {
            margin: 0px 0px 0px 0px;
        }

        div.resep {
            height: 370;
            width: 100%;
            margin: 20px 0px 0px 0px;
        }

        div.ttd {
            float: right;
            width: 200;
            height: 10;

        }

        div.ttd p {
            margin: 0px 0px 0px 0px;
        }

        hr {
            margin: 0px 0px 0px 0px;
        }

        .name {
            text-align: center;
            text-decoration: underline;
        }

        .sip {
            text-align: center;
            font-size: 8px;
        }

        div.identitas-pasien {
            position: relative;
            width: 100%;
            margin: 5px 0px 0px 0px;
        }

        div.identitas-pasien p {
            margin: 0px 0px 0px 0px;
        }
    </style>
</head>

<body>
    <div class="header"></div>
    <div class="identitas-dokter">
        <p><?= $data[0]->dokterName ?></p>
        <p><?= $data[0]->dokterAlamat ?></p>
        <p><?= $data[0]->dokterNoHp ?></p>
    </div>
    <div class="tanggal">
        <p>Medan, <?= date('d-m-Y') ?></p>
    </div>
    <div class="resep">
        <?= $data[0]->pemeriksaanResep; ?>
    </div>
    <div class="ttd">
        <p class="name"><?= $data[0]->dokterName; ?></p>
        <p class="sip">SIP : <?= $data[0]->dokterSip; ?></p>
    </div>
    <div class="identitas-pasien">
        <p>Pro : <?= $data[0]->pasienName; ?></p>
        <p>Umur : <?= $data[0]->pasienAge; ?></p>
        <p>Jenis Kelamin : <?= $data[0]->pasienSex; ?></p>
    </div>
</body>

</html>