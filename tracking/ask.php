<?php $root = '../'; require '../componenti/header.php';?>
<?php require '../componenti/alert.php'; ?>

    <h1>Traccia un pacco</h1>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h4>Tracking ID Lookup</h4>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="">
                            <div class="mb-3">
                                <label for="trackid" class="form-label">Enter Tracking ID:</label>
                                <input type="text" class="form-control" id="trackid" name="trackid" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require '../componenti/footer.php'; ?>