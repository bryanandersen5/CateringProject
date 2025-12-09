<?php
require(__DIR__ . '/../Database/database.php');

// Add current page detection for active nav item
$currentPage = basename($_SERVER['PHP_SELF']);

// detect success or error params
$showSuccess = false;
$successOrderId = null;
if (isset($_GET['success']) && $_GET['success'] === 'order_submitted') {
    $showSuccess = true;
    $successOrderId = isset($_GET['order_id']) ? htmlspecialchars($_GET['order_id']) : null;
}

// Add fetching of packages from DB
$availablePackages = [];
$pkgResult = $connection->query("SELECT package_id, package_name, base_price FROM catering_packages WHERE is_active = 1 ORDER BY package_id");
if ($pkgResult) {
    while ($pkgRow = $pkgResult->fetch_assoc()) {
        $availablePackages[] = $pkgRow;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Now - Gourmet Catering</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../style.css">

    <!-- Page-specific spacing & heading styling -->
    <style>
        /* Space between navbar and the booking section */
        .navbar + .booking-content { margin-top: 1.5rem; }

        /* Reduced / balanced heading size & weight */
        .booking-content .booking-heading,
        .order-tracking .tracking-wrapper h1 {
            font-size: 1.75rem;   /* smaller, less imposing */
            font-weight: 700;     /* slightly less heavy */
            letter-spacing: -0.3px;
            margin-top: 0;
        }

        /* Keep subtitle/intro spacing consistent */
        .booking-content p { margin-top: 0.5rem; font-weight: 400; }
    </style>
</head>
<body>
    <!-- Navigation (replaced with horizontal Bootstrap navbar) -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-light">
        <div class="container">
            <a class="navbar-brand" href="../Index.php">Gourmet Catering</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="<?php echo $currentPage === 'Index.php' ? 'nav-link active' : 'nav-link'; ?>" href="../Index.php#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="<?php echo $currentPage === 'Menu.php' ? 'nav-link active' : 'nav-link'; ?>" href="Menu.php">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="<?php echo $currentPage === 'CateringPackages.php' ? 'nav-link active' : 'nav-link'; ?>" href="CateringPackages.php">Catering Packages</a>
                    </li>
                    <li class="nav-item">
                        <a class="<?php echo $currentPage === 'QuoteRequest.php' ? 'nav-link active' : 'nav-link'; ?>" href="QuoteRequest.php">Get a quote</a>
                    </li>
                    <li class="nav-item">
                        <a class="<?php echo $currentPage === 'Bookings.php' ? 'nav-link active' : 'nav-link'; ?>" href="Bookings.php">Book Now</a>
                    </li>
                    <li class="nav-item">
                        <a class="<?php echo $currentPage === 'OrderTracking.php' ? 'nav-link active' : 'nav-link'; ?>" href="OrderTracking.php">Review Your Order</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Booking Content -->
    <section class="booking-content">
        <div class="container">
            <?php if ($showSuccess): ?>
                <div class="alert alert-success mb-1-5" role="alert">
                    <h5 style="margin:0 0 0.25rem 0;">Thank you!</h5>
                    <p style="margin:0;">
                        We have received your booking request. We will get back to you within 3-5 business days!
                    </p>
                </div>
            <?php endif; ?>

            <h5 class="booking-heading">Send us your catering needs and we'll get back to you!</h5>

            <div class="booking-form-container">
                <form class="booking-form" method="POST" action="submit_booking.php">
                    <!-- Name Section -->
                    <div class="mb-2rem">
                        <label class="form-label">Name*</label>
                        <div class="grid-two">
                            <div>
                                <input class="form-control custom-input" type="text" id="first-name" name="first_name" placeholder="First Name" required>
                            </div>
                            <div>
                                <input class="form-control custom-input" type="text" id="last-name" name="last_name" placeholder="Last Name" required>
                            </div>
                        </div>
                    </div>

                    <!-- Email Address -->
                    <div class="mb-2rem">
                        <label class="form-label">Email Address*</label>
                        <input class="form-control custom-input" type="email" id="email" name="email" placeholder="Your Email" required>
                    </div>

                    <!-- Phone -->
                    <div class="mb-2rem">
                        <label class="form-label">Phone*</label>
                        <input class="form-control custom-input" type="tel" id="phone" name="phone" placeholder="Your Phone Number" required>
                    </div>

                    <!-- Type of Event -->
                    <div class="mb-2rem">
	<label class="form-label">Type Of Event*</label>
	<input class="form-control custom-input" type="text" id="event-type" name="event_type" placeholder="e.g., Wedding, Corporate Event, Birthday Party" required>
</div>

<!-- Event Location (new) -->
<div class="mb-2rem">
	<label class="form-label">Event Location</label>
	<input class="form-control custom-input" type="text" id="event-location" name="event_location" placeholder="Venue or address (optional)">
</div>

                    <!-- Service Required -->
                    <div class="mb-2rem">
                        <label class="form-label">Service Required*</label>
                        <div class="checkbox-grid">
                            <div>
                                <input type="checkbox" id="drop-off" name="service_required[]" value="drop-off">
                                <label for="drop-off" style="margin-left: 0.5rem;">Drop Off</label>
                            </div>
                            <div>
                                <input type="checkbox" id="pick-up" name="service_required[]" value="pick-up">
                                <label for="pick-up" style="margin-left: 0.5rem;">Pick Up</label>
                            </div>
                            <div>
                                <input type="checkbox" id="food-setup" name="service_required[]" value="food-setup">
                                <label for="food-setup" style="margin-left: 0.5rem;">Food Setup</label>
                            </div>
                            <div>
                                <input type="checkbox" id="serving-staff" name="service_required[]" value="serving-staff">
                                <label for="serving-staff" style="margin-left: 0.5rem;">Serving Staff</label>
                            </div>
                        </div>
                    </div>

                    <!-- Menu Preference -->
                    <div class="mb-2rem">
                        <label class="form-label">Menu Preference*</label>
                        <div class="checkbox-grid">
                            <div style="margin-bottom: 0.75rem;">
                                <input type="checkbox" id="breakfast" name="menu_preference[]" value="breakfast">
                                <label for="breakfast" style="margin-left: 0.5rem;">Breakfast & Baked Goods</label>
                            </div>
                            <div style="margin-bottom: 0.75rem;">
                                <input type="checkbox" id="sandwiches" name="menu_preference[]" value="sandwiches">
                                <label for="sandwiches" style="margin-left: 0.5rem;">Sandwiches & Salads</label>
                            </div>
                            <div style="margin-bottom: 0.75rem;">
                                <input type="checkbox" id="buffets" name="menu_preference[]" value="buffets">
                                <label for="buffets" style="margin-left: 0.5rem;">Gourmet Buffets</label>
                            </div>
                            <div>
                                <input type="checkbox" id="hors-doevures" name="menu_preference[]" value="hors-doevures">
                                <label for="hors-doevures" style="margin-left: 0.5rem;">Hors D'oevures, Canapés & Dessert Buffets</label>
                            </div>
                        </div>
                    </div>

                    <!-- Package -->
                    <div class="mb-2rem">
                        <label class="form-label">Package*</label>
                        <div class="package-grid">
                            <?php
                            // Detect if a "custom" package is already in DB to prevent manual duplicate
                            $hasCustomInDB = false;
                            foreach ($availablePackages as $pkgCheck) {
                                if (stripos($pkgCheck['package_name'], 'custom') !== false || floatval($pkgCheck['base_price']) <= 0) {
                                    $hasCustomInDB = true;
                                    break;
                                }
                            }

                            foreach ($availablePackages as $pkg):
                            ?>
                                <div class="package-option">
                                    <input type="radio" id="package-<?php echo $pkg['package_id']; ?>" name="package" value="<?php echo $pkg['package_id']; ?>" required>
                                    <label for="package-<?php echo $pkg['package_id']; ?>" class="package-label">
                                        <h6 style="margin: 0.5rem 0;"><?php echo htmlspecialchars($pkg['package_name']); ?></h6>
                                        <p style="margin: 0; font-size: 0.9rem;">
                                            <?php if (isset($pkg['base_price']) && floatval($pkg['base_price']) > 0): ?>
                                                $<?php echo number_format($pkg['base_price'], 2); ?> per person
                                            <?php else: ?>
                                                Contact us
                                            <?php endif; ?>
                                        </p>
                                    </label>
                                </div>
                            <?php endforeach; ?>

                            <!-- Manual Custom option only if DB doesn't already have a custom/zero-price package -->
                            <?php if (!$hasCustomInDB): ?>
                                <div class="package-option">
                                    <input type="radio" id="package-custom" name="package" value="custom" required>
                                    <label for="package-custom" class="package-label">
                                        <h6 style="margin: 0.5rem 0;">Custom Package</h6>
                                        <p style="margin: 0; font-size: 0.9rem;">Contact us</p>
                                    </label>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Dietary Restrictions -->
                    <div class="mb-2rem">
                        <label class="form-label">Dietary Restrictions*</label>
                        <textarea class="form-control custom-input" id="dietary-restrictions" name="dietary_restrictions" placeholder="Please list any allergies or dietary requirements" rows="3" required></textarea>
                    </div>

                    <!-- Budget & Guests -->
                    <div class="grid-two mb-2rem">
                        <div>
                            <label class="form-label">Your Budget*</label>
                            <input class="form-control custom-input" type="number" id="budget" name="budget" placeholder="Enter your budget" min="0" step="0.01" required>
                        </div>
                        <div>
                            <label class="form-label">Number of Guests*</label>
                            <input class="form-control custom-input" type="number" id="guests" name="guests" placeholder="Number of guests" min="1" required>
                        </div>
                    </div>

                    <!-- Date & Time -->
                    <div class="grid-two mb-2rem">
                        <div>
                            <label class="form-label">Date of Catering*</label>
                            <input class="form-control custom-input" type="date" id="catering-date" name="catering_date" required>
                        </div>
                        <div>
                            <label class="form-label">Best Time to Reach You*</label>
                            <input class="form-control custom-input" type="time" id="best-time" name="best_time" required>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="mb-2rem">
                        <label class="form-label">Additional Notes</label>
                        <textarea class="form-control custom-input" id="additional-notes" name="additional_notes" placeholder="Any additional information or special requests..." rows="4"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit">Submit Your Catering Request</button>
                </form>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <!-- Add Bootstrap JS bundle for responsive navbar toggler -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom script for tab navigation -->
    <script>
        function goToTab(tabName) {
            // Hide all tabs
            const tabs = document.querySelectorAll('.tab-content');
            tabs.forEach(tab => tab.classList.remove('active'));

            // Remove active class from all buttons
            const buttons = document.querySelectorAll('.tab-button');
            buttons.forEach(btn => btn.classList.remove('active'));

            // Show selected tab
            document.getElementById(tabName).classList.add('active');

            // Activate corresponding button
            document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');

            // Scroll to top
            window.scrollTo(0, 0);
        }

        // Update tab button active state on click
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', function() {
                goToTab(this.getAttribute('data-tab'));
            });
        });
    </script>
</body>
</html>