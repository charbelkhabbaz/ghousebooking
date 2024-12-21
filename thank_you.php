<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .thank-you-card {
            max-width: 600px;
            margin: 100px auto;
            text-align: center;
            padding: 30px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .thank-you-icon {
            font-size: 50px;
            color: #28a745;
        }
        .thank-you-message {
            font-size: 24px;
            font-weight: bold;
            margin-top: 20px;
            color: #333;
        }
        .thank-you-description {
            font-size: 16px;
            color: #666;
            margin-top: 10px;
        }
        .btn-custom {
            margin-top: 20px;
            background-color: #28a745;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn-custom:hover {
            background-color: #218838;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="thank-you-card">
        <div class="thank-you-icon">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div class="thank-you-message">Thank You for Your Payment!</div>
        <div class="thank-you-description">Your booking has been successfully confirmed. We look forward to hosting you!</div>
        <a href="index.php" class="btn btn-custom">Return to Homepage</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
