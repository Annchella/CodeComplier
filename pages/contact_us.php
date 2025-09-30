<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us</title>
  <style>
    body {
      margin: 0;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #667eea, #764ba2);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      color: #333;
    }

    .contact-container {
      background: #fff;
      max-width: 1100px;
      width: 90%;
      display: grid;
      grid-template-columns: 1fr 1fr;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .contact-info {
      background: #764ba2;
      color: #fff;
      padding: 50px 30px;
    }

    .contact-info h2 {
      margin-bottom: 20px;
      font-size: 28px;
    }

    .contact-info p {
      margin: 10px 0;
      line-height: 1.6;
    }

    .contact-info .info-box {
      margin-top: 20px;
    }

    .info-box div {
      margin-bottom: 15px;
    }

    .info-box strong {
      display: block;
      font-weight: bold;
    }

    .socials {
      margin-top: 25px;
    }

    .socials a {
      margin-right: 10px;
      text-decoration: none;
      font-size: 20px;
      color: #fff;
      transition: 0.3s;
    }

    .socials a:hover {
      color: #ffdd57;
    }

    .contact-form {
      padding: 50px 30px;
    }

    .contact-form h2 {
      margin-bottom: 20px;
      font-size: 26px;
      color: #764ba2;
    }

    .contact-form form {
      display: grid;
      gap: 20px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
    }

    .form-group label {
      margin-bottom: 6px;
      font-weight: 500;
    }

    .form-group input,
    .form-group textarea {
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
      resize: none;
      outline: none;
      transition: 0.3s;
    }

    .form-group input:focus,
    .form-group textarea:focus {
      border-color: #764ba2;
      box-shadow: 0 0 5px rgba(118,75,162,0.4);
    }

    .contact-form button {
      padding: 14px;
      border: none;
      border-radius: 8px;
      background: #764ba2;
      color: #fff;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }

    .contact-form button:hover {
      background: #5a3685;
    }

    /* Responsive */
    @media (max-width: 900px) {
      .contact-container {
        grid-template-columns: 1fr;
      }
      .contact-info {
        text-align: center;
      }
    }
  </style>
</head>
<body>
    <?php include_once "../includes/Navbar.php"; ?>


  <div class="contact-container">
    <!-- Left Side Info -->
    <div class="contact-info">
      <h2>Get in Touch</h2>
      <p>We’d love to hear from you! Whether you have a question, feedback, or want to work with us, feel free to drop a message below.</p>

      <div class="info-box">
        <div>
          <strong>📍 Address:</strong>
          123 Code Street, Bangalore, India
        </div>
        <div>
          <strong>📞 Phone:</strong>
          +91 98765 43210
        </div>
        <div>
          <strong>📧 Email:</strong>
          support@codecompiler.com
        </div>
      </div>

      <div class="socials">
        <a href="#">🌐</a>
        <a href="#">🐦</a>
        <a href="#">📘</a>
        <a href="#">📸</a>
      </div>
    </div>

    <!-- Right Side Form -->
    <div class="contact-form">
      <h2>Contact Us</h2>
      <form id="contactForm">
        <div class="form-group">
          <label for="name">Your Name</label>
          <input type="text" id="name" placeholder="Enter your full name" required>
        </div>

        <div class="form-group">
          <label for="email">Your Email</label>
          <input type="email" id="email" placeholder="Enter your email" required>
        </div>

        <div class="form-group">
          <label for="subject">Subject</label>
          <input type="text" id="subject" placeholder="Subject of your message" required>
        </div>

        <div class="form-group">
          <label for="message">Message</label>
          <textarea id="message" rows="5" placeholder="Write your message here..." required></textarea>
        </div>

        <button type="submit">Send Message</button>
      </form>
    </div>
  </div>

  <script>
    document.getElementById("contactForm").addEventListener("submit", function(e){
      e.preventDefault();
      alert("✅ Your message has been sent successfully!");
      this.reset();
    });
  </script>

</body>
</html>