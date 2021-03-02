<table border='1'>
    <thead>
        <tr>
            <th scope="col">Foto</th>
            <th scope="col">Nama</th>
            <th scope="col">Umur</th>
            <th scope="col">Alamat</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($pasien1 as $pas): ?>
        <tr>
            <td><?= $pas->nama_pasien; ?></td>
            <td><?= $pas->umur; ?></td>
            <td><?= $pas->alamat ?>... ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>