<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed - Gourmet Catering</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="../Index.php">Gourmet Catering</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="<?php echo $currentPage === 'Home.php' ? 'nav-link active' : 'nav-link'; ?>" href="Home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Home.php#menu">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="CateringPackages.php">Catering Packages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="QuoteRequest.php">Get a quote</a>
                    </li>
                    <li class="nav-item">
                        <a class="<?php echo $currentPage === 'Bookings.php' ? 'nav-link active' : 'nav-link'; ?>" href="Bookings.php">Book Now</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Success Message Section -->
    <section class="success-section" style="padding: 60px 20px; text-align: center;">
        <div class="container">
            <h1 style="font-weight: bold; font-size: 2.5rem; color: #333; margin-bottom: 1.5rem;">Thank you!</h1>
            <p style="font-size: 1.1rem; color: #555; margin-bottom: 2rem;">We have received your booking request. We will get back to you within 3-5 business days!</p>
            <a href="../Index.php" class="btn btn-primary">Return to Home</a>
        </div>
    </section>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <!-- Bootstrap JS bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
