<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit-Mahasiswa</title>
</head>
<body>
    <h2>Edit Mahasiswa</h2>
    <form action="{{ route('mahasiswa.update', $mhs->id) }}" method="POST">
    @csrf

    <label>Nama:</label>
    <input type="text" name="nama" value="{{ $mhs->nama }}"><br><br>
    <label>NPM:</label>
    <input type="text" name="npm" value="{{ $mhs->npm }}"><br><br>
    <label>Jenis Kelamin:</label><br>
    <select name="jenis_kelamin">
        <option value="L">Laki-Laki</option>
        <option value="P">Perempuan</option>
    </select><br>
    <label>Pilih Jurusan :</label><br>
    <select id="jurusan">
        <option value="{{ $mhs->kelas->prodi->jurusan->id }}">{{ $mhs->kelas->prodi->jurusan->nama_jurusan }}</option>
        @foreach ($jurusan as $j)
            <option value="{{ $j->id }}">{{ $j->nama_jurusan }}</option>
        @endforeach
    </select><br><br>

    <label>Pilih Prodi :</label><br>
    <select id="prodi">
        <option value="{{ $mhs->kelas->prodi->id }}">{{ $mhs->kelas->prodi->nama_prodi }}</option>
    </select><br><br>

    <label>Pilih Kelas :</label><br>
    <select name="id_kelas" id="kelas">
        <option value="{{ $mhs->kelas->id }}">{{ $mhs->kelas->nama_kelas }}</option>
    </select><br><br>

    <button type="submit">Update</button>
</form>
</body>
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
</html>
