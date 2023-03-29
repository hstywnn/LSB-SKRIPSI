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
                    <input class="form-control" type="file" name="form_gambar" id="form-gambar" multiple>
                </div>
                <div class="mb-3">
                    <label for="form-PIN" class="form-label">Pin rahasia pesan</label>
                    <input type="number" class="form-control" name="form_PIN" id="form-PIN" placeholder="pin mu">
                </div>
                <input type="submit" class="btn btn-primary" value="submit">Submit</input>
            </form>
        </div>
    </div>
    <?php include('properties/script.php'); ?>
</body>
</html>