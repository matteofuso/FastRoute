<?php $main_classes = ''; require 'componenti/header.php';?>

    <div class="container mt-4">
        <!-- Hero section -->
        <div class="row align-items-center mb-5">
                <h1 class="display-4 fw-bold text-primary">Corriere Espresso FastRoute</h1>
                <p class="lead mb-4">Spedizioni veloci e sicure per i tuoi plichi in tutta Italia</p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                    <a href="tracking/" class="btn btn-primary btn-lg px-4 me-md-2">Traccia una spedizione</a>
                    <a href="#informazioni" class="btn btn-outline-secondary btn-lg px-4">Richiedi informazioni</a>
                </div>
        </div>

        <!-- Servizi offerti -->
        <section class="mb-5">
            <h2 class="text-center mb-4">I nostri servizi</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <h3 class="card-title h4">Spedizione Standard</h3>
                            <p class="card-text">Consegna affidabile in 3-5 giorni lavorativi. Ideale per spedizioni non urgenti.</p>
                            <hr>
                            <p class="fs-5 fw-bold text-primary">Da €5,99</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-primary">
                        <div class="card-body text-center">
                            <h3 class="card-title h4">Spedizione Express</h3>
                            <p class="card-text">Consegna rapida in 24-48 ore. La soluzione ideale per chi ha bisogno di velocità.</p>
                            <hr>
                            <p class="fs-5 fw-bold text-primary">Da €9,99</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <h3 class="card-title h4">Spedizione Premium</h3>
                            <p class="card-text">Consegna garantita in giornata per le spedizioni urbane. Massima priorità.</p>
                            <hr>
                            <p class="fs-5 fw-bold text-primary">Da €14,99</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Come funziona -->
        <section class="mb-5 p-4 rounded">
            <h2 class="text-center mb-4">Come funziona</h2>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="mb-3">
                            <span class="bg-primary text-white rounded-circle d-inline-block" style="width: 50px; height: 50px; line-height: 50px;">1</span>
                        </div>
                        <h3 class="h5">Consegna</h3>
                        <p>Consegna il tuo plico in una delle nostre sedi</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="mb-3">
                            <span class="bg-primary text-white rounded-circle d-inline-block" style="width: 50px; height: 50px; line-height: 50px;">2</span>
                        </div>
                        <h3 class="h5">Spedizione</h3>
                        <p>Spediamo il plico alla sede di destinazione</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="mb-3">
                            <span class="bg-primary text-white rounded-circle d-inline-block" style="width: 50px; height: 50px; line-height: 50px;">3</span>
                        </div>
                        <h3 class="h5">Arrivo</h3>
                        <p>Il plico arriva alla sede di destinazione</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="mb-3">
                            <span class="bg-primary text-white rounded-circle d-inline-block" style="width: 50px; height: 50px; line-height: 50px;">4</span>
                        </div>
                        <h3 class="h5">Ritiro</h3>
                        <p>Il destinatario ritira il plico nella sede di arrivo</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Carta fedeltà -->
        <section class="mb-5">
            <div class="row align-items-center">
                    <h2>Carta Fedeltà</h2>
                    <p class="lead">Accumula punti ad ogni spedizione e ottieni sconti per le tue future consegne!</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Ogni spedizione
                            <span class="badge bg-primary rounded-pill">1 punto</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            10 punti
                            <span class="badge bg-primary rounded-pill">10% di sconto</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            20 punti
                            <span class="badge bg-primary rounded-pill">25% di sconto</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            30 punti
                            <span class="badge bg-primary rounded-pill">Spedizione gratuita</span>
                        </li>
                    </ul>
            </div>
        </section>

        <!-- Richiesta informazioni -->
        <section class="mb-5" id="informazioni">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="card-title h4 mb-0">Richiedi informazioni</h2>
                </div>
                <div class="card-body">
                    <form action="actions/richiesta_informazioni.php" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-12">
                                <label for="messaggio" class="form-label">Messaggio</label>
                                <textarea class="form-control" id="messaggio" name="messaggio" rows="3" required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Invia richiesta</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

<?php require 'componenti/footer.php'; ?>