let map = L.map('map');
let lat, lng, marker, alamatChanged;

$(document).ready(function () {

    $('#alamat').on('input', function () {
        alamatChanged = true;
    });

    $.ajax({
        type: 'GET',
        url: '../src/wilayahDropdown/getProvinsiOptions.php',
        success: function (response) {
            $('#provinsi').html(response);
        }
    });

    $('#provinsi').change(function () {
        var idProvinsi = $(this).val();

        $.ajax({
            url: '../src/wilayahDropdown/getKabupatenOptions.php',
            data: { idProvinsi: idProvinsi },
            success: function (response) {
                $('#kabupaten').html(response);

                var idKabupaten = $('#kabupaten').val();
                $.ajax({
                    url: '../src/wilayahDropdown/getKecamatanOptions.php',
                    data: { idKabupaten: idKabupaten },
                    success: function (response) {
                        $('#kecamatan').html(response);

                        var idKecamatan = $('#kecamatan').val();
                        $.ajax({
                            url: '../src/wilayahDropdown/getDesaOptions.php',
                            data: { idKecamatan: idKecamatan },
                            success: function (response) {
                                $('#desa').html(response);
                            }
                        });
                    }
                });
            }
        });
    });

    $('#kabupaten').change(function () {
        var idKabupaten = $(this).val();

        $.ajax({
            url: '../src/wilayahDropdown/getKecamatanOptions.php',
            data: { idKabupaten: idKabupaten },
            success: function (response) {
                $('#kecamatan').html(response);

                var idKecamatan = $('#kecamatan').val();

                $.ajax({
                    url: '../src/wilayahDropdown/getDesaOptions.php',
                    data: { idKecamatan: idKecamatan },
                    success: function (response) {
                        $('#desa').html(response);
                    }
                });
            }
        });
    });

    $('#kecamatan').change(function () {
        var idKecamatan = $(this).val();
        $.ajax({
            url: '../src/wilayahDropdown/getDesaOptions.php',
            data: { idKecamatan: idKecamatan },
            success: function (response) {
                $('#desa').html(response);
            }
        });
    });

    $('#desa').change(function () {
        alamatChanged = false;
        var place = $('#desa option:selected').text() + ', ' + $('#kecamatan option:selected').text() + ', ' + $('#provinsi option:selected').text();
        getLatLng($('#desa option:selected').text(), $('#kecamatan option:selected').text(), $('#provinsi option:selected').text());
    });

});

function getLatLng(desa, kecamatan, provinsi) {
    var place = desa + ', ' + kecamatan + ', ' + provinsi + ', Indonesia';

    $.getJSON('https://nominatim.openstreetmap.org/search', {
        q: place,
        format: 'json',
    }, function (data) {
        if (data.length > 0) {
            var lat = data[0].lat;
            var lng = data[0].lon;
            map.removeLayer(marker)
            loadMap(lat, lng);
            return true;
        } else {
            var place = kecamatan + ', ' + provinsi + ', Indonesia';

            $.getJSON('https://nominatim.openstreetmap.org/search', {
                q: place,
                format: 'json',
            }, function (data) {
                if (data.length > 0) {
                    var lat = data[0].lat;
                    var lng = data[0].lon;
                    map.removeLayer(marker)
                    loadMap(lat, lng);
                    return true;
                } else {
                    var place = provinsi + ', Indonesia';
                    $.getJSON('https://nominatim.openstreetmap.org/search', {
                        q: place,
                        format: 'json',
                    }, function (data) {
                        if (data.length > 0) {
                            var lat = data[0].lat;
                            var lng = data[0].lon;
                            map.removeLayer(marker)
                            loadMap(lat, lng);
                            return true;
                        }
                    });
                }
            });
        }
    });
}

function loadMap(lat, lng) {
    $('#lat').val(lat);
    $('#lng').val(lng);
    updateAddress(lat, lng);

    map.setView([lat, lng], 13);
    map.invalidateSize(false);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors'
    }).addTo(map);

    marker = L.marker([lat, lng], {
        draggable: true
    }).addTo(map);

    marker.on('moveend', function () {
        lat = marker.getLatLng().lat;
        lng = marker.getLatLng().lng;
        $('#lat').val(lat);
        $('#lng').val(lng);
        updateAddress(lat, lng);
    });
}


async function updateAddress(lat, lng) {
    if (!alamatChanged || $('#alamat').val().trim() === '') {
        const address = await reverseGeocoding(lat, lng);
        $("#alamat").val(address);
    }
}

async function reverseGeocoding(lat, lng) {
    const response = await fetch(
        "https://nominatim.openstreetmap.org/search.php?q=" +
        lat +
        "," +
        lng +
        "&polygon_geojson=1&format=json"
    );
    const data = await response.json();
    return data[0].display_name;
}
