rate_guesthouse.php: <?php

require('inc/links.php');

if (!isset($_GET['id'])) {
    redirect('index.php');
}

$data = filteration($_GET);
$guesthouse_id = $data['id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate Guesthouse</title>
</head>

<body class="bg-light">
    <?php require('inc/header.php'); ?>

    <div class="container my-5">
        <div class="card shadow-sm p-4">
            <h3 class="text-center">Rate the Guesthouse</h3>
            <p class="text-center">How much would you rate this guesthouse from 1 to 5?</p>

            <!-- Rating Slider -->
            <div class="text-center mb-4">
                <input type="range" class="form-range w-50" min="1" max="5" step="1" id="ratingSlider" value="5" 
                       oninput="updateSliderValue(this.value)">
                <div>
                    <span>Your Rating: </span><strong id="sliderValue">5</strong>
                </div>
            </div>

            <!-- Feedback Form -->
            <form action="submit_rating.php" method="POST">
                <div class="mb-3">
                    <label for="feedback" class="form-label">Please give us your feedback:</label>
                    <textarea name="review" id="feedback" class="form-control" rows="4" required></textarea>
                </div>
                <input type="hidden" name="guesthouse_id" value="<?php echo $guesthouse_id; ?>">
                <input type="hidden" name="rating" id="ratingInput" value="5">
                <button type="submit" class="btn w-100" style="background-color: #2ec1ac; border-color: #2ec1ac; color: white;">Submit</button>

            </form>
        </div>
    </div>

    <?php require('inc/footer.php'); ?>

    <script>
        function updateSliderValue(value) {
            document.getElementById('sliderValue').textContent = value;
            document.getElementById('ratingInput').value = value;
        }
    </script>
</body>
</html>