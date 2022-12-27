$(document).ready(function() {
    var typingTimer;
    var doneTypingInterval = 3000;
    var $input = $('[name="nik"]');
    $('.btnLanjut').hide()
    $('.btnExport').hide()
    let existsData = false;
    let pasienId = '';
    var myUrl = window.location.href;
    var base_url = myUrl.split('?')[0];


    const nik = $('[name="nik"]').val()
        // console.log((nik.toString() == ""));
    if (nik.toString() != "") {
        doneTyping()
    }

    $input.on('keyup', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });

    $input.on('keydown', function() {
        clearTimeout(typingTimer);
    });

    function doneTyping() {
        let nik = (($('[name="nik"]').val() == '') ? null : $('[name="nik"]').val())
        if (nik != null) {
            $.ajax({
                url: "/pasien/" + nik,
                type: "GET",
                success: function(response) {
                    const body = JSON.parse(response).data
                    if (body.length > 0) {
                        displayMessage('Pasien Ditemukan')
                        $('[name="pasienName"]').val(body[0].pasienName)
                        $('[name="pasienAge"]').val(body[0].pasienAge)
                        $('[name="address"]').val(body[0].pasienAddress)
                        $('[name="accupation"]').val(body[0].pasienAccupation)
                        $('[name="unit"]').val(body[0].pasienUnit)
                        $('[name="jk"]').val(body[0].pasienSex).change();
                        $('[name="status"]').val(body[0].pasienStatus).change();
                        existsData = true;
                        pasienId = body[0].pasienId
                        $('.btnLanjut').show()
                        rekammedis(body[0].nik)
                    } else {
                        displayMessageError('Pasien Tidak Ada Di Database')
                        $('[name="pasienName"]').val('')
                        $('[name="pasienAge"]').val('')
                        $('[name="address"]').val('')
                        $('[name="accupation"]').val('')
                        $('[name="unit"]').val('')
                        $('[name="jk"]').val('').change()
                        $('[name="status"]').val('').change()
                        pasienId = ''
                        existsData = false;
                        $('.data-rekam-medis').empty()
                        html = ""
                        html += '<tr>'
                        html += '<td colspan="4" align="center">Data Tidak ditemukan</td>'
                        html += '</tr>'
                        $('.data-rekam-medis').append(html)
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                },
            })
        }
    }

    $('[name="jk"], [name="status"]').change(function() {
        cekInfterace()
    });

    $('[name="pasienName"], [name="pasienAge"], [name="address"], [name="accupation"], [name="unit"]').on('keyup', function() {
        cekInfterace()
    });

    $('[name="proses"]').click(function() {
        const poliId = $('[name="poliId"]').val()

        if (existsData) {
            const nik = $('[name="nik"]').val()
            console.log('langsung ke poli')
            const data = {
                'pemeriksaanPasienId': pasienId,
                'pemeriksaanPoliId': poliId,
                'pemeriksaanCreatedBy': $('[name="emailUser"]').val()
            };
            savePemeriksaan(data)
            addQSParm('n', nik)
            window.location.replace(myUrl)
        } else {
            const nik = $('[name="nik"]').val()
            const pasienName = $('[name="pasienName"]').val()
            const pasienAge = $('[name="pasienAge"]').val()
            const address = $('[name="address"]').val()
            const accupation = $('[name="accupation"]').val()
            const unit = $('[name="unit"]').val()
            const jk = $('[name="jk"]').val()
            const status = $('[name="status"]').val()
            const dataPasien = {
                'nik': nik,
                'pasienName': pasienName,
                'pasienAge': pasienAge,
                'address': address,
                'accupation': accupation,
                'unit': unit,
                'jk': jk,
                'status': status,
                'email': $('[name="emailUser"]').val()
            };
            $.ajax({
                url: "/pasien",
                type: "POST",
                data: dataPasien,
                success: function(response) {
                    const data = {
                        'pemeriksaanPasienId': JSON.parse(response).data,
                        'pameriksaanPoliId': poliId,
                        'pemeriksaanCreatedBy': $('[name="emailUser"]').val()
                    }
                    savePemeriksaan(data)
                    addQSParm('n', nik)
                    window.location.replace(myUrl)
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                },
            })
        }
    })

    function rekammedis(nik) {
        $('.data-rekam-medis').empty()

        $.ajax({
            url: "/pemeriksaan/" + nik,
            type: "GET",
            success: function(response) {
                let no = 1
                let html = ''
                if (JSON.parse(response).data.length > 0) {
                    JSON.parse(response).data.forEach(element => {
                        let diagnosa = ""
                        if (element.pemeriksaanDiagnosa === null) {
                            diagnosa = '<div class="badge badge-warning">Butuh tindakan</div>'
                        } else {
                            diagnosa = element.pemeriksaanDiagnosa
                        }
                        html += '<tr>'
                        html += '<td align="center">' + no++ + '</td>'
                        html += '<td>' + element.pemeriksaanCreatedDate + '</td>'
                        html += '<td>' + diagnosa + '</td>'
                        html += '<td align="center"><button button class="btn btn-primary" ' + ((element.pemeriksaanDiagnosa === null) ? 'disabled' : '') + ' onclick = "showMore(' + element.pemeriksaanId + ')" > <i class="fas fa-list-ul"></i> Read more</button > <button class="btn btn-success" ' + ((element.pemeriksaanResep === null) ? 'disabled' : '') + ' onclick = "exportResep(' + element.pemeriksaanId + ',' + '`' + element.pemeriksaanResep + '`' + ')"><i class="fas fa-pills"></i> Resep</button></td >'
                        html += '</tr>'
                    })
                    $('.btnExport').show()
                } else {
                    html += '<tr>'
                    html += '<td colspan="4" align="center">Data Tidak ditemukan</td>'
                    html += '</tr>'
                }
                $('.data-rekam-medis').append(html)
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            },
        })
    }

    function savePemeriksaan(data) {
        $.ajax({
            url: "/pemeriksaan",
            type: "POST",
            data: data,
            success: function(response) {
                console.log(JSON.parse(response));
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            },
        })
    }

    function cekInfterace() {
        if ($('[name="pasienName"]').val() !== '' &&
            $('[name="address"]').val() !== '' &&
            $('[name="accupation"]').val() !== '' &&
            $('[name="unit"]').val() !== '' &&
            $('[name="jk"]').val() !== '' &&
            $('[name="status"]').val() !== ''
        ) {
            $('.btnLanjut').show()
        } else {
            $('.btnLanjut').hide()
        }
    }

    function displayMessageError(message) {
        iziToast.show({
            theme: "dark",
            icon: "fas fa-times",
            title: "Error",
            message: message,
            position: "topRight",
            progressBarColor: "rgb(255, 0, 72)",
        });
    }

    function displayMessage(message) {
        iziToast.show({
            theme: "dark",
            icon: "fas fa-check",
            title: "Notification",
            message: message,
            position: "topRight",
            progressBarColor: "rgb(0, 255, 184)",
        });
    }

    $('.lanjut').click(function() {
        $('#anamnese').modal('show')
    })

    $('.pilihPsn').click(function() {
        const nik = $("input[type='radio']:checked").val()
        addQSParm('n', nik)
        window.location.replace(myUrl)
    })

    $("#table1").dataTable({
        "columnDefs": [
            { "sortable": false, "targets": [1, 2, 3] }
        ]
    });

    function addQSParm(name, value) {
        var re = new RegExp("([?&]" + name + "=)[^&]+", "");

        function add(sep) {
            myUrl += sep + name + "=" + encodeURIComponent(value);
        }

        function change(nama, value) {
            var forMaintenance = new URLSearchParams(window.location.search);
            $urlke = 1;
            if (value == "") {
                forMaintenance.delete(nama);
            }

            for (const [k, v] of forMaintenance) {
                if (nama == k && v == value) {
                    if ($urlke == 1) {
                        base_url += "?" + k + "=" + encodeURIComponent(v);
                    } else {
                        base_url += "&" + k + "=" + encodeURIComponent(v);
                    }
                } else if (nama == k && v != value) {
                    if ($urlke == 1) {
                        base_url += "?" + k + "=" + encodeURIComponent(value);
                    } else {
                        base_url += "&" + k + "=" + encodeURIComponent(value);
                    }
                } else if (nama != k && v != value) {
                    if ($urlke == 1) {
                        base_url += "?" + k + "=" + encodeURIComponent(v);
                    } else {
                        base_url += "&" + k + "=" + encodeURIComponent(v);
                    }
                }
                $urlke++;
            }

            myUrl = base_url;
        }


        if (myUrl.indexOf("?") === -1) {
            add("?");
        } else {
            if (re.test(myUrl)) {
                change(name, value);
            } else {
                add("&");
            }
        }
    }
})

function showMore(id) {
    $.ajax({
        url: "/pemeriksaan/detail/" + id,
        type: "POSt",
        success: function(response) {
            let pemeriksaan = JSON.parse(response).data
            let html = ''
            pemeriksaan.forEach(data => {
                html += '<div class="table-responsive">';
                html += '<table class="table table-striped table-bordered">';
                html += '<tbody>';
                html += '<tr>';
                html += '<th>Anamnese</th>';
                html += '<td>' + data.pemeriksaanAnamnese + '</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<th>Therapy</th>';
                html += '<td>' + data.pemeriksaanTherapy + '</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<th>Dokter</th>';
                html += '<td>' + data.dokterName + '</td>';
                html += '</tr>';
            })
            $('#data-pemeriksaan').empty()
            $('#more').modal('show')
            $('#data-pemeriksaan').append(html)
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        },
    })
}

function exportResep(id, data) {
    $('[name="pemeriksaanId"]').val(id)
    let html = ''
    html += data;
    html += '<p class="text-warning"><small>Klik tombol <b>export</b> untuk meng-export resep</small></p>';
    $('#data-resep').empty()
    $('#resep').modal('show')
    $('#data-resep').append(html)
}