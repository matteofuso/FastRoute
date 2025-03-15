<?php $root = '../'; require '../componenti/header.php';?>
<?php require '../componenti/restrict.php'; ?>
<?php require '../utils/Database.php'; ?>
<?php require_once '../utils/Log.php'; ?>
<?php $config = require '../config.php' ?>

<?php
// Inizializza variabili
$giorni = isset($_POST['giorni']) ? intval($_POST['giorni']) : 7; // Default 7 giorni
$risultati = [];
$totale = 0;
$statisticheSedi = [];

// Se il form è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        Database::connect($config);
        // Calcola la data di inizio (oggi - N giorni)
        $dataInizio = date('Y-m-d', strtotime("-$giorni days"));
        $dataFine = date('Y-m-d');

        // Query per il numero totale di plichi consegnati negli ultimi N giorni
        $sqlTotale = "SELECT COUNT(*) as totale FROM plichi 
                     WHERE DATE(ritiro) BETWEEN :dataInizio AND :dataFine 
                     AND ritiro IS NOT NULL";

        $bindTotale = [
            ':dataInizio' => $dataInizio,
            ':dataFine' => $dataFine
        ];

        $risultatoTotale = Database::select($sqlTotale, $bindTotale);
        $totale = $risultatoTotale[0]->totale;

        // Query per il numero di consegne per giorno
        $sqlGiorni = "SELECT DATE(ritiro) as data, COUNT(*) as conteggio 
                     FROM plichi 
                     WHERE DATE(ritiro) BETWEEN :dataInizio AND :dataFine 
                     AND ritiro IS NOT NULL 
                     GROUP BY DATE(ritiro) 
                     ORDER BY data DESC";

        $bindGiorni = [
            ':dataInizio' => $dataInizio,
            ':dataFine' => $dataFine
        ];

        $risultati = Database::select($sqlGiorni, $bindGiorni);

        // Query per statistiche consegne per sede destinazione
        $sqlSedi = "SELECT s.nome as sede, COUNT(p.id) as conteggio
                   FROM plichi p
                   JOIN sedi s ON p.destinazione = s.id
                   WHERE DATE(p.ritiro) BETWEEN :dataInizio AND :dataFine
                   AND p.ritiro IS NOT NULL
                   GROUP BY p.destinazione
                   ORDER BY conteggio DESC";

        $bindSedi = [
            ':dataInizio' => $dataInizio,
            ':dataFine' => $dataFine
        ];

        $statisticheSedi = Database::select($sqlSedi, $bindSedi);
    } catch (Exception $e) {
        Log::errlog($e);
        echo '<div class="alert alert-danger">Si è verificato un errore durante l\'esecuzione della query.</div>';
    }
}
?>

    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card shadow border-0">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0"><i class="fas fa-search me-2"></i>Ricerca plichi consegnati</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="giorni" class="form-label fw-bold">Ultimi N giorni:</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="giorni" name="giorni" min="1" max="365" value="<?php echo $giorni; ?>" required>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i>Cerca</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="card shadow h-100 border-0">
                        <div class="card-header bg-success text-white">
                            <h4 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Riepilogo</h4>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <h2 class="display-4 fw-bold text-success"><?php echo $totale; ?></h2>
                                <p class="lead">Plichi consegnati negli ultimi <?php echo $giorni; ?> giorni</p>
                                <hr>
                                <p class="text-muted mb-0">Dal <?php echo date('d/m/Y', strtotime($dataInizio)); ?> al <?php echo date('d/m/Y', strtotime($dataFine)); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 mb-4">
                    <div class="card shadow h-100 border-0">
                        <div class="card-header bg-info text-white">
                            <h4 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Dettaglio consegne per giorno</h4>
                        </div>
                        <div class="card-body">
                            <?php if (count($risultati) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-light">
                                        <tr>
                                            <th>Data</th>
                                            <th>Numero consegne</th>
                                            <th>Grafico</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        // Trova il valore massimo per scalare la barra di progresso
                                        $maxConteggio = 1;
                                        foreach ($risultati as $riga) {
                                            if ($riga->conteggio > $maxConteggio) {
                                                $maxConteggio = $riga->conteggio;
                                            }
                                        }

                                        foreach ($risultati as $riga):
                                            $percentuale = ($riga->conteggio / $maxConteggio) * 100;
                                            ?>
                                            <tr>
                                                <td class="fw-bold"><?php echo date('d/m/Y', strtotime($riga->data)); ?></td>
                                                <td><?php echo $riga->conteggio; ?></td>
                                                <td width="50%">
                                                    <div class="progress">
                                                        <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $percentuale; ?>%"
                                                             aria-valuenow="<?php echo $riga->conteggio; ?>" aria-valuemin="0" aria-valuemax="<?php echo $maxConteggio; ?>">
                                                            <?php echo $riga->conteggio; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Nessun plico consegnato nel periodo selezionato.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (count($statisticheSedi) > 0): ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow border-0">
                            <div class="card-header bg-warning text-dark">
                                <h4 class="mb-0"><i class="fas fa-building me-2"></i>Consegne per sede</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <?php foreach ($statisticheSedi as $sede): ?>
                                        <div class="col-md-4 mb-3">
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-body">
                                                    <h5 class="card-title"><?php echo $sede->sede; ?></h5>
                                                    <h2 class="text-warning"><?php echo $sede->conteggio; ?></h2>
                                                    <p class="text-muted">plichi consegnati</p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

<?php require '../componenti/footer.php'; ?>