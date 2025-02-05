
// Data yang akan dienkripsi ke QR Code
const dataSiswa = {
    nomor_induk: "<?php echo $siswa['nomor_induk']; ?>",
    nama_siswa: "<?php echo $siswa['nama_siswa']; ?>",
    kelas: "<?php echo $siswa['kelas']; ?>",
    nama_orang_tua: "<?php echo $siswa['nama_orang_tua']; ?>"
};

// Konversi data ke format JSON
const dataString = JSON.stringify(dataSiswa);

// Generate QR Code
const qrcode = new QRCode(document.getElementById("qrcode"), {
    text: dataString,
    width: 160,
    height: 160
});