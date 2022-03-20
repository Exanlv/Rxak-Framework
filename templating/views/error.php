<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rxak - Error</title>
    <style>
        html {
            width: 100%;
        }
        body {
            background-color: #eee;
            font-family: Verdana, sans-serif;
            width: 100%;
        }

        h1 {
            font-weight: 100;
            font-size: 150px;
        }

        #container {
            width: 90%;
            max-width: 400px;
            margin: auto;
            text-align: center;
        }
    </style>
</head>
<body>
    <div id="container">
        <h1><?= $code ?></h1>
        <p><?= $message ?></p>
    </div>
</body>
</html>