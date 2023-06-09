<?= $this->extend('layout/v_template'); ?>

<?= $this->section('content'); ?>

<div class="container">
    <div class="row">
        <div class="col">
            <h3>Detail</h3>
            <div class="card" style="width: 18rem;">
                <img src="/img/<?= $komik['sampul'] ?>" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title"><?= $komik['judul'] ?></h5>
                    <p class="card-text">penulis :<?= $komik['penulis'] ?></p>
                    <p class="card-text">penerbit : <?= $komik['penerbit'] ?></p>
                    <a href="/komik/edit/<?= $komik['slug']; ?>" class="btn btn-warning">Edit</a>

                    <form action="/komik/<?= $komik['id_komik'] ?>" method="post" class="d-inline">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('apakah kamu yakin?')">Delete</button>
                    </form>

                    <br><br>

                    <a href="">Kembali kedaftar komik</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>