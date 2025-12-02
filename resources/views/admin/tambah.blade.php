<!DOCTYPE html>
<html>
<head>
    <title>Tambah Mahasiswa</title>
</head>
<body>

<h2>Tambah Data Mahasiswa</h2>

@if (session('success'))
    <p style="color: green">{{ session('success') }}</p>
@endif

<form action="{{ route('mahasiswa.store') }}" method="POST">
    @csrf
    <label>NPM :</label><br>
    <input type="text" name="npm"><br><br>

    <label>Nama :</label><br>
    <input type="text" name="nama"><br><br>

    <label>Tanggal Lahir :</label><br>
    <input type="date" name="tgl_lahir"><br><br>

    <label>Jenis Kelamin :</label><br>
    <select name="jenis_kelamin">
        <option value="L">Laki-Laki</option>
        <option value="P">Perempuan</option>
    </select><br><br>

    <label>Pilih Jurusan :</label><br>
    <select id="jurusan">
        <option value="">-- Pilih Jurusan --</option>
        @foreach ($jurusan as $j)
            <option value="{{ $j->id }}">{{ $j->nama_jurusan }}</option>
        @endforeach
    </select><br><br>

    <label>Pilih Prodi :</label><br>
    <select id="prodi">
        <option value="">-- Pilih Prodi --</option>
    </select><br><br>

    <label>Pilih Kelas :</label><br>
    <select name="id_kelas" id="kelas">
        <option value="">-- Pilih Kelas --</option>
    </select><br><br>

    <button type="submit">Tambah Mahasiswa</button>
</form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Ketika jurusan dipilih → ambil prodi
    $("#jurusan").change(function () {
        var id_jurusan = $(this).val();

        $("#prodi").html("<option>Loading...</option>");

        $.get("/get-prodi/" + id_jurusan, function (data) {
            $("#prodi").html("<option value=''>-- Pilih Prodi --</option>");
            data.forEach(function (item) {
                $("#prodi").append(`<option value="${item.id}">${item.nama_prodi}</option>`);
            });
        });
    });

    // Ketika prodi dipilih → ambil kelas
    $("#prodi").change(function () {
        var id_prodi = $(this).val();

        $("#kelas").html("<option>Loading...</option>");

        $.get("/get-kelas/" + id_prodi, function (data) {
            $("#kelas").html("<option value=''>-- Pilih Kelas --</option>");
            data.forEach(function (item) {
                $("#kelas").append(`<option value="${item.id}">${item.nama_kelas}</option>`);
            });
        });
    });
</script>

</body>
</html>
