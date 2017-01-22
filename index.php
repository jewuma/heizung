<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="favicon.ico">

        <title>Heizungssteuerung</title>

        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/jquery.bootstrap-touchspin.min.css" rel="stylesheet">
        <link href="css/bootstrap-switch.min.css" rel="stylesheet">
        <link href="css/jquery-gauge.css" type="text/css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <style>
            .thermo {
                position: relative;
                width:12vw;
                height: 12vw;
                float:left;
            }
            .thermomit {
                margin-top:30px;
                width: 12vw;
                text-align: center;
                float:left;
                margin-right:20px;
            }
            .thermotxt {
                font-weight: bold;
            }
        </style>
    </head>

    <body>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.bootstrap-touchspin.min.js"></script>
        <script src="js/bootstrap-switch.min.js"></script>
        <script src="js/jquery-gauge.min.js"></script>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar">hallo</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="#actualdata" onclick="getPage('actualdata')">Aktuelle Daten</a></li>
                    <li><a href="#settings" onclick="getPage('settings')">>Einstellungen</a></li>
                </ul>
            </div>
        </nav>

        <div id="main" class="container-fluid">
        </div>
    </body>
    <script type="text/javascript" src="index.js"></script>
    </html>
