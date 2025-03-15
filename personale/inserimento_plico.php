<?php $root = '../'; require '../componenti/header.php';?>
<?php require '../componenti/restrict.php'; ?>
<?php require '../utils/Database.php'; ?>
<?php require_once '../utils/Log.php'; ?>
<?php $config = include '../config.php'; ?>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <h2 class="mb-4"><i class="bi bi-box-seam me-2"></i>Inserimento Nuovo Plico</h2>

                <form action="../actions/inserimento_plico.php" method="POST" id="plicoForm">
                    <!-- Step progress indicator -->
                    <div class="progress mb-4">
                        <div class="progress-bar" role="progressbar" style="width: 20%;" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">Step 1/5</div>
                    </div>

                    <!-- Dati del plico -->
                    <div class="card mb-4 form-section" id="section-plico">
                        <div class="card-header bg-primary text-white">
                            <h3 class="h5 mb-0">Dati del plico</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="dimensione" class="form-label">Dimensione</label>
                                    <select class="form-select" id="dimensione" name="dimensione" required>
                                        <option value="" selected disabled>Seleziona dimensione</option>
                                        <option value="piccolo">Piccolo (max 20x30x10 cm)</option>
                                        <option value="medio">Medio (max 40x30x20 cm)</option>
                                        <option value="grande">Grande (max 60x40x30 cm)</option>
                                        <option value="extra">Extra (oltre 60x40x30 cm)</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="peso" class="form-label">Peso (kg)</label>
                                    <input type="number" class="form-control" id="peso" name="peso" step="0.01" min="0.01" required>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <button type="button" class="btn btn-primary next-section" data-next="section-sede-partenza">Avanti</button>
                            </div>
                        </div>
                    </div>

                    <!-- Sede di partenza (NUOVA SEZIONE) -->
                    <div class="card mb-4 form-section" id="section-sede-partenza" style="display: none;">
                        <div class="card-header bg-primary text-white">
                            <h3 class="h5 mb-0">Sede di partenza</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="partenza" class="form-label">Seleziona sede di partenza</label>
                                    <select class="form-select" id="partenza" name="partenza" required>
                                        <option value="" selected disabled>Seleziona sede di partenza</option>
                                        <?php
                                        try {
                                            Database::connect($config);
                                            $query = "SELECT id, nome, citta FROM sedi ORDER BY citta, nome";
                                            $result = Database::select($query);

                                            foreach ($result as $row) {
                                                echo "<option value='" . $row->id . "'>" . $row->nome . " (" . $row->citta . ")</option>";
                                            }
                                        } catch (Exception $e) {
                                            echo "<option value=''>Errore nel caricamento sedi</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-secondary prev-section" data-prev="section-plico">Indietro</button>
                                <button type="button" class="btn btn-primary next-section" data-next="section-mittente">Avanti</button>
                            </div>
                        </div>
                    </div>

                    <!-- Dati del mittente -->
                    <div class="card mb-4 form-section" id="section-mittente" style="display: none;">
                        <div class="card-header bg-primary text-white">
                            <h3 class="h5 mb-0">Dati del mittente</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="cliente" class="form-label">Cliente mittente</label>
                                    <select class="form-select" id="cliente" name="cliente" required>
                                        <option value="" selected disabled>Seleziona cliente</option>
                                        <?php
                                        try {
                                            $query = "SELECT * FROM clienti ORDER BY cognome, nome";
                                            $result = Database::select($query);

                                            foreach ($result as $row) {
                                                echo "<option value='" . $row->id . "'>" . $row->cognome . " " . $row->nome . " (" . $row->email . ")</option>";
                                            }
                                        } catch (Exception $e) {
                                            echo "<option value=''>Errore nel caricamento clienti</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-secondary prev-section" data-prev="section-sede-partenza">Indietro</button>
                                <button type="button" class="btn btn-primary next-section" data-next="section-destinatario">Avanti</button>
                            </div>
                        </div>
                    </div>

                    <!-- Dati del destinatario -->
                    <div class="card mb-4 form-section" id="section-destinatario" style="display: none;">
                        <div class="card-header bg-primary text-white">
                            <h3 class="h5 mb-0">Dati del destinatario</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nome_destinatario" class="form-label">Nome</label>
                                    <input type="text" class="form-control" id="nome_destinatario" name="nome_destinatario" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cognome_destinatario" class="form-label">Cognome</label>
                                    <input type="text" class="form-control" id="cognome_destinatario" name="cognome_destinatario" required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="indirizzo_destinatario" class="form-label">Indirizzo</label>
                                    <input type="text" class="form-control" id="indirizzo_destinatario" name="indirizzo_destinatario" required>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-secondary prev-section" data-prev="section-mittente">Indietro</button>
                                <button type="button" class="btn btn-primary next-section" data-next="section-destinazione">Avanti</button>
                            </div>
                        </div>
                    </div>

                    <!-- Sede di destinazione -->
                    <div class="card mb-4 form-section" id="section-destinazione" style="display: none;">
                        <div class="card-header bg-primary text-white">
                            <h3 class="h5 mb-0">Sede di destinazione</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="destinazione" class="form-label">Seleziona sede</label>
                                    <select class="form-select" id="destinazione" name="destinazione" required>
                                        <option value="" selected disabled>Seleziona sede di destinazione</option>
                                        <?php
                                        try {
                                            $query = "SELECT id, nome, citta FROM sedi ORDER BY citta, nome";
                                            $result = Database::select($query);

                                            foreach ($result as $row) {
                                                echo "<option value='" . $row->id . "'>" . $row->nome . " (" . $row->citta . ")</option>";
                                            }
                                        } catch (Exception $e) {
                                            echo "<option value=''>Errore nel caricamento sedi</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-secondary prev-section" data-prev="section-destinatario">Indietro</button>
                                <button type="submit" class="btn btn-success">Registra Plico</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script src="../scripts/inserimento_plico.js"></script>
<?php require '../componenti/footer.php'; ?>