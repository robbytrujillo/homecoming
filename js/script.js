$(document).ready(function() {
    $('#tanggal_pulang').change(function() {
        if ($(this).val()) {
            $('#tanggal_datang').prop('disabled', false);
        } else {
            $('#tanggal_datang').prop('disabled', true);
        }
    });

    // Contoh: Tambahkan fungsi untuk validasi form
    $('#formTambahSiswa').submit(function(e) {
        if ($('#nomor_induk').val() === '') {
            alert('Nomor Induk tidak boleh kosong!');
            e.preventDefault();
        }
    });
});