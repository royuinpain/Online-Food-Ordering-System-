<?php
require '_base.php';
$_title = 'Welcome!';
include '_head.php';
?>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #fffaf5;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .welcome-container {
        max-width: 800px;
        margin: 60px auto;
        padding: 40px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        text-align: center;
    }

    h3 {
        font-size: 26px;
        margin-bottom: 15px;
        color: #705222;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    p {
        font-size: 16px;
        line-height: 1.7;
        margin-bottom: 25px;
    }

    .location {
        font-weight: bold;
        color: #444;
        background-color: #f3ede5;
        padding: 15px;
        border-radius: 10px;
        display: inline-block;
    }
</style>

<div class="welcome-container">
    <h3>Brunch | Bakery | Beans</h3>
    <p>Estate Coffeehouse is your to-go place for specialty coffee and all-day brunch. Located in strategic neighbourhoods, our space is bright and airy—perfect to kickstart your day as the sun streams through our large windows.</p>
    <p>We’ve got you covered all throughout breakfast, lunch, and afternoon tea. Come for the coffee, stay for the vibe.</p>

    <h3>Store Location</h3>
<p class="location">
    12, Jalan Midah 12, Taman Midah Cheras,<br>
    56000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur
</p>

<!-- Embedded Google Map -->
<div style="margin-top: 30px; border-radius: 12px; overflow: hidden;">
    <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3983.8165119977586!2d101.73835097577928!3d3.102624553098303!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc37abf2414eab%3A0x1f3cdbd0b9c60a0b!2s12%2C%20Jalan%20Midah%2012%2C%20Taman%20Midah%2C%2056000%20Cheras%2C%20Federal%20Territory%20of%20Kuala%20Lumpur%2C%20Malaysia!5e0!3m2!1sen!2smy!4v1713773286930!5m2!1sen!2smy" 
        width="100%" 
        height="300" 
        style="border:0;" 
        allowfullscreen="" 
        loading="lazy" 
        referrerpolicy="no-referrer-when-downgrade">
    </iframe>
</div>


<?php
include '_foot.php';
?>
