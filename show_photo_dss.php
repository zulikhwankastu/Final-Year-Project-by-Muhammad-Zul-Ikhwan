<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Photos</title>
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
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 1.8rem;
            color: #ff5a5f;
        }

        .container {
            max-width: 1100px;
            margin: 20px auto;
            padding: 20px;
        }

        .photo-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .photo-row {
            display: flex;
            gap: 10px;
        }

        .photo-row img {
            flex: 1;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.2s;
        }

        .photo-row img:hover {
            transform: scale(1.05);
        }

        .back-btn {
            text-align: center;
            margin-top: 20px;
        }

        .back-btn a {
            display: inline-block;
            background-color: #ff5a5f;
            color: white;
            padding: 10px 20px;
            font-size: 1rem;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .back-btn a:hover {
            background-color: #e03e48;
        }

        /* Lightbox Styles */
        .lightbox {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .lightbox img {
            max-width: 90%;
            max-height: 90%;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        .lightbox .controls {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            width: 100%;
            justify-content: space-between;
            padding: 0 20px;
            box-sizing: border-box;
        }

        .lightbox .controls button {
            background-color: rgba(0, 0, 0, 0.5);
            border: none;
            color: white;
            font-size: 2rem;
            cursor: pointer;
            padding: 10px;
            border-radius: 50%;
        }

        .lightbox .controls button:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }

        .lightbox .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 2rem;
            color: white;
            background: none;
            border: none;
            cursor: pointer;
        }

        .lightbox .close-btn:hover {
            color: #ff5a5f;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .photo-row {
                flex-wrap: wrap;
            }

            .photo-row img {
                height: 120px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>All Photos</h1>
    </header>

    <div class="container">
        <div class="photo-list">
            <div class="photo-row">
                <img src="ImagesDss/details1.jpg" alt="Room 1" onclick="openLightbox(0)">
            </div>
            <div class="photo-row">
                <img src="ImagesDss/details2.jpg" alt="Room 2" onclick="openLightbox(1)">
                <img src="ImagesDss/details3.jpg" alt="Room 3" onclick="openLightbox(2)">
            </div>
            <div class="photo-row">
                <img src="ImagesDss/details4.jpg" alt="Room 4" onclick="openLightbox(3)">
                <img src="ImagesDss/details5.jpg" alt="Room 5" onclick="openLightbox(4)">
                <img src="ImagesDss/details6.jpg" alt="Room 6" onclick="openLightbox(5)">
            </div>
            <div class="photo-row">
                <img src="ImagesDss/details7.jpg" alt="Room 7" onclick="openLightbox(6)">
                <img src="ImagesDss/details8.jpg" alt="Room 8" onclick="openLightbox(7)">
            </div>
        </div>

        <div class="back-btn">
            <a href="viewRoomDeluxeSuperSingle.php">Back to Room Details</a>
        </div>
    </div>

    <!-- Lightbox Section -->
    <div class="lightbox" id="lightbox">
        <button class="close-btn" onclick="closeLightbox()">&times;</button>
        <div class="controls">
            <button onclick="prevImage()">&#10094;</button>
            <button onclick="nextImage()">&#10095;</button>
        </div>
        <img id="lightbox-img" src="" alt="">
    </div>

    <script>
        const images = [
            "ImagesDss/details1.jpg",
            "ImagesDss/details2.jpg",
            "ImagesDss/details3.jpg",
            "ImagesDss/details4.jpg",
            "ImagesDss/details5.jpg",
            "ImagesDss/details6.jpg",
            "ImagesDss/details7.jpg",
            "ImagesDss/details8.jpg"
        ];

        let currentImageIndex = 0;

        function openLightbox(index) {
            currentImageIndex = index;
            const lightbox = document.getElementById('lightbox');
            const lightboxImg = document.getElementById('lightbox-img');
            lightboxImg.src = images[currentImageIndex];
            lightbox.style.display = 'flex';
        }

        function closeLightbox() {
            const lightbox = document.getElementById('lightbox');
            lightbox.style.display = 'none';
        }

        function prevImage() {
            currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
            document.getElementById('lightbox-img').src = images[currentImageIndex];
        }

        function nextImage() {
            currentImageIndex = (currentImageIndex + 1) % images.length;
            document.getElementById('lightbox-img').src = images[currentImageIndex];
        }
    </script>
</body>
</html>
