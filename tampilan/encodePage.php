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
                    <input class="form-control" type="file" name="form_gambar" id="form-gambar" multiple>
                </div>
                <div class="mb-3">
                    <label for="form-PIN" class="form-label">Pin rahasia pesan</label>
                    <input type="number" class="form-control" name="form_PIN" id="form-PIN" placeholder="pin mu">
                </div>
                <div class="mb-3">
                    <label for="form-pesan" class="form-label">Pesan yang akan disembunyikan</label>
                    <textarea class="form-control" name="form_pesan" id="form-pesan" rows="3"></textarea>
                </div>

                <input type="submit" class="btn btn-primary" value="submit">Submit</input>
            </form>
        </div>
    </div>
    <?php include('properties/script.php'); ?>
</body>
</html>