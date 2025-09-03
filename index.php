<?php
$page_title = "WhisperBox | Home";
$css_file = "home.css";
$show_header = true;
include 'header.php';
?>

<main>
    <div class="content">
        <div class="hero">
            <div class="text-block">
                <h1>Welcome to WhisperBox</h1>
                <p>
                    Share your thoughts while staying anonymous. This platform
                    offers a secure and private space to express your ideas,
                    experiences, or concerns openly. Your privacy is protected,
                    allowing you to communicate freely without fear of exposure or
                    judgment.
                </p>
            </div>
            <div class="image-container">
                <img src="image.png" alt="Secret Sharing" />
            </div>
        </div>

        <div class="features">
            <div id="box3">
                Anonymus Messaging <i class="fa-solid fa-user-secret"></i>
            </div>
            <div id="box3">
                OTP Verification <i class="fa-solid fa-check"></i>
            </div>
            <div id="box1">
                End-to-End-Encryption <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div id="box2">
                Self-Destructive <i class="fa-solid fa-trash"></i>
            </div>
        </div>
        <div class="button-container">
            <hr />
            <div class="encrypt">
                <textarea
                    id="textarea"
                    placeholder="Enter your message..."
                    rows="5"></textarea>
                <button id="otp">Encrypt & Generate OTP</button>
            </div>

            <div class="decrypt">
                <input
                    type="text"
                    id="textarea2"
                    placeholder="Enter your OTP..." />
                <button id="decrypt">Decrypt Message</button>
            </div>
        </div>
    </div>
</main>
</div>
</body>

</html>