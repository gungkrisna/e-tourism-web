<?php 
	session_start();
	if(!isset($_SESSION['username'])){
		header("location: login/");	exit();
	} else {
        $username = $_SESSION['username'];
    }

	if(isset($_GET['logout'])){
        session_unset($username);
        session_destroy();
		header("location: login/");	exit();
	}

    $storage = "assets/json/data.json";
    $stored_accounts = json_decode(file_get_contents($storage), true);

    foreach($stored_accounts as $key => $entry) {
        if ($entry['username'] == $username) {
            $name = explode(" ", $stored_accounts[$key]['name']);
            $email = $stored_accounts[$key]['email'];
            $last_login = $stored_accounts[$key]['last_login'];
        }
    }

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="NextSocial">
    <meta name="keywords" content="social media">
    <meta name="author" content="gk">
    
    <title>NextSocial - Home</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/plugins/perfectscroll/perfect-scrollbar.css" rel="stylesheet">
    <link href="assets/plugins/pace/pace.css" rel="stylesheet">
    
    <link href="assets/css/main.min.css" rel="stylesheet">
    <link href="assets/css/custom.css" rel="stylesheet">

    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/next.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/next.png" />
</head>
<body>
    <div class="app app-auth-home-screen align-content-stretch d-flex flex-wrap justify-content-end">

        <div class="app-auth-container">
            <div class="logo">
                <a href="index.html">NextSocial</a>
            </div>
            
            <h2 class="mt-5">Halo, <?php echo $name[0] ?></h2>

            <div class="auth-user">
                <div class="avatar avatar-xs status status-online">
                    <div class="avatar-title"><?php echo $name[0][0]; echo $name[1][0] ?? null; ?></div>
                </div>
                <span class="auth-user-fullname ms-3">@<?php echo $username ?></span>
                <span class="auth-user-activity">Last Login: <?php echo date('d/m/Y H:i:s', $last_login) ?></span>
            </div>

            <a href="?logout" class="btn btn-danger">Logout</a>
        </div>
        <div class="app-auth-background p-5 gallery min-vh-100">
        </div>
    </div>


    <!-- Javascripts -->
    <script src="assets/plugins/jquery/jquery-3.5.1.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/plugins/perfectscroll/perfect-scrollbar.min.js"></script>
    <script src="assets/plugins/pace/pace.min.js"></script>
    <script src="assets/js/main.min.js"></script>
</body>
</html>