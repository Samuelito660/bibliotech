<?php
session_start();
include 'includes/db.php';

$logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$nome_utente = $logged_in ? $_SESSION['nome'] . ' ' : '';
$ruolo = $logged_in ? $_SESSION['ruolo'] : '';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - BiblioTech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .hero-section {
            background: linear-gradient(to right, #007bff, #28a745), url('https://via.placeholder.com/1920x600/007bff/ffffff?text=Biblioteca+Digitale') no-repeat center center;
            background-size: cover;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .hero-section h1 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .hero-section p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        .features-section {
            padding: 60px 0;
            background-color: white;
        }
        .feature-card {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .feature-icon {
            font-size: 3rem;
            color: #007bff;
            margin-bottom: 15px;
        }
        footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 20px 0;
        }
        .container{
            color : black;
            text-align: center;
            font-size : 20px;
            font-weight: bold;
        }
    
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">BiblioTech</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if ($logged_in): ?>
                        <li class="nav-item">
                            <span class="navbar-text me-3">Benvenuto, <?php echo htmlspecialchars($nome_utente); ?>!</span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php"><i class="bi bi-person-circle fs-5"></i></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="registrazione.php"><i class="bi bi-person-plus fs-5"></i></a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <h1>Benvenuti alla Biblioteca Digitale</h1>
            <p class="lead">Esplora la nostra collezione di libri digitali, prenota prestiti e gestisci il tuo account in modo semplice e intuitivo.</p>
            <?php if (!$logged_in): ?>
                <a href="login.php" class="btn btn-light btn-lg me-3">Accedi per iniziare</a>
                <a href="registrazione.php" class="btn btn-outline-light btn-lg">Registrati</a>
            <?php else: ?>
                <?php if ($ruolo === 'bibliotecario'): ?>
                    <a href="gestione_restituzioni.php" class="btn btn-light btn-lg">Gestisci Restituzioni</a>
                <?php else: ?>
                    <a href="libri.php" class="btn btn-light btn-lg">Vai al Catalogo</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>

    <section class="features-section">
        <div class="container">
            <h2 class="text-center mb-5">Perché scegliere BiblioTech?</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">📚</div>
                        <h5>Collezione Vasta</h5>
                        <p>Accedi a migliaia di libri digitali in qualsiasi momento, da qualsiasi dispositivo.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">🔒</div>
                        <h5>Sicurezza e Privacy</h5>
                        <p>I tuoi dati sono protetti. Gestisci prestiti e account in totale sicurezza.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">🚀</div>
                        <h5>Facilità d'Uso</h5>
                        <p>Interfaccia intuitiva per prenotare, leggere e restituire libri senza complicazioni.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2026 BiblioTech. Tutti i diritti riservati. | Contattaci +39 3472100794<a href="#" class="text-light"></a></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>