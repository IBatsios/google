<?php
    require_once __DIR__ . '/header.php';
    require_once __DIR__ . '/app/loginScript.php';

     if(isset($_SESSION['username'], $_SESSION['id'])){
         header('Location: index.php');
     }
?>

<div class="container-fluid">
    <div class="row">
        <div class="centered">
            <h1>Login</h1>
            <form action="" method="post">
                <div class="form-group">
                    <label for="UserEmail1">Email address</label>
                    <input type="email" name="email" class="form-control" id="UserEmail1" placeholder="Email">
                </div>

                <div class="form-group">
                    <label for="UserPassword">Password</label>
                    <input type="password" name="password" class="form-control" id="UserPassword" placeholder="Password">
                </div>

                <button type="submit" name="submitBtn" class="btn btn-default btn-lg btn-block">Site Login</button>
            </form>
            <hr />
            <h2>OR</h2>
            <div class="social">
                <a href="<?= $googleLoginUrl ?>" class="btn btn-danger btn-lg btn-block">Login With Google</a>
            </div>

            <a href="index.php" class="btn btn-link">Back</a>
        </div>
    </div>
</div>

<?php include_once 'footer.php' ?>
