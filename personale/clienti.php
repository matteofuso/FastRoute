<?php $root = '../';
require '../componenti/header.php'; ?>
<?php require '../componenti/restrict.php'; ?>
<?php require '../componenti/alert.php'; ?>

    <div>
        <h1>Clienti</h1>
        <?php
            require '../utils/Database.php';
            require_once '../utils/Log.php';
            require  '../utils/Helpers.php';
            $config = include '../config.php';

            try{
                Database::connect($config);
                $clienti = Database::select("SELECT c.id, c.nome, c.cognome, c.email, c.numero, c.indirizzo FROM clienti c");
                if (empty($clienti)) {
                    echo '<div class="alert alert-warning">Nessun cliente presente</div>';
                } else {
                    Helpers::printTable(['ID', 'Nome', 'Cognome', 'Email', 'Numero', 'Indirizzo'], $clienti);
                }
            } catch (Exception $e) {
                Log::errlog($e);
                echo '<div class="alert alert-danger">Errore nel recupero dei clienti</div>';
            }
        ?>
    </div>

    <div class="container mt-4">
        <h3 class="mb-4">Inserimento Nuovo Cliente</h3>

        <form action="../actions/crea_cliente.php" method="POST">
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome"
                           placeholder="Mario" required>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="cognome" class="form-label">Cognome</label>
                    <input type="text" class="form-control" id="cognome" name="cognome"
                           placeholder="Rossi" required>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                           placeholder="esempio@email.com" required>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="numero" class="form-label">Numero di telefono</label>
                    <input type="text" class="form-control" id="numero" name="numero"
                           placeholder="+39 123 456 7890" required>
                </div>
                <div class="mb-3 col-md-12">
                    <label for="indirizzo" class="form-label">Indirizzo</label>
                    <input type="text" class="form-control" id="indirizzo" name="indirizzo"
                           placeholder="Via Roma, 123 - 00100 Roma" required>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <button type="submit" class="btn btn-success">Inserisci</button>
            </div>
        </form>
    </div>

<?php require '../componenti/footer.php'; ?>