$(document).ready(function() {
    $("#nama_siswa").change(function() {
        var nama_siswa = $(this).val();
        
        if (nama_siswa) {
            $.ajax({
                url: "../ajax/get_siswa.php",
                type: "GET",
                data: { nama_siswa: nama_siswa },
                dataType: "json",
                success: function(response) {
                    console.log("Response:", response); // Debugging
                    
                    if (response && !response.error) {
                        $("#nomor_induk").val(response.nomor_induk);
                        $("#kelas").val(response.kelas);
                        $("#nama_orang_tua").val(response.nama_orang_tua);
                    } else {
                        alert("Data siswa tidak ditemukan!");
                        $("#nomor_induk, #kelas, #nama_orang_tua").val('');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", xhr.responseText);
                    alert("Terjadi kesalahan saat mengambil data!");
                }
            });
        } else {
            $("#nomor_induk, #kelas, #nama_orang_tua").val('');
        }
    });
});
