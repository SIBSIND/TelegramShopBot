<?php
if (isset($_POST['login']) && isset($_POST['password'])) {
    $_SESSION['admin'] = true;
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Панель управления telegram ботом</title>

    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="materialize/css/materialize.min.css"  media="screen,projection"/>

    <!--Import jQuery before materialize.js-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="materialize/js/materialize.min.js"></script>
    <script type="text/javascript" src="js/js.js"></script>
</head>
<body>
<div class="container">
<?php
if (empty($_SESSION['admin'])) { ?>

        <form method="post">
            <div class="row">
                <div class="input-field col s12">
                    <input id="login" name="login" type="text" class="validate">
                    <label for="login">Login</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input id="password" name="password" type="password" class="validate">
                    <label for="password">Password</label>
                </div>
            </div>
            <div class="row center">
                <button class="btn waves-effect waves-light btn-large" type="submit">Submit</button>
            </div>
        </form>

<?php } else {
require 'panel.php';
}
?>
</div>
</body>
</html>
