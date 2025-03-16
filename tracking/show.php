<?php
// File: conferma_inserimento.php
$root = '../';
require_once "../utils/Database.php";
require_once "../utils/Log.php";
$config = require "../config.php";

// Verifica se c'è un ID di plico nella sessione (deve essere stato appena inserito)
$id_plico = $_GET['trackid'] ?? null;

// Se ancora non c'è ID, mostra un errore
if (!$id_plico) {
    header('Location: ?err=2');
    exit;
}

// Recupera i dati del plico
try {
    Database::connect($config);

    // Query per recuperare tutti i dati del plico
    $query = "SELECT p.*, 
                    c.nome AS cliente_nome, c.cognome AS cliente_cognome, c.email AS cliente_email,
                    so.nome AS sede_origine_nome, so.citta AS sede_origine_citta,
                    sd.nome AS sede_dest_nome, sd.citta AS sede_dest_citta,
                    d.nome AS dest_nome, d.cognome AS dest_cognome, d.indirizzo AS dest_indirizzo
             FROM plichi p
             JOIN clienti c ON p.cliente = c.id
             JOIN sedi so ON p.origine = so.id
             JOIN sedi sd ON p.destinazione = sd.id
             JOIN destinatari d ON p.id = d.plico
             WHERE p.id = :id";

    $plico = Database::select($query, ['id' => $id_plico]);

    if (!$plico) {
        header('Location: ?err=9');
        exit;
    }
    $plico = $plico[0];

} catch (Exception $e) {
    Log::errlog($e);
    header('Location: ?err=1');
    exit;
}

$stato_plico = match (true) {
    !empty($plico->ritiro) => 'Ritirato',
    !empty($plico->arrivo) => 'Arrivato',
    !empty($plico->partenza) => 'In transito',
    default => 'Consegnato'
}

?>
<?php require '../componenti/header.php'; ?>

    <div class="container mt-4">

        <div class="card px-0 mb-4">
            <div class="card-header bg-primary text-white">
                <h2 class="h4 mb-0"><i class="bi bi-box-seam me-2"></i>Dettagli Plico #<?php echo $plico->id; ?>
                </h2>
            </div>
            <div class="card-body px-4">
                <div class="row">
                    <!-- Informazioni plico -->
                    <div class="col-md-6 mb-4">
                        <h5 class="border-bottom pb-2">Informazioni sul plico</h5>
                        <table class="table table-sm">
                            <tr>
                                <th style="width: 40%;">ID Plico:</th>
                                <td><span class="fw-bold"><?php echo $plico->id; ?></span></td>
                            </tr>
                            <tr>
                                <th>Dimensione:</th>
                                <td><?php echo ucfirst($plico->dimensione); ?></td>
                            </tr>
                            <tr>
                                <th>Peso:</th>
                                <td><?php echo number_format($plico->peso, 2); ?> kg</td>
                            </tr>
                            <tr>
                                <th>Data di consegna alla sede di partenza:</th>
                                <td><?php echo date('d/m/Y H:i', strtotime($plico->consegna)); ?></td>
                            </tr>
                            <tr>
                                <th>Stato:</th>
                                <td>
                                    <?php echo $stato_plico;?>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Mittente -->
                    <div class="col-md-6 mb-4">
                        <h5 class="border-bottom pb-2">Mittente</h5>
                        <p>
                            <strong><?php echo $plico->cliente_nome . ' ' . $plico->cliente_cognome; ?></strong><br>
                            <?php echo $plico->cliente_email; ?></p>

                        <h6 class="mt-3">Sede di origine:</h6>
                        <p><?php echo $plico->sede_origine_nome; ?> (<?php echo $plico->sede_origine_citta; ?>)</p>
                    </div>

                    <!-- Destinatario -->
                    <div class="col-md-6 mb-4">
                        <h5 class="border-bottom pb-2">Destinatario</h5>
                        <p><strong><?php echo $plico->dest_nome . ' ' . $plico->dest_cognome; ?></strong><br>
                            <?php echo $plico->dest_indirizzo; ?></p>
                    </div>

                    <!-- Sede di destinazione -->
                    <div class="col-md-6 mb-4">
                        <h5 class="border-bottom pb-2">Sede di destinazione</h5>
                        <p><?php echo $plico->sede_dest_nome; ?> (<?php echo $plico->sede_dest_citta; ?>)</p>
                    </div>

                    <!-- Date di consegna -->
                    <?php if ($plico->partenza): ?>
                    <div class="col-md-12 mb-4">
                        <h5 class="border-bottom pb-2">Date di consegna</h5>
                        <table class="table table-sm">
                                <tr>
                                    <th style="width: 40%;">Data di partenza:</th>
                                    <td><?php echo date('d/m/Y H:i', strtotime($plico->partenza)); ?></td>
                                </tr>

                            <?php if ($plico->arrivo): ?>
                                <tr>
                                    <th>Data di arrivo:</th>
                                    <td><?php echo date('d/m/Y H:i', strtotime($plico->arrivo)); ?></td>
                                </tr>
                            <?php endif; ?>

                            <?php if ($plico->ritiro): ?>
                                <tr>
                                    <th>Data di ritiro:</th>
                                    <td><?php echo date('d/m/Y H:i', strtotime($plico->ritiro)); ?></td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php
        if (isset($_SESSION['id'])) {
            // Determina lo stato attuale e prepara i form appropriati
            if ($stato_plico == 'Consegnato') {
                ?>
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h3 class="mb-0"><i class="bi bi-box-seam me-2"></i>Plico Consegnato alla sede di partenza</h3>
                    </div>
                    <div class="card-body">
                        <p class="lead">Il plico è stato consegnato alla sede di partenza. Ora può iniziare il viaggio
                            verso la destinazione.</p>

                        <!-- Form per registrare la partenza -->
                        <form method="POST" action="../actions/aggiorna_plico.php" class="mt-3">
                            <input type="hidden" name="action" value="partenza">
                            <input type="hidden" name="id_plico" value="<?php echo $id_plico; ?>">

                            <div class="row align-items-end">
                                <div class="col-md-6">
                                    <label for="partenza" class="form-label">Registra data e ora di partenza</label>
                                    <input type="datetime-local" class="form-control" id="partenza" name="partenza"
                                           value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-truck me-2"></i>Registra Partenza
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
            } else if ($stato_plico == 'In transito') {
                ?>
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h3 class="mb-0"><i class="bi bi-truck me-2"></i>Plico In Transito</h3>
                    </div>
                    <div class="card-body">
                        <p class="lead">Il plico è in viaggio dalla sede di origine alla sede di destinazione.</p>

                        <!-- Form per registrare l'arrivo -->
                        <form method="POST" action="../actions/aggiorna_plico.php" class="mt-3">
                            <input type="hidden" name="action" value="arrivo">
                            <input type="hidden" name="id_plico" value="<?php echo $id_plico; ?>">

                            <div class="row align-items-end">
                                <div class="col-md-6">
                                    <label for="arrivo" class="form-label">Registra data e ora di arrivo</label>
                                    <input type="datetime-local" class="form-control" id="arrivo" name="arrivo"
                                           value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-building me-2"></i>Registra Arrivo alla Sede
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
            } else if ($stato_plico == 'Arrivato') {
                ?>
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0"><i class="bi bi-building me-2"></i>Plico Arrivato alla Sede di Destinazione
                        </h3>
                    </div>
                    <div class="card-body">
                        <p class="lead">Il plico è arrivato alla sede di destinazione ed è pronto per essere ritirato
                            dal destinatario.</p>

                        <!-- Form per registrare il ritiro -->
                        <form method="POST" action="../actions/aggiorna_plico.php" class="mt-3">
                            <input type="hidden" name="action" value="ritiro">
                            <input type="hidden" name="id_plico" value="<?php echo $id_plico; ?>">

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="ritiro" class="form-label">Data e ora di ritiro</label>
                                    <input type="datetime-local" class="form-control" id="ritiro" name="ritiro"
                                           value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="bi bi-check-circle me-2"></i>Conferma Ritiro dal Destinatario
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>

<?php require '../componenti/footer.php'; ?>