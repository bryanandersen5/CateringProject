<?php
require(__DIR__ . '/../Database/database.php');

$currentPage = basename($_SERVER['PHP_SELF']);

// ensure DB connection variable exists
if (!isset($connection) || $connection === null) {
    die('Database connection not initialized.');
}

// Fetch catering packages from database
$packages = [];
$query = "SELECT * FROM catering_packages WHERE is_active = 1 ORDER BY package_id";
$result = $connection->query($query); // changed $conn -> $connection

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $packages[$row['package_id']] = $row;
    }
}

// Fetch features for each package
$packageFeatures = [];
$featureQuery = "SELECT package_id, feature_text FROM package_features ORDER BY package_id, feature_id";
$featureResult = $connection->query($featureQuery); // changed $conn -> $connection

if ($featureResult) {
    while ($row = $featureResult->fetch_assoc()) {
        if (!isset($packageFeatures[$row['package_id']])) {
            $packageFeatures[$row['package_id']] = [];
        }
        $packageFeatures[$row['package_id']][] = $row['feature_text'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catering Packages - Gourmet Catering</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css">
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
                        <a class="<?php echo $currentPage === 'QuoteRequest.php' ? 'nav-link active' : 'nav-link'; ?>" href="QuoteRequest.php">Book Now</a>
                    </li>
                    <li class="nav-item">
                        <a class="<?php echo $currentPage === 'Bookings.php' ? 'nav-link active' : 'nav-link'; ?>" href="Bookings.php">Get a quote</a>
                    </li>
                    <li class="nav-item">
                        <a class="<?php echo $currentPage === 'OrderTracking.php' ? 'nav-link active' : 'nav-link'; ?>" href="OrderTracking.php">Review Your Order</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Packages Hero Section -->
    <section class="packages-hero">
        <div class="hero-content">
            <h1>Catering Packages</h1>
            <p>Flexible packages tailored to fit your budget and event needs</p>
        </div>
    </section>

    <!-- Packages Section -->
    <section class="packages-section">
        <div class="container">
            <div class="packages-grid">
                <?php
                $packageBadges = [
                    1 => 'Perfect for Small Gatherings',
                    2 => 'Most Popular',
                    3 => 'Premium Experience',
                    4 => 'Luxury & Customization'
                ];
                
                foreach ($packages as $packageId => $package):
                    $isFeatured = ($packageId === 2);
                    $isSilver = ($packageId === 2);
                    $isPlatinum = ($packageId === 4);
                ?>
                <!-- <?php echo $package['package_name']; ?> -->
                <div class="package-card <?php echo $isFeatured ? 'featured' : ''; ?>">
                    <div class="package-header">
                        <h2><?php echo $package['package_name']; ?></h2>
                        <div class="price-tag">
                            <?php 
                            if ($package['base_price'] > 0) {
                                echo '$' . number_format($package['base_price'], 2) . ' <span>/person</span>';
                            } else {
                                echo 'Call for Pricing';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="package-badge <?php echo $isSilver ? 'featured-badge' : ''; ?>">
                        <?php echo $packageBadges[$packageId] ?? ''; ?>
                    </div>
                    <ul class="package-features">
                        <?php
                        if (isset($packageFeatures[$packageId])) {
                            foreach ($packageFeatures[$packageId] as $feature) {
                                echo '<li>' . $feature . '</li>';
                            }
                        }
                        ?>
                    </ul>
                    <button class="package-button" onclick="window.location.href='<?php echo $isPlatinum ? 'QuoteRequest.php' : 'Bookings.php'; ?>'">
                        <?php echo $isPlatinum ? 'Request Custom Quote' : 'Book Now'; ?>
                    </button>
                </div>
                <br><hr><br>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <br><br><br>
    <!-- Package Add-Ons Section -->
    <section class="add-ons-section">
        <div class="container">
            <h2>Enhance Your Package</h2>
            <p>Add premium services to make your event even more special</p>
            <div class="add-ons-grid">
                <div class="add-on-card">
                    <h3>Bartender Service</h3>
                    <p class="price">$50/hour</p>
                    <p>Professional bartender for mixed drinks and cocktails</p>
                </div>

                <div class="add-on-card">
                    <h3>Live Cooking Station</h3>
                    <p class="price">$150-$300</p>
                    <p>Interactive cooking station with chef demonstration</p>
                </div>

                <div class="add-on-card">
                    <h3>Premium Linens</h3>
                    <p class="price">$50-$100</p>
                    <p>Elegant linens and table settings</p>
                </div>

                <div class="add-on-card">
                    <h3>Floral Centerpieces</h3>
                    <p class="price">$20-$40 each</p>
                    <p>Fresh flower arrangements for tables</p>
                </div>

                <div class="add-on-card">
                    <h3>Champagne Toast</h3>
                    <p class="price">$8/person</p>
                    <p>Premium champagne for special toasts</p>
                </div>

                <div class="add-on-card">
                    <h3>Late Night Appetizers</h3>
                    <p class="price">$12/person</p>
                    <p>Delicious late-night snack selection</p>
                </div>

                <div class="add-on-card">
                    <h3>Event Coordinator</h3>
                    <p class="price">$200-$500</p>
                    <p>Dedicated coordinator for complete event planning</p>
                </div>

                <div class="add-on-card">
                    <h3>Dessert Display</h3>
                    <p class="price">$75-$150</p>
                    <p>Premium dessert table with multiple selections</p>
                </div>

            </div>
        </div>
    </section>

    <!-- Comparison Section -->
    <section class="comparison-section py-5">
        <div class="container">
            <h2 class="mb-4">Package Comparison</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Feature</th>
                            <th scope="col">Bronze</th>
                            <th scope="col">Silver</th>
                            <th scope="col">Gold</th>
                            <th scope="col">Platinum</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">Price per Person</th>
                            <td>$25</td>
                            <td>$40</td>
                            <td>$60</td>
                            <td>$75</td>
                        </tr>
                        <tr>
                            <th scope="row">Appetizers</th>
                            <td>3 selections</td>
                            <td>5 selections</td>
                            <td>7 selections</td>
                            <td>Unlimited</td>
                        </tr>
                        <tr>
                            <th scope="row">Main Courses</th>
                            <td>2 options</td>
                            <td>3 options</td>
                            <td>4 options</td>
                            <td>5+ options</td>
                        </tr>
                        <tr>
                            <th scope="row">Side Dishes</th>
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
                            <td>4+</td>
                        </tr>
                        <tr>
                            <th scope="row">Beverages</th>
                            <td>Basic</td>
                            <td>Standard</td>
                            <td>Premium</td>
                            <td>Full Bar</td>
                        </tr>
                        <tr>
                            <th scope="row">Wait Staff</th>
                            <td>Not Included</td>
                            <td>Included</td>
                            <td>1 per 8 guests</td>
                            <td>Full Team</td>
                        </tr>
                        <tr>
                            <th scope="row">Setup &amp; Cleanup</th>
                            <td>Not Included</td>
                            <td>Not Included</td>
                            <td>Included</td>
                            <td>Included</td>
                        </tr>
                        <tr>
                            <th scope="row">Menu Customization</th>
                            <td>Limited</td>
                            <td>Standard</td>
                            <td>Full</td>
                            <td>Complete</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section py-5">
        <div class="container">
            <h2 class="mb-4">Frequently Asked Questions</h2>
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading1">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1" aria-expanded="true" aria-controls="faqCollapse1">
                            Can I customize a package?
                        </button>
                    </h2>
                    <div id="faqCollapse1" class="accordion-collapse collapse show" aria-labelledby="faqHeading1" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Absolutely! All packages can be customized based on your specific needs and preferences. Contact us for a personalized quote.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading2">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
                            What's the minimum guest count?
                        </button>
                    </h2>
                    <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Bronze: 20 guests, Silver: 30 guests. Gold and Platinum have flexible minimums. Smaller events can be accommodated upon request.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading3">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
                            Do you accommodate dietary restrictions?
                        </button>
                    </h2>
                    <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faqHeading3" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes! We handle all dietary requirements including vegetarian, vegan, gluten-free, and allergy considerations. Please notify us in advance.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading4">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse4" aria-expanded="false" aria-controls="faqCollapse4">
                            Is gratuity included in the pricing?
                        </button>
                    </h2>
                    <div id="faqCollapse4" class="accordion-collapse collapse" aria-labelledby="faqHeading4" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Our pricing is before gratuity. Standard 18-20% gratuity is recommended for service staff.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading5">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse5" aria-expanded="false" aria-controls="faqCollapse5">
                            What areas do you serve?
                        </button>
                    </h2>
                    <div id="faqCollapse5" class="accordion-collapse collapse" aria-labelledby="faqHeading5" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            We cater to events throughout the greater Vancouver area. Travel fees may apply for locations outside our standard service area.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="faqHeading6">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse6" aria-expanded="false" aria-controls="faqCollapse6">
                            How far in advance should I book?
                        </button>
                    </h2>
                    <div id="faqCollapse6" class="accordion-collapse collapse" aria-labelledby="faqHeading6" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            We recommend booking 2-4 weeks in advance. However, we accommodate rush requests based on availability.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="packages-cta">
        <div class="container">
            <h2>Ready to plan your event?</h2>
            <p>Contact us today for a personalized quote or to discuss your catering needs</p>
            <div class="cta-buttons">
                <a href="QuoteRequest.php" class="cta-button">Get a Quote</a>
                <a href="Bookings.php" class="cta-button secondary-button">Book Now</a>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>