$(document).ready(function() {
    // Contoh: Tambahkan fungsi untuk validasi form
    $('#formTambahSiswa').submit(function(e) {
        if ($('#nomor_induk').val() === '') {
            alert('Nomor Induk tidak boleh kosong!');
            e.preventDefault();
        }
    });
});