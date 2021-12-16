<?php include './includes/head.php'; 
//print_r($_COOKIE);?>
<body>
    <div class="container container-fluid">
        <?php
        if (!isset($_GET["page"])) {
            $_GET["page"] = "inicio";
        }
        include './includes/header.php';
        
        include './front/' . $_GET["page"] . '.php';
        include './includes/footer.php'; ?>
    </div>
</body>
</html>
