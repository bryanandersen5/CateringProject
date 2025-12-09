<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Gourmet Catering</title>
    <!-- Add Bootstrap CSS CDN (before custom style.css) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css">
    <style>
        /* top-level adjustments using palette variables */
        body {
            background-color: var(--light-cream);
        }
        
        @media (max-width: 1024px) {
            .menu-items {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 640px) {
            .menu-items {
                grid-template-columns: 1fr;
            }
        }
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
                        <a class="<?php echo $currentPage === 'OrderTracking.php' ? 'nav-link active' : 'nav-link'; ?>" href="OrderTracking.php">Review your order</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Menu Hero Section -->
    <section class="menu-hero">
        <div class="hero-content-menu">
            <h1>Our Gourmet Menu</h1>
            <p>Discover our exquisite culinary offerings crafted by our award-winning chefs</p>
        </div>
    </section>

    <!-- Menu Categories -->
    <section class="menu-section">
        <div class="container">
            <div class="menu-filters">
                <button class="filter-button active" data-filter="all">All</button>
                <button class="filter-button" data-filter="appetizers">Appetizers</button>
                <button class="filter-button" data-filter="mains">Main Courses</button>
                <button class="filter-button" data-filter="sides">Sides</button>
                <button class="filter-button" data-filter="desserts">Desserts</button>
                <button class="filter-button" data-filter="beverages">Beverages</button>
            </div>

            <!-- Appetizers -->
            <div class="menu-category" id="appetizers">
                <h2>Appetizers</h2>
                <div class="menu-items">
                    <div class="menu-item">
                    <img src="../catering/shrimpcanape.jpg" alt="Shrimp Canapés" class="menu-item-image" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=No+Image';">
                    <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Shrimp Canapés</h3>
                                <span class="price">$8.50</span>
                            </div>
                            <p>Crispy toasts topped with marinated shrimp, lemon aioli, and fresh herbs</p>
                        </div>
                    </div>

                    <div class="menu-item">
                    <img src="../catering/tartlets.jpg" alt="Brie & Fig Tartlets" class="menu-item-image" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=No+Image';">                        <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Brie & Fig Tartlets</h3>
                                <span class="price">$7.00</span>
                            </div>
                            <p>Warm phyllo cups filled with baked brie, fig jam, and candied walnuts</p>
                        </div>
                    </div>

                    <div class="menu-item"> 
                    <img src="../catering/beefrolls.jpg" alt="Beef Carpaccio Skewers" class="menu-item-image" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=No+Image';">                        <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Beef Carpaccio Skewers</h3>
                                <span class="price">$9.50</span>
                            </div>
                            <p>Thinly sliced prime beef with arugula, parmesan, and truffle oil</p>
                        </div>
                    </div>

                    <div class="menu-item">
                    <img src="../catering/caprese.jpg" alt="Caprese Skewers" class="menu-item-image" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=No+Image';">
                    <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Caprese Skewers</h3>
                                <span class="price">$6.50</span>
                            </div>
                            <p>Fresh mozzarella, heirloom tomatoes, basil, and balsamic reduction</p>
                        </div>
                    </div>

                    <div class="menu-item">
                    <img src="../catering/crabcakes.jpg" alt="Crab Cakes" class="menu-item-image" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=No+Image';">                        <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Crab Cakes</h3>
                                <span class="price">$10.00</span>
                            </div>
                            <p>Pan-seared lump crab cakes with old bay remoulade and microgreens</p>
                        </div>
                    </div>

                    <div class="menu-item">
                    <img src="../catering/spinach.jpg" alt="Spinach & Artichoke Phyllo Rolls" class="menu-item-image" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=No+Image';">
                    <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Spinach & Artichoke Phyllo Rolls</h3>
                                <span class="price">$6.00</span>
                            </div>
                            <p>Crispy phyllo rolls filled with creamy spinach and artichoke dip</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Courses -->
            <div class="menu-category" id="mains">
                <h2>Main Courses</h2>
                <div class="menu-items">
                    <div class="menu-item">
                        <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=300&fit=crop" alt="Pan-Seared Salmon" class="menu-item-image">
                        <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Pan-Seared Salmon</h3>
                                <span class="price">$28.00</span>
                            </div>
                            <p>Atlantic salmon fillet with lemon butter sauce, seasonal vegetables, and wild rice pilaf</p>
                        </div>
                    </div>

                    <div class="menu-item">
                        <img src="https://images.unsplash.com/photo-1432139555190-58524dae6a55?w=400&h=300&fit=crop" alt="Filet Mignon" class="menu-item-image">
                        <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Filet Mignon</h3>
                                <span class="price">$35.00</span>
                            </div>
                            <p>Prime beef tenderloin with red wine reduction, roasted potatoes, and asparagus</p>
                        </div>
                    </div>

                    <div class="menu-item">
                        <img src="https://images.unsplash.com/photo-1598103442097-8b74394b95c6?w=400&h=300&fit=crop" alt="Herb-Roasted Chicken Breast" class="menu-item-image">
                        <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Herb-Roasted Chicken Breast</h3>
                                <span class="price">$22.00</span>
                            </div>
                            <p>Free-range chicken with herb jus, garlic mashed potatoes, and seasonal greens</p>
                        </div>
                    </div>

                    <div class="menu-item">
                        <img src="../catering/lobstertails.jpg" alt="Lobster Tail" class="menu-item-image" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=No+Image';">
                        <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Lobster Tail</h3>
                                <span class="price">$38.00</span>
                            </div>
                            <p>Fresh Atlantic lobster tail with clarified butter, roasted vegetables, and rice medley</p>
                        </div>
                    </div>

                    <div class="menu-item">
                        <img src="https://images.unsplash.com/photo-1541519227354-08fa5d50c44d?w=400&h=300&fit=crop" alt="Vegetarian Wellington" class="menu-item-image">
                        <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Vegetarian Wellington</h3>
                                <span class="price">$24.00</span>
                            </div>
                            <p>Puff pastry-wrapped mushroom and spinach cake with herb demi-glace</p>
                        </div>
                    </div>

                    <div class="menu-item">
                        <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=300&fit=crop" alt="Osso Buco" class="menu-item-image">
                        <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Osso Buco</h3>
                                <span class="price">$32.00</span>
                            </div>
                            <p>Braised veal shanks in red wine, tomatoes, served with saffron risotto</p>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <!-- Sides -->
            <div class="menu-category" id="sides">
            <div class="container">
                <h2>Sides & Starches</h2>
                <div class="menu-items">
                    <div class="menu-item">
                        <img src="../catering/trufflemac.jpg" alt="Truffle Mac & Cheese" class="menu-item-image" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=No+Image';">
                        <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Truffle Mac & Cheese</h3>
                                <span class="price">$8.00</span>
                            </div>
                            <p>Creamy three-cheese blend with black truffle oil and panko breadcrumb topping</p>
                        </div>
                    </div>

                    <div class="menu-item">
                        <img src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=400&h=300&fit=crop" alt="Roasted Root Vegetables" class="menu-item-image" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=No+Image';">
                        <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Roasted Root Vegetables</h3>
                                <span class="price">$6.00</span>
                            </div>
                            <p>Seasonal vegetables roasted with garlic, rosemary, and balsamic vinegar</p>
                        </div>
                    </div>

                    <div class="menu-item">
                        <img src="../catering/garlicmashedpotatoes.jpg" alt="Garlic Mashed Potatoes" class="menu-item-image" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=No+Image';">
                        <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Garlic Mashed Potatoes</h3>
                                <span class="price">$5.50</span>
                            </div>
                            <p>Creamy mashed potatoes with roasted garlic and chives</p>
                        </div>
                    </div>

                    <div class="menu-item">
                        <img src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=400&h=300&fit=crop" alt="Asparagus with Hollandaise" class="menu-item-image" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=No+Image';">
                        <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Asparagus with Hollandaise</h3>
                                <span class="price">$7.00</span>
                            </div>
                            <p>Fresh asparagus spears with classic hollandaise sauce</p>
                        </div>
                    </div>

                    <div class="menu-item">
                        <img src="../catering/wildrice.jpg" alt="Wild Rice Pilaf" class="menu-item-image" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=No+Image';">
                        <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Wild Rice Pilaf</h3>
                                <span class="price">$6.50</span>
                            </div>
                            <p>Toasted wild rice with dried cranberries, pecans, and seasonal herbs</p>
                        </div>
                    </div>

                    <div class="menu-item">
                        <img src="../catering/crabcakes.jpg" alt="Crab Cakes" class="menu-item-image" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=No+Image';">
                        <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Caesar Salad</h3>
                                <span class="price">$7.00</span>
                            </div>
                            <p>Romaine, house-made croutons, parmesan, and classic Caesar dressing</p>
                        </div>
                    </div>
                </div>
    </div>
            </div>

            <!-- Desserts -->
            <div class="menu-category" id="desserts">
            <div class="container">
                <h2>Desserts</h2>
                <div class="menu-items">
                    <div class="menu-item">
                        <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=400&h=300&fit=crop" alt="Chocolate Lava Cake" class="menu-item-image" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=No+Image';">
                        <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Chocolate Lava Cake</h3>
                                <span class="price">$7.50</span>
                            </div>
                            <p>Warm chocolate cake with molten center, served with vanilla ice cream</p>
                        </div>
                    </div>

                    <div class="menu-item">
                        <img src="../catering/tiramisu.jpg" alt="Tiramisu" class="menu-item-image" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=No+Image';">
                        <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Tiramisu</h3>
                                <span class="price">$6.50</span>
                            </div>
                            <p>Classic Italian dessert with espresso-soaked ladyfingers and mascarpone</p>
                        </div>
                    </div>

                    <div class="menu-item">
                        <img src="../catering/cremebrulee.jpg" alt="Crème Brûlée" class="menu-item-image" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=No+Image';">
                        <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Crème Brûlée</h3>
                                <span class="price">$6.00</span>
                            </div>
                            <p>Silky custard with caramelized sugar top and fresh berries</p>
                        </div>
                    </div>

                    <div class="menu-item">
                        <img src="../catering/raspberrycheesecake.jpg" alt="Raspberry Cheesecake" class="menu-item-image" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=No+Image';">
                        <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Raspberry Cheesecake</h3>
                                <span class="price">$7.00</span>
                            </div>
                            <p>New York style cheesecake with fresh raspberry compote</p>
                        </div>
                    </div>

                    <div class="menu-item">
                        <img src="../catering/macarons.jpg" alt="Macarons Selection" class="menu-item-image" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=No+Image';">
                        <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Macarons Selection</h3>
                                <span class="price">$5.00</span>
                            </div>
                            <p>Assorted French macarons with seasonal flavors</p>
                        </div>
                    </div>

                    <div class="menu-item">
                        <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=400&h=300&fit=crop" alt="Fruit Tart" class="menu-item-image" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=No+Image';">
                        <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Fruit Tart</h3>
                                <span class="price">$8.00</span>
                            </div>
                            <p>Buttery pastry with pastry cream and fresh seasonal fruits</p>
                        </div>
                    </div>
                </div>
    </div>
            </div>

            <!-- Beverages -->
            <div class="menu-category" id="beverages">
            <div class="container">
                <h2>Beverages</h2>
                <div class="menu-items">
                    <div class="menu-item">
                        <img src="../catering/coffee.jpg" alt="Premium Coffee Service" class="menu-item-image" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=No+Image';">
                        <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Premium Coffee Service</h3>
                                <span class="price">$3.50</span>
                            </div>
                            <p>Fresh-brewed gourmet coffee service for your guests</p>
                        </div>
                    </div>

                    <div class="menu-item">
                        <img src="../catering/assortedteasred.jpg" alt="Assorted Teas" class="menu-item-image" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=No+Image';">
                        <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Assorted Teas</h3>
                                <span class="price">$2.50</span>
                            </div>
                            <p>Selection of premium loose-leaf teas</p>
                        </div>
                    </div>

                    <div class="menu-item">
                        <img src="../catering/old-fashioned-homemade-lemonade.jpg" alt="Fresh Lemonade" class="menu-item-image" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=No+Image';">
                        <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Fresh Lemonade</h3>
                                <span class="price">$3.00</span>
                            </div>
                            <p>Freshly made lemonade with seasonal fruit</p>
                        </div>
                    </div>

                    <div class="menu-item">
                        <img src="https://images.unsplash.com/photo-1571115177098-24ec42ed204d?w=400&h=300&fit=crop" alt="Sparkling Water Station" class="menu-item-image" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=No+Image';">
                        <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Sparkling Water Station</h3>
                                <span class="price">$4.00</span>
                            </div>
                            <p>Selection of sparkling waters with fruit and herb infusions</p>
                        </div>
                    </div>

                    <div class="menu-item">
                        <img src="../catering/whimsical-signature-cocktails.jpg" alt="Signature Cocktails" class="menu-item-image" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=No+Image';">
                        <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Signature Cocktails</h3>
                                <span class="price">$12.00</span>
                            </div>
                            <p>House-made cocktails available with bartender service</p>
                        </div>
                    </div>

                    <div class="menu-item">
                        <img src="../catering/wine-selection-aae47a8.jpg" alt="Wine Selection" class="menu-item-image" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=No+Image';">
                        <div class="menu-item-content">
                            <div class="item-header">
                                <h3>Wine Selection</h3>
                                <span class="price">$50-$150</span>
                            </div>
                            <p>Premium wine selections available upon request</p>
                        </div>
                    </div>
                </div>
    </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="menu-cta">
        <div class="container">
            <h2>Ready to book your event?</h2>
            <p>Let our culinary team create a customized menu for your special occasion</p>
            <a href="QuoteRequest.php" class="cta-button">Request a Quote</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Gourmet Catering. All rights reserved.</p>
    </footer>

    <!-- Add Bootstrap JS bundle for responsive navbar toggler -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Menu filter functionality
        document.querySelectorAll('.filter-button').forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                
                // Update active button
                document.querySelectorAll('.filter-button').forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');

                // Show/hide menu categories
                document.querySelectorAll('.menu-category').forEach(category => {
                    if (filter === 'all') {
                        category.style.display = 'block';
                    } else {
                        const categoryId = category.getAttribute('id');
                        if (categoryId === filter) {
                            category.style.display = 'block';
                        } else {
                            category.style.display = 'none';
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>