<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('properties/header.php'); ?>
</head>
<body>
    <?php include('properties/navbar.php'); ?>
    <div class="content">
        <div class="container">
            <form action="../decode.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="form-gambar" class="form-label">Pilih File:</label>
                    <input class="form-control" type="file" name="form_gambar" id="form-gambar" onchange="original()" multiple>
                </div>
                <div class="mb-3">
                    <label for="ori-image" class="form-label">Preview File:</label><br>
                    <img id="ori-image" class="img-fluid" alt="stego_image_here">
                </div>
                <div class="mb-3">
                    <label for="form-PIN" class="form-label">Pin rahasia pesan:</label>
                    <input type="number" class="form-control" name="form_PIN" id="form-PIN" placeholder="pin mu">
                </div>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#decode_modal" id="submit_decode">Decode</button>

                <!-- <input type="submit" class="btn btn-primary" value="submit" id="submit_decode">Submit</input> -->
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="decode_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hasil Decode</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="hidden_object">tampilan hidden object</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <?php include('properties/script.php'); ?>
</body>
</html>