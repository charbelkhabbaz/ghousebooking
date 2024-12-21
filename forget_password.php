<!DOCTYPE html>
<html>

<head>
    <title>Forgot Password</title>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const button = document.getElementById('submit-button');
            const timeoutKey = 'lastClickedTime';
            const timeoutDuration = 5 * 60 * 1000; // 5 minutes in milliseconds

            // Check if the button should be disabled
            const lastClickedTime = localStorage.getItem(timeoutKey);
            if (lastClickedTime && Date.now() - lastClickedTime < timeoutDuration) {
                disableButton(button);
                const remainingTime = timeoutDuration - (Date.now() - lastClickedTime);
                startCountdown(remainingTime, button);
            }

            // Add event listener for the button
            button.addEventListener('click', (e) => {
                if (button.disabled) {
                    e.preventDefault();
                    alert('You can only request a code once every 5 minutes.');
                    return;
                }

                // Store the current time in localStorage
                localStorage.setItem(timeoutKey, Date.now());
                disableButton(button);
                startCountdown(timeoutDuration, button);
            });

            // Function to disable the button
            function disableButton(btn) {
                btn.disabled = true;
            }

            // Function to enable the button
            function enableButton(btn) {
                btn.disabled = false;
            }

            // Function to start the countdown
            function startCountdown(duration, btn) {
                const countdownInterval = setInterval(() => {
                    const timeLeft = Math.max(0, duration - (Date.now() - lastClickedTime));
                    if (timeLeft <= 0) {
                        clearInterval(countdownInterval);
                        enableButton(btn);
                    }
                }, 1000);
            }
        });
    </script>
</head>

<body>
    <h2>Forgott Password</h2>
    <form method="POST" action="https://youbee.click/send_email_password.php">
        <label for="email">Enter your email:</label>
        <input type="email" id="email" name="email" required>
        <button id="submit-button" type="submit">Send Verification Code</button>
    </form>
</body>

</html>
