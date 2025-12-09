<?php

require(__DIR__ . '/Database/database.php');

// Add current page detection for active nav item
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gourmet Catering - Professional Event Catering Services</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">

    <!-- Add small page-local styles to match the attached card look -->
    <style>
        /* Page background similar to the screenshot */
        body { background-color: #f6efe6; }

        /* Card/Service styling */
        .service-row .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            overflow: hidden;
            background: #fff;
        }

        .service-row .card-img-top {
            width: 100%;
            height: 160px;           /* smaller image height */
            object-fit: cover;       /* crop to fill area */
            display: block;
        }

        .service-row .card-body {
            padding: 1.25rem;
        }

        .service-row h5 {
            font-size: 1.25rem;
            margin-bottom: .4rem;
            font-weight: 700;
        }

        .service-row p {
            color: #6c6c6c;
            margin-bottom: 0;
        }

        /* Section spacing (make space between services, menu and about) */
        .section-gap { padding-top: 3.5rem; padding-bottom: 3.5rem; }
        .services.section-gap { padding-bottom: 4.5rem; }
        .menu-preview.section-gap { padding-top: 2.5rem; padding-bottom: 4.5rem; }
        .about.section-gap { padding-top: 2.75rem; padding-bottom: 4rem; }

        /* CTA spacing to avoid overlap */
        .cta-button { display: inline-block; margin-top: 1.25rem; }

        /* Testimonials styling */
        .testimonial-card {
            border-radius: 12px;
            box-shadow: 0 6px 14px rgba(0,0,0,0.06);
            padding: 1rem;
            background: #fff;
            height: 100%;
        }
        .testimonial-quote { color: #444; font-style: italic; font-size: .98rem; }
        .testimonial-author { margin-top: .75rem; font-weight: 700; color: #6b4c2f; }
        .star { color: #f2b01e; margin-right: .15rem; font-size: .95rem; }

        /* Small devices: reduce card height and spacing a touch */
        @media (max-width: 575.98px) {
            .service-row .card-img-top { height: 120px; }
        }

        /* Add space between navbar and hero */
        .navbar + .hero {
            margin-top: 1.25rem;  /* creates space below the nav */
        }

        /* Increase thickness of heading and navbar text */
        .hero .hero-content h1 {
            font-weight: 800;     /* heavier heading */
            letter-spacing: -0.5px;
        }
        .navbar-brand, .navbar-nav .nav-link {
            font-weight: 700;     /* make nav items thicker */
        }

        /* Slightly increase spacing for the nav links for better visual weight */
        .navbar-nav .nav-link { padding-left: .95rem; padding-right: .95rem; }
    </style>
</head>
<body>
    <!-- Navigation (replaced with horizontal Bootstrap navbar) -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-light">
        <div class="container">
            <a class="navbar-brand" href="Index.php">Gourmet Catering</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="<?php echo $currentPage === 'Index.php' ? 'nav-link active' : 'nav-link'; ?>" href="Index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="<?php echo $currentPage === 'Menu.php' ? 'nav-link active' : 'nav-link'; ?>" href="Client Side/Menu.php">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="<?php echo $currentPage === 'CateringPackages.php' ? 'nav-link active' : 'nav-link'; ?>" href="Client Side/CateringPackages.php">Catering Packages</a>
                    </li>
                    <li class="nav-item">
                        <a class="<?php echo $currentPage === 'QuoteRequest.php' ? 'nav-link active' : 'nav-link'; ?>" href="Client Side/QuoteRequest.php">Get a quote</a>
                    </li>
                    <li class="nav-item">
                        <a class="<?php echo $currentPage === 'Bookings.php' ? 'nav-link active' : 'nav-link'; ?>" href="Client Side/Bookings.php">Book Now</a>
                    </li>
                    <li class="nav-item">
                        <a class="<?php echo $currentPage === 'OrderTracking.php' ? 'nav-link active' : 'nav-link'; ?>" href="Client Side/OrderTracking.php">Review Your Order</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
            <h1>Gourmet Catering for Every Occasion</h1>
            <p>Delicious menus and professional service tailored to your needs.</p>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="services section-gap">
        <div class="container">
            <h2>Our Services</h2>

            <!-- Replace the previous grid with a Bootstrap row of three cards -->
            <div class="row service-row g-4">
                <div class="col-md-4">
                    <div class="card h-100">
                        <!-- Corporate Events: use your web-relative path -->
                        <img src="catering/coporate.jpg" alt="Corporate Events" class="card-img-top">
                        <div class="card-body">
                            <h5>Corporate Events</h5>
                            <p>Professional catering for conferences, meetings, and company celebrations</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <!-- Weddings: updated image path -->
                        <img src="catering/wedding.jpg" alt="Weddings" class="card-img-top">
                        <div class="card-body">
                            <h5>Weddings</h5>
                            <p>Elegant cuisine and impeccable service for your special day</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <!-- Private Parties: updated image path -->
                        <img src="catering/sliders.jpg" alt="Private Parties" class="card-img-top">
                        <div class="card-body">
                            <h5>Private Events</h5>
                            <p>Customized menus for birthdays, anniversaries, and gatherings</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Menu Preview Section -->
    <section id="menu" class="menu-preview section-gap">
        <div class="container">
            <h2>Featured Menu</h2>
            <p>Quick highlights from our most popular dishes — visit the full menu for details.</p>

            <!-- Compact snippet showing a few menu highlights -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="card">
                        <img src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=400&h=250&fit=crop" class="card-img-top" alt="Truffle Mac & Cheese">
                        <div class="card-body">
                            <h5 class="card-title">Truffle Mac & Cheese</h5>
                            <p class="card-text small text-muted">$8.00</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                    <img src="catering/lobstertails.jpg" alt="Lobster Tail" class="menu-item-image" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=No+Image';">                        
                    <div class="card-body">
                            <h5 class="card-title">Lobster Tail</h5>
                            <p class="card-text small text-muted">$38.00</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=400&h=250&fit=crop" class="card-img-top" alt="Chocolate Lava Cake">
                        <div class="card-body">
                            <h5 class="card-title">Chocolate Lava Cake</h5>
                            <p class="card-text small text-muted">$7.50</p>
                        </div>
                    </div>
                </div>
            </div>

            <a href="Client Side/Menu.php" class="cta-button">View Full Menu</a>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about section-gap">
        <div class="container">
            <h2>About Us</h2>
            <p>With over 15 years of experience, we've been delivering exceptional catering services to hundreds of satisfied clients. Our team of professional chefs and event coordinators are dedicated to making your event unforgettable.</p>

            <!-- Raving Reviews (testimonials) -->
            <div class="testimonials row mt-4 g-3">
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="testimonial-quote">"Gourmet Catering handled our corporate dinner flawlessly—amazing presentation and flavor. They were prompt, professional and our team loved every course."</div>
                        <div class="testimonial-author">— Jessica H., Corporate Client</div>
                        <div class="mt-2"><span class="star">★★★★★</span></div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="testimonial-quote">"Our wedding reception was perfect thanks to the team. The food was exceptional and the staff made everything so easy—we couldn’t be happier."</div>
                        <div class="testimonial-author">— Mark & Lisa, Brides</div>
                        <div class="mt-2"><span class="star">★★★★★</span></div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="testimonial-quote">"Fantastic service for our family celebration. The private party menu was tailored to everyone’s tastes—highly recommended."</div>
                        <div class="testimonial-author">— Omar R., Private Event Host</div>
                        <div class="mt-2"><span class="star">★★★★★</span></div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact">
        <div class="container">
            <h2>Contact Us</h2>
            <div class="row">
                <div class="col-md-6">
                    <div class="quote-snippet">
                        <h3>Request a Quote</h3>
                        <p>Use our full quote form to get a personalized estimate — quick preview below.</p>
                        <!-- Enabled quick quote form -->
                        <form class="preview-form" action="Client Side/QuoteRequest.php" method="post" novalidate>
                            <input name="name" class="form-control mb-2" type="text" placeholder="Your Name" aria-label="Name" required>
                            <input name="email" class="form-control mb-2" type="email" placeholder="Your Email" aria-label="Email" required>
                            <input name="event_date" class="form-control mb-2" type="date" aria-label="Event Date" required>
                            <!-- Useful to identify quick snippet submissions server-side -->
                            <input type="hidden" name="source" value="quick-snippet">
                            <div class="d-flex gap-2 align-items-center">
                                <a href="Client Side/QuoteRequest.php" class="btn btn-outline-secondary cta-button">Open Full Quote Form</a>
                            </div>
                        </form>
                        <p style="margin-top:0.75rem; color:var(--muted);">Prefer to call? We're happy to assist you by phone.</p>

                        <!-- Moved contact info under quote-snippet -->
                        <div class="mt-3 contact-info small text-muted">
                            <p><strong>Phone:</strong> (604) 555-0123</p>
                            <p><strong>Email:</strong> info@gourmetcatering.com</p>
                        </div>
                    </div>
                </div>

                <!-- Keep the right column for additional info, if needed -->
                <div class="col-md-6">
                    <!-- Potentially keep other contact or map info here in future -->
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <!-- Add Bootstrap JS bundle for responsive navbar toggler -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>