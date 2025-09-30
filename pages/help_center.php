<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Help Center</title>
  <style>
    body {
      margin: 0;
      font-family: "Segoe UI", sans-serif;
      background: #f7f8fc;
      color: #333;
    }

    header {
      background: #764ba2;
      color: #fff;
      padding: 20px 30px;
      text-align: center;
    }

    header h1 {
      margin: 0;
    }

    .faq-container {
      max-width: 1000px;
      margin: 40px auto;
      padding: 20px;
    }

    .faq {
      background: #fff;
      margin-bottom: 15px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      overflow: hidden;
    }

    .faq button {
      width: 100%;
      text-align: left;
      padding: 18px 20px;
      font-size: 16px;
      font-weight: bold;
      border: none;
      background: #fff;
      cursor: pointer;
      outline: none;
      transition: 0.3s;
    }

    .faq button:hover {
      background: #f0ebf7;
    }

    .faq-content {
      max-height: 0;
      overflow: hidden;
      padding: 0 20px;
      transition: all 0.4s ease;
      background: #fafafa;
    }

    .faq-content p {
      margin: 15px 0;
      line-height: 1.6;
    }

    .faq.active .faq-content {
      max-height: 200px;
      padding: 15px 20px;
    }

    .back-links {
      text-align: center;
      margin-top: 30px;
    }
    .back-links a {
      margin: 0 10px;
      text-decoration: none;
      color: #764ba2;
      font-weight: bold;
    }
  </style>
</head>
<body>
    <?php include_once "../includes/Navbar.php"; ?>
    <br/>
    <br/>
    <br/>

  

  <div class="faq-container">
    <header>
    <h1>Help Center</h1>
    <p>Find quick answers to common questions</p>
  </header>
    <div class="faq">
      <button>❓ How do I reset my password?</button>
      <div class="faq-content">
        <p>Click on “Forgot Password” at the login page, enter your registered email, and follow the reset link sent to you.</p>
      </div>
    </div>

    <div class="faq">
      <button>💳 How do I make a payment?</button>
      <div class="faq-content">
        <p>Go to the Payment section, choose your preferred method (UPI/Card), and follow the on-screen instructions.</p>
      </div>
    </div>

    <div class="faq">
      <button>📚 How do I access my courses?</button>
      <div class="faq-content">
        <p>After logging in, navigate to the Dashboard. All your enrolled courses will appear under “My Courses.”</p>
      </div>
    </div>

    <div class="faq">
      <button>📩 Still need help?</button>
      <div class="faq-content">
        <p>If your query isn’t listed here, you can <a href="contact.php">contact us</a> directly for assistance.</p>
      </div>
    </div>
  </div>

  <div class="back-links">
    <a href="help_center.php">Help Center</a> | 
    <a href="contact_us.php">Contact Us</a>
  </div>

  <script>
    const faqs = document.querySelectorAll(".faq");
    faqs.forEach(faq => {
      faq.querySelector("button").addEventListener("click", () => {
        faq.classList.toggle("active");
      });
    });
  </script>
</body>
</html>