<?php $root = '../';
require '../componenti/header.php'; ?>
<?php require '../componenti/restrict.php'; ?>
<?php require '../componenti/alert.php'; ?>
<?php
require '../utils/Database.php';
require_once '../utils/Log.php';
require '../utils/Helpers.php';
$config = include '../config.php';

try {
    Database::connect($config);

    // Query per recuperare tutti i dati delle spedizioni con i nomi dei clienti e delle sedi
    $spedizioni = Database::select("
                    SELECT 
                        p.id,
                        CONCAT(c.nome, ' ', c.cognome) AS mittente,
                        concat (so.nome, ' (', so.citta, ')') AS sede_origine,
                        concat (sd.nome, ' (', sd.citta, ')') AS sede_destinazione,
                        p.dimensione,
                        p.peso,
                        p.consegna,
                        p.partenza,
                        p.arrivo,
                        p.ritiro
                    FROM plichi p
                    JOIN clienti c ON p.cliente = c.id
                    JOIN sedi so ON p.origine = so.id
                    JOIN sedi sd ON p.destinazione = sd.id
                ");

    foreach ($spedizioni as $spedizione) {
        $spedizione->stato = match (true) {
            !empty($spedizione->ritiro) => 'Ritirato',
            !empty($spedizione->arrivo) => 'Arrivato',
            !empty($spedizione->partenza) => 'In transito',
            default => 'Consegnato'
        };

        $spedizione->id = "$spedizione->id<a href='../tracking/?trackid=$spedizione->id' class='bi bi-box-arrow-up-right' style='font-size: .7em; vertical-align: baseline; margin-left: 4px;'></a>";    }
} catch (Exception $e) {
    Log::errlog($e);
    echo '<div class="alert alert-danger">Errore nel recupero delle spedizioni</div>';
}
?>

    <div>
        <h1>Dashboard Spedizioni</h1>
        <?php
        if (empty($spedizioni)) {
            echo '<div class="alert alert-warning">Nessuna spedizione presente</div>';
        } else {
            // Definizione delle intestazioni della tabella
            $headers = [
                'TRACKID',
                'Mittente',
                'Origine',
                'Destinazione',
                'Dimensione',
                'Peso (kg)',
                'Stato',
                'Data Partenza',
                'Data Arrivo',
                'Data Ritiro',
                'Stato',
            ];

            // Stampa della tabella
            echo '<div class="table-responsive">';
            Helpers::printTable($headers, $spedizioni);
            echo '</div>';
        }
        ?>
    </div>

<?php require '../componenti/footer.php'; ?>