# 🏨 Guesthouse Booking System

A web-based platform I developed using PHP, HTML, CSS, AJAX, JavaScript, and SQL. This system allows users to browse and book guesthouses effortlessly while providing administrators with powerful tools to manage listings, reservations, and users.

**✨ Features**
👤 User Features
- Browse available guesthouses and book for specific dates.
- Double authentication login for security.
- Manage reservations and view booking history.

![Capture1](https://github.com/user-attachments/assets/f2568c3f-90e7-409d-a9ea-db829e2c2fb1)

![Capture2](https://github.com/user-attachments/assets/9ff37194-64d7-4f65-bd43-c13e1e30cd92)

**🛠️ Admin Features**

- Add, update, and delete guesthouse listings.
- Approve or reject bookings in real-time.
- Monitor occupancy and revenue reports through an intuitive dashboard.

 ![Capture3](https://github.com/user-attachments/assets/5ef149e4-7a2c-4b67-8896-bae9e17e4bdc)
 
 ![Capture4](https://github.com/user-attachments/assets/8f00c383-4cc8-4666-af0e-3c4e7446b85d)
 

**🧱 Technologies Used:**

PHP | MySQL | HTML | CSS | JavaScript | AJAX

🚫 The project was developed without any frameworks and runs locally using XAMPP, making it a lightweight yet robust solution for small to medium-sized guesthouse businesses.

⚙️ Setup Instructions (Localhost with XAMPP)

🔧 Before running the project, you need to configure a few files.

1️⃣ db_config.php – Located in the /Admin folder
✅ Import the .sql file inside the /DATABASE folder into phpMyAdmin.
✅ Then update the following values in db_config.php:

$db_host = 'localhost';
$db_user = 'root';       // Change if your DB username is different
$db_pass = '';           // Change if your DB has a password
$db_name = 'your_db_name'; // Replace with the imported DB name

2️⃣ essentials.php – Also in the /Admin folder
Update the URLs to reflect your local folder name:

define('SITE_URL','http://127.0.0.1/ghousebooking/'); 
define('UPLOAD_IMAGE_PATH', $_SERVER['DOCUMENT_ROOT'].'/ghousebooking/images/');
🔁 Replace "ghousebooking" with your actual folder name (e.g., guesthouse-booking) if it's different.


3️⃣ sendgrid Configuration (Optional – for email verification)
If you're using SendGrid for email verification, update these in essentials.php:

define('SENDGRID_API_KEY',"YOUR_API_KEY_HERE");
define('SENDGRID_EMAIL',"YOUR_EMAIL_HERE");
define('SENDGRID_NAME',"ANY_NAME");

You’ll need to:
Sign up at SendGrid
Create an API key
Paste it here

4️⃣ config_paytm.php – (Optional Payment Integration)
If using Paytm for test/production payments:

Update values like PAYTM_MERCHANT_KEY, MID, WEBSITE, and CALLBACK_URL accordingly.
This is optional and only needed if you enable payment processing.
