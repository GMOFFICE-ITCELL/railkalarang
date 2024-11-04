<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us - Our Company</title>
  <link rel="stylesheet" href="style.css">
  
  
  <style>
      /* General styles */
body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  line-height: 1.6;
  background-color: #f4f4f4;
}

.container {
  width: 80%;
  margin: auto;
  overflow: hidden;
}

h1, h2, h3 {
  text-align: center;
  /*color: #333;*/
}

p {
  color: #666;
  text-align: center;
}

header {
  background: #333;
  color: #fff;
  padding: 2rem 0;
  text-align: center;
}

header h1 {
  margin: 0;
  font-size: 2.5rem;
}

nav {
  /*background-color: #444;*/
}

nav .menu {
  display: flex;
  justify-content: center;
  padding: 0;
  list-style-type: none;
  font-weight:bold;
}

nav .menu li {
  margin: 0 1rem;
}

nav .menu li a {
  color: #fff;
  text-decoration: none;
  padding: 1rem;
  display: block;
}

nav .menu li a:hover {
  background-color: #555;
}

#about-us {
  padding: 3rem 0;
  background-color: #fff;
}

.map-container {
  margin-top: 2rem;
  text-align: center;
}

footer {
  background-color: #222;
  color: #fff;
  padding: 1.5rem 0;
  text-align: center;
}

footer p {
  margin: 0;
}
.mail{
    text-align:center;
    font-weight:bold;
    margin-bottom:30px;
}

/* Media Query for Responsive Layout */
@media (max-width: 768px) {
  .container {
    width: 95%;
  }

  nav .menu {
    flex-direction: column;
  }

  nav .menu li {
    margin: 0.5rem 0;
  }
}

  </style>
  
  
  
  
</head>
<body>

  <!-- Navbar -->
  <!--<nav>-->
  <!--  <ul class="menu">-->
      <!--<li><a href="#">Home</a></li>-->
  <!--    <li><a href="#services">Services</a></li>-->
  <!--    <li><a href="#contact-us">Contact Us</a></li>-->
  <!--    <li><a href="#about-us">About Us</a></li>-->
  <!--  </ul>-->
  <!--</nav>-->

  <!-- Header -->
  <header>
    <h1>South Central Railway</h1>
  </header>

  <!-- About Us Section -->
  
  
<!--  <section id="services">-->
<!--      <h1>Rail kalarang Booking</h1>-->
<!--      <div>-->
<!--          <h3>Address</h3>-->
<!--          <p>-->
<!--              Railway Officer Colony,</p>-->
<!--   <p>Bhoiguda,</p>-->
<!--             <p> Secunderabad, </p>-->
<!--   <p>           Telangana 500025</p>-->


          
            <!-- Embedded Map -->
<!--      <div class="map-container">-->
<!--             <iframe -->
<!--  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3806.783879926891!2d78.5005946238717!3d17.43132005592127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bcb9a20dcbff493%3A0xd634ca9546e3bb22!2sRail%20Kalarang%2C%20Railway%20Officer%20Colony%2C%20Botiguda%2C%20Bhoiguda%2C%20Secunderabad%2C%20Telangana%20500025!5e0!3m2!1sen!2sin!4v1696078202369!5m2!1sen!2sin"-->
<!--  width="100%" -->
<!--  height="300" -->
<!--  frameborder="0" -->
<!--  style="border:0;" -->
<!--  allowfullscreen="" -->
<!--  aria-hidden="false" -->
<!--  tabindex="0">-->
<!--</iframe>-->


<!--      </div>-->
<!--      </div>-->
      
<!--  </section>-->
  <section id="about-us">
    <div class="container">
      <h2>About Us</h2>
<p>      IT cell,GM Office,</p>
      <p>South Central Railway</p>
     <p> Rail Nilayam Colony</p>
      <p>Secunderabad,</p> 
      <p> Telangana 500071</p>

      <h3>Our Location</h3>
      <p>We are located in the Secunderabad , ready to serve you.</p>

      <!-- Embedded Map -->
      <div class="map-container">
              <iframe 
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3806.7232489731886!2d78.50827122485065!3d17.43860554610157!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bcb9a2fbd6b3db3%3A0x4274852412d69d6e!2sRail%20Nilayam!5e0!3m2!1sen!2sin!4v1696073605553!5m2!1sen!2sin" 
          width="100%" 
          height="300" 
          frameborder="0" 
          style="border:0;" 
          allowfullscreen="" 
          aria-hidden="false" 
          tabindex="0">
        </iframe>

      </div>
    </div>
  </section>
  
  <section id="Contact-us">
      <h1>Contact Us:</h1>
      
      <div class= "mail">
          <b>mobile no:</b> 9701370012
      </div>
      <div class= "mail">
         <b> mail id:</b> <a href="mailto:scritcellgmoffice@gmail.com">scritcellgmoffice@gmail.com</a>
      </div>
      
  </section>

  <!-- Footer -->
  <footer>
    <p>&copy; South Central Railway - © 2024 GM Office</p>
  </footer>

</body>
</html>
