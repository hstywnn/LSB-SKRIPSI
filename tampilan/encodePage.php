<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('properties/header.php'); ?>
</head>
<body>
    <?php include('properties/navbar.php'); ?>
    <div class="content">
        <div class="container">
            <form action="../encode.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="form-gambar" class="form-label">Pilih File:</label>
                    <input class="form-control" type="file" name="form_gambar" id="form-gambar" onchange="original()" multiple required>
                </div>
                <div class="mb-3">
                    <label for="ori-image" class="form-label">Preview File:</label><br>
                    <img id="ori-image" class="img-fluid" alt="cover_image_here">
                </div>
                <div class="mb-3">
                    <label for="form-PIN" class="form-label">Pin rahasia pesan:</label>
                    <input type="number" class="form-control" name="form_PIN" id="form-PIN" placeholder="pin mu" required>
                </div>
                <div class="mb-3">
                    <label for="form-pesan" class="form-label">Pesan yang akan disembunyikan:</label>
                    <textarea class="form-control" name="form_pesan" id="form-pesan" rows="3" required></textarea>
                </div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#encode_modal" id="submit_encode">Encode</button>
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="encode_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hasil Penyisipan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="#" id="cover_image" class="img-fluid" alt="cover_image_here">
                    <p id="image_comparation">komparasi</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a class="btn btn-success" href="#" id="image_download" download>Download</a>
                    <a class="btn btn-primary" href="DecodePage.php">Menuju Halaman Decode</a>
                </div>
            </div>
        </div>
    </div>

    <?php include('properties/script.php'); ?>
</body>
</html>