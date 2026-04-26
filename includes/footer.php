<?php 
$basePath = '/pranjyoti/';
?>
<footer class="footer py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5 class="text-white"><i class="bi bi-droplet-fill text-danger"></i> Pranjyoti</h5>
                <p class="text-white">Help us save a life with the drop of your blood. Every drop counts.</p>
            </div>
            <div class="col-md-4">
                <h5 class="text-white">Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="<?php echo $basePath; ?>index.php" class="text-white">Home</a></li>
                    <li><a href="<?php echo $basePath; ?>about.php" class="text-white">About Us</a></li>
                    <li><a href="<?php echo $basePath; ?>contact.php" class="text-white">Contact</a></li>
                    <li><a href="<?php echo $basePath; ?>user/register.php" class="text-white">Become a Donor</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5 class="text-white">Contact Info</h5>
                <ul class="list-unstyled text-white">
                    <li><i class="bi bi-envelope me-2"></i> info@pranjyoti.org</li>
                    <li><i class="bi bi-phone me-2"></i> +91 9876543210</li>
                    <li><i class="bi bi-geo-alt me-2"></i> Blood Bank Center, Medical Road</li>
                </ul>
            </div>
        </div>
        <hr class="bg-secondary">
        <div class="text-center">
            <p class="mb-0 text-white">&copy; <?php echo date('Y'); ?> Pranjyoti - Blood Bank & Donor Management. All rights reserved.</p>
        </div>
    </div>
</footer>