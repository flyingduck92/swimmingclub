    <footer class="box footer">
       <p>Copyright &copy; <?= date("Y")  ?></p>
    </footer>
    <?php 
        if(basename($_SERVER['PHP_SELF']) == 'login.php' || basename($_SERVER['PHP_SELF']) == 'register.php') {
            echo '<script type="text/javascript" src="./assets/js/togglePassword.js"></script>';
        }
     ?>
    <script type="text/javascript" src="./assets/js/responsive.js"></script>
</body>
</html>