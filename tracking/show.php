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
    header ('Location: ?err=2');
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
        header ('Location: ?err=9');
        exit;
    }
    $plico = $plico[0];

} catch (Exception $e) {
    Log::errlog($e);
    header ('Location: ?err=1');
    exit;
}
?>
<?php require '../componenti/header.php'; ?>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-10 offset-md-1">

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h2 class="h4 mb-0"><i class="bi bi-box-seam me-2"></i>Dettagli Plico #<?php echo $plico->id; ?></h2>
                    </div>
                    <div class="card-body">
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
                                </table>
                            </div>

                            <!-- Mittente -->
                            <div class="col-md-6 mb-4">
                                <h5 class="border-bottom pb-2">Mittente</h5>
                                <p><strong><?php echo $plico->cliente_nome . ' ' . $plico->cliente_cognome; ?></strong><br>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require '../componenti/footer.php'; ?>