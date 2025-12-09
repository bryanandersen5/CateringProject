<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request a Quote - Gourmet Catering</title>
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
                        <a class="<?php echo $currentPage === 'QuoteRequest.php' ? 'nav-link active' : 'nav-link'; ?>" href="QuoteRequest.php">Get a quote</a>
                    </li>
                    <li class="nav-item">
                        <a class="<?php echo $currentPage === 'Bookings.php' ? 'nav-link active' : 'nav-link'; ?>" href="Bookings.php">Book Now</a>
                    </li>
                    <li class="nav-item">
                        <a class="<?php echo $currentPage === 'OrderTracking.php' ? 'nav-link active' : 'nav-link'; ?>" href="OrderTracking.php">Review your order</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Quote Hero Section -->
    <section class="quote-hero">
        <div class="hero-content">
            <h1>Request a Quote</h1>
            <p>Tell us about your event and receive a personalized catering quote</p>
        </div>
    </section>

    <!-- Quote Request Form Section -->
    <section class="quote-section">
        <div class="container">
            <div class="booking-form-container">
                <div class="form-wrapper">
                    <h2>Get Your Custom Quote</h2>
                    <form class="quote-form" id="quoteForm">
                        <div class="form-section">
                            <h3>Your Information</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="full-name">Full Name *</label>
                                    <input type="text" id="full-name" name="full-name" required placeholder="John Doe">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email Address *</label>
                                    <input type="email" id="email" name="email" required placeholder="john@example.com">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="phone">Phone Number *</label>
                                    <input type="tel" id="phone" name="phone" required placeholder="(604) 555-0123">
                                </div>
                                <div class="form-group">
                                    <label for="event-date">Preferred Event Date</label>
                                    <input type="date" id="event-date" name="event-date">
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3>Message</h3>
                            <div class="form-group full-width">
                                <label for="message">Additional Message</label>
                                <textarea id="message" name="message" placeholder="Tell us anything else you'd like us to know about your event..." rows="5"></textarea>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="checkbox-option">
                                <input type="checkbox" id="terms" name="terms" required>
                                <label for="terms">I agree to be contacted by Gourmet Catering regarding my quote request *</label>
                            </div>

                            <button type="submit" class="submit-button">Send Quote Request</button>
                            <p class="form-note">We'll get back to you within 24 hours with a personalized quote</p>
                        </div>
                    </form>
                </div>

                <div class="info-sidebar">
                    <div class="info-card">
                        <h3>Quick Responses</h3>
                        <p>Our team responds to quote requests within 24 hours during business days.</p>
                    </div>

                    <div class="info-card">
                        <h3>No Obligation</h3>
                        <p>Request a quote with no obligation. We'll provide a detailed estimate tailored to your needs.</p>
                    </div>

                    <div class="info-card">
                        <h3>Expert Consultation</h3>
                        <p>Our event coordinators are available to discuss your vision and create the perfect menu.</p>
                    </div>

                    <div class="info-card">
                        <h3>Contact Info</h3>
                        <p><strong>Phone:</strong> (604) 555-0123</p>
                        <p><strong>Email:</strong> quotes@gourmetcatering.com</p>
                        <p><strong>Hours:</strong> Mon-Fri, 9 AM - 6 PM</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Gourmet Catering. All rights reserved.</p>
    </footer>

    <script>
        // Form submission handler - sends data to quote_submit.php and redirects to thank_you
        document.getElementById('quoteForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);

            fetch('quote_submit.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                return response.text();
            })
            .then(text => {
                console.log('Raw response:', text);
                try {
                    const result = JSON.parse(text);
                    if (result.success) {
                        // Redirect to thank-you page
                        window.location.href = 'thank_you.php';
                    } else {
                        alert('Error: ' + (result.message || 'Unable to submit request'));
                    }
                } catch (e) {
                    console.error('JSON parse error:', e);
                    console.error('Response was:', text);
                    alert('Server error: ' + text);
                }
            })
            .catch(err => {
                console.error('Submission error:', err);
                alert('An error occurred while submitting your request. Please try again later.');
            });
        });
    </script>
</body>
</html>
