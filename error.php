<html>

<head>
    <meta charset="UTF-8" />
    <link rel="icon" href="img/favicon.ico" type="image/gif" sizes="16x16">
    <title>Uh oh</title>
    <!-- BOOTSTRAP CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- JQUERY -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- POPPER JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <!-- BOOTSTRAP JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <!-- ROBOTO Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <!-- Fontawesome -->
    <script src="https://kit.fontawesome.com/2a953cdc29.js" crossorigin="anonymous"></script>
</head>
<style>
    body {
        overflow: hidden;
    }

    .container-fluid {
        background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABZ0RVh0Q3JlYXRpb24gVGltZQAxMC8yOS8xMiKqq3kAAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzVxteM2AAABHklEQVRIib2Vyw6EIAxFW5idr///Qx9sfG3pLEyJ3tAwi5EmBqRo7vHawiEEERHS6x7MTMxMVv6+z3tPMUYSkfTM/R0fEaG2bbMv+Gc4nZzn+dN4HAcREa3r+hi3bcuu68jLskhVIlW073tWaYlQ9+F9IpqmSfq+fwskhdO/AwmUTJXrOuaRQNeRkOd5lq7rXmS5InmERKoER/QMvUAPlZDHcZRhGN4CSeGY+aHMqgcks5RrHv/eeh455x5KrMq2yHQdibDO6ncG/KZWL7M8xDyS1/MIO0NJqdULLS81X6/X6aR0nqBSJcPeZnlZrzN477NKURn2Nus8sjzmEII0TfMiyxUuxphVWjpJkbx0btUnshRihVv70Bv8ItXq6Asoi/ZiCbU6YgAAAABJRU5ErkJggg==);
        position: absolute;
        width: 100%;
        top: 0;
    }

    .error-template {
        padding: 40px 15px;
        text-align: center;
    }

    .error-actions {
        margin-top: 15px;
        margin-bottom: 15px;
    }

    .error-actions .btn {
        margin-right: 10px;
    }

    .error-logo {
        background-color: #00304e;
        border-radius: 100%;
        width: 100px;
        border-style: none;
    }
</style>

<body class="m-0 p-0 w-100 h-100">
    <div class="container-fluid d-flex h-100 w-100 nu-gutters p-0">
        <div class="row align-items-center w-100 h-100 ">
            <div class="col mx-auto">
                <div class="error-template ">
                    <img class="error-logo" src="https://www.logo-designer.co/wp-content/uploads/2019/09/2019-University_of_Chichester_new_logo_design-2.png" alt="University of Chichester Logo">
                    <h1>
                        Oops!</h1>
                    <h2>
                        {Error Code} {Error Message}</h2>
                    <div class="error-details">
                        Sorry, an error has occured, {Error Description}
                    </div>
                    <div class="error-actions">
                        <a href="index.php" class="btn btn-primary btn-lg">
                            <i class="fas fa-home"></i>
                            Take Me Home
                        </a>
                        <a href="error-form.php" class="btn btn-warning btn-lg">
                            <i class="fas fa-envelope"></i>
                            Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>