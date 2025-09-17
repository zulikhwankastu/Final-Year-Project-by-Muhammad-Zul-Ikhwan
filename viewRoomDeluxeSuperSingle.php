<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moonlit Haven Villa - Petaling Jaya</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        header {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        header h1 {
            margin: 0;
            font-size: 1.8rem;
            text-align: center;
            color: #ff5a5f;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px;
        }

        .villa-details {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .villa-image {
            display: flex;
            gap: 10px;
        }

        .main-image {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }

        .main-image img {
            width: 100%;
            height: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .thumbnail-images {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .thumbnail-images img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .villa-details-below {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            flex-wrap: wrap;
        }

        .left-section {
            flex: 2;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            width: 100%;
        }

        .right-section {
            flex: 1;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 100px;
            margin-bottom: 20px;
            width: 100%;
        }

        .villa-info {
            text-align: center;
        }

        .villa-info h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #ff5a5f;
        }

        .villa-info p {
            margin: 10px 0;
            font-size: 1rem;
            color: #555;
        }

        .villa-info p strong {
            font-weight: bold;
            color: #333;
        }

        .villa-description ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .villa-description ul li {
            font-size: 1rem;
            margin: 5px 0;
            color: #555;
        }

        .villa-description ul li i {
            color: #ff5a5f;
        }

        .villa-description h3 {
            margin-bottom: 15px;
            font-size: 1.3rem;
            color: #333;
        }

        .book-now-btn {
            display: inline-block;
            background-color: #ff5a5f;
            color: white;
            padding: 15px 30px;
            font-size: 1rem;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            margin-top: 20px;
            width: 100%;
            text-align: center;
        }

        .book-now-btn:hover {
            background-color: #e03e48;
        }

        footer {
            background-color: #fff;
            padding: 20px;
            text-align: center;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        footer p {
            font-size: 0.9rem;
            color: #777;
            margin: 0;
        }

        /* Image Zoom and Darken Background */
        .zoomed-in {
            transform: scale(2);
            z-index: 10;
        }

        .darken-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 9;
            display: none;
        }
        .show-all-btn a {
         display: inline-block;
         background: linear-gradient(45deg, #ff5a5f, #f4777d); /* Gradient effect */
         color: white;
         padding: 12px 30px;
        font-size: 1rem;
        text-decoration: none;
        border-radius: 50px; /* Rounded corners */
        text-align: center;
         box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Soft shadow for depth */
        transition: background 0.3s, transform 0.3s;
        }   

.show-all-btn a:hover {
    background: linear-gradient(45deg, #f4777d, #ff5a5f); /* Inverted gradient on hover */
    transform: scale(1.05); /* Slight scaling effect on hover */
}

.show-all-btn a:focus {
    outline: none;
}


        /* Responsive Design */
        @media (max-width: 768px) {
            header h1 {
                font-size: 1.5rem;
            }

            .villa-info h2 {
                font-size: 1.3rem;
            }

            .villa-details-below {
                flex-direction: column;
            }

            .book-now-btn {
                padding: 12px 25px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Moonlit Haven Villa - Petaling Jaya</h1>
    </header>

    <div class="container">
        <section class="villa-details">
            <div class="villa-image">
                <div class="main-image" id="mainImage">
                    <img src="Images/room1.jpg" alt="Main Villa View" id="zoomImage">
                </div>
                <div class="thumbnail-images">
                    <img src="ImagesDss/details1.jpg" alt="Villa View 2">
                    <img src="ImagesDss/details2.jpg" alt="Villa View 3">
                    <img src="ImagesDss/details3.jpg" alt="Villa View 4">
                    <img src="ImagesDss/details4.jpg" alt="Villa View 5">
                    <div class="show-all-btn">
                     <a href="show_photo_dss.php">Show All Photos</a>
                </div>
                </div>
                
            </div>

            <div class="villa-details-below">
                <div class="left-section">
                    <h2>About Moonlit Haven 🌙</h2>
                    <div class="villa-description">
                        <p><strong>Location:</strong> Petaling Jaya, Malaysia</p>
                        <p><strong>Accommodates:</strong> 16+ guests, 13 bedrooms, 13 beds, 9.5 baths</p>
                        <p><strong>Rating:</strong> 4.92 stars ⭐ from 24 reviews</p>
                        
                        <h3>🌟 Listing Highlights 🌟</h3>
                        <ul>
                            <li><i class="fas fa-swimming-pool"></i> Pool for a refreshing dip</li>
                            <li><i class="fas fa-check"></i> Great check-in experience</li>
                            <li><i class="fas fa-home"></i> Extra spacious for large families</li>
                        </ul>

                        <h3>🏡 The Space</h3>
                        <p>Moonlit Haven is a serene Thai-inspired 3-storey bungalow with a shimmering pool, perfect for relaxation. The villa offers an array of entertainment facilities and panoramic views of lush landscapes.</p>
                        
                        <h3>🎁 Chinese New Year Special</h3>
                        <p>Book your stay during Chinese New Year (01 Jan - 31 Jan 2025) and receive a FREE Gift Bundle, including a 2025 calendar and an Angpau for good luck!</p>
                    </div>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15935.959916056314!2d101.79222639999999!3d3.0973226!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc35c06f149881%3A0x1234529a9e3613ce!2sHomestay%20Kak%20Ina%20Hulu%20Langat!5e0!3m2!1sen!2smy!4v1736898261527!5m2!1sen!2smy" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                
                        <a href="Ratings/give_ratings_DSS.php" class="book-now-btn">Submit Review</a>

                        <a href="Ratings/review_ratings_DSS.php" class="book-now-btn">See Comment</a>

                        <a href="index.php" class="book-now-btn">Homepage</a>
                    </div>



                <div class="right-section">
                    <div class="villa-info">
                        <h2>Book Your Stay Now! 🏖️</h2>
                        <p><strong>Price:</strong> RM 5000 per night</p>
                        <p><strong>Guest Access:</strong> Access to all bedrooms, bathrooms, kitchen, and pool area.</p>
                        <p><strong>Important Notice:</strong> A refundable damage deposit of RM1000 is required. Additional charges may apply for extra guests or events.</p>
                        
                        <a href="Booking/make_booking.php" class="book-now-btn">Book Now</a>

                    </div>
                </div>
            </div>
            
        </section>
    </div>

    <div class="darken-background" id="darkBackground"></div>

    <footer>
        <p>&copy; 2025 Moonlit Haven. All rights reserved.</p>
    </footer>

    <script>
        // Zoom functionality
        const zoomImage = document.getElementById('zoomImage');
        const darkBackground = document.getElementById('darkBackground');
        const mainImage = document.getElementById('mainImage');

        mainImage.addEventListener('click', function() {
            zoomImage.classList.toggle('zoomed-in');
            darkBackground.style.display = zoomImage.classList.contains('zoomed-in') ? 'block' : 'none';
        });

        darkBackground.addEventListener('click', function() {
            zoomImage.classList.remove('zoomed-in');
            darkBackground.style.display = 'none';
        });
    </script>
</body>
</html>
