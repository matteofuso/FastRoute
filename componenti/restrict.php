<?php
if (!isset($_SESSION['id'])) {
    ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h3 class="text-center mb-0">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>Accesso Non Autorizzato
                        </h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <i class="bi bi-lock-fill text-danger" style="font-size: 5rem;"></i>
                        </div>
                        <h4 class="mb-3">Accesso Negato</h4>
                        <p class="lead">Non hai il permesso di accedere a questa pagina.</p>
                        <p>Per favore, effettua il login o torna alla homepage.</p>
                        <div class="mt-4">
                            <a href="<?=$root?>personale/login.php?ref=<?=$_SERVER['PHP_SELF']?>" class="btn btn-primary me-2">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Accedi
                            </a>
                            <a href="<?=$root?>" class="btn btn-secondary">
                                <i class="bi bi-house-door-fill me-2"></i>Homepage
                            </a>
                        </div>
                    </div>
                    <div class="card-footer text-center text-muted">
                        <small>Se ritieni che questo sia un errore, contatta l'assistenza.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    require '../componenti/footer.php';
    die();
}