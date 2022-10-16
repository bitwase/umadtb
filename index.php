<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Theme Made By www.w3schools.com -->
    <title>Bootstrap Theme Company Page</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    <script src="arquivos/jquery/mask2.js"></script>

    <style>
        body {
            font: 400 15px Lato, sans-serif;
            line-height: 1.8;
            color: #818181;
        }

        h2 {
            font-size: 24px;
            text-transform: uppercase;
            color: #303030;
            font-weight: 600;
            margin-bottom: 30px;
        }

        h4 {
            font-size: 19px;
            line-height: 1.375em;
            color: #303030;
            font-weight: 400;
            margin-bottom: 30px;
        }

        .jumbotron {
            background-color: #f4511e;
            color: #fff;
            padding: 100px 25px;
            font-family: Montserrat, sans-serif;
        }

        .container-fluid {
            padding: 60px 50px;
        }

        .bg-grey {
            background-color: #f6f6f6;
        }

        .logo-small {
            color: #f4511e;
            font-size: 50px;
        }

        .logo {
            color: #f4511e;
            font-size: 200px;
        }

        .thumbnail {
            padding: 0 0 15px 0;
            border: none;
            border-radius: 0;
        }

        .thumbnail img {
            width: 100%;
            height: 100%;
            margin-bottom: 10px;
        }

        .carousel-control.right,
        .carousel-control.left {
            background-image: none;
            color: #f4511e;
        }

        .carousel-indicators li {
            border-color: #f4511e;
        }

        .carousel-indicators li.active {
            background-color: #f4511e;
        }

        .item h4 {
            font-size: 19px;
            line-height: 1.375em;
            font-weight: 400;
            font-style: italic;
            margin: 70px 0;
        }

        .item span {
            font-style: normal;
        }

        .panel {
            border: 1px solid #f4511e;
            border-radius: 0 !important;
            transition: box-shadow 0.5s;
        }

        .panel:hover {
            box-shadow: 5px 0px 40px rgba(0, 0, 0, .2);
        }

        .panel-footer .btn:hover {
            border: 1px solid #f4511e;
            background-color: #fff !important;
            color: #f4511e;
        }

        .panel-heading {
            color: #fff !important;
            background-color: #f4511e !important;
            padding: 25px;
            border-bottom: 1px solid transparent;
            border-top-left-radius: 0px;
            border-top-right-radius: 0px;
            border-bottom-left-radius: 0px;
            border-bottom-right-radius: 0px;
        }

        .panel-footer {
            background-color: white !important;
        }

        .panel-footer h3 {
            font-size: 32px;
        }

        .panel-footer h4 {
            color: #aaa;
            font-size: 14px;
        }

        .panel-footer .btn {
            margin: 15px 0;
            background-color: #f4511e;
            color: #fff;
        }

        .navbar {
            margin-bottom: 0;
            background-color: #f4511e;
            z-index: 9999;
            border: 0;
            font-size: 12px !important;
            line-height: 1.42857143 !important;
            letter-spacing: 4px;
            border-radius: 0;
            font-family: Montserrat, sans-serif;
        }

        .navbar li a,
        .navbar .navbar-brand {
            color: #fff !important;
        }

        .navbar-nav li a:hover,
        .navbar-nav li.active a {
            color: #f4511e !important;
            background-color: #fff !important;
        }

        .navbar-default .navbar-toggle {
            border-color: transparent;
            color: #fff !important;
        }

        footer .glyphicon {
            font-size: 20px;
            margin-bottom: 20px;
            color: #f4511e;
        }

        .slideanim {
            visibility: hidden;
        }

        .slide {
            animation-name: slide;
            -webkit-animation-name: slide;
            animation-duration: 1s;
            -webkit-animation-duration: 1s;
            visibility: visible;
        }

        @keyframes slide {
            0% {
                opacity: 0;
                transform: translateY(70%);
            }

            100% {
                opacity: 1;
                transform: translateY(0%);
            }
        }

        @-webkit-keyframes slide {
            0% {
                opacity: 0;
                -webkit-transform: translateY(70%);
            }

            100% {
                opacity: 1;
                -webkit-transform: translateY(0%);
            }
        }

        @media screen and (max-width: 768px) {
            .col-sm-4 {
                text-align: center;
                margin: 25px 0;
            }

            .btn-lg {
                width: 100%;
                margin-bottom: 35px;
            }
        }

        @media screen and (max-width: 480px) {
            .logo {
                font-size: 150px;
            }
        }
    </style>
</head>

<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">

    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#myPage"><img src="arquivos/imagens/logo.png" width="80px"></a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#quando">QUANDO?</a></li>
                    <li><a href="#onde">ONDE?</a></li>
                    <li><a href="#quanto">QUANTO?</a></li>
                    <li><a href="#inscricao">INSCRIÇÃO</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="jumbotron text-center">
        <h1>RETIRO UMADTB</h1>
    </div>

    <!-- Container (About Section) -->
    <div id="quando" class="container-fluid">
        <div class="row">
            <div class="col-sm-8">
                <h1>QUANDO?</h1><br>
                <h4>De 12 à 15 de novembro</h4><br>

            </div>
            <div class="col-sm-4">
                <span class="glyphicon glyphicon-calendar logo"></span>
            </div>
        </div>
    </div>

    <div id="onde" class="container-fluid bg-grey">
        <div class="row">
            <div class="col-sm-4">
                <span class="glyphicon glyphicon-map-marker logo slideanim"></span>
            </div>
            <div class="col-sm-8">
                <h1>ONDE?</h1><br>
                <h4><strong>RECANTO PRESBITERIANO</strong> Our mission lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</h4><br>
            </div>
        </div>
    </div>

    <!-- Container (About Section) -->
    <div id="quanto" class="container-fluid">
        <div class="row">
            <div class="col-sm-8">
                <h1>QUANTO?</h1><br>
                <h4>Investimento de R$100,00</h4><br>
            </div>
            <div class="col-sm-4">
                <span class="glyphicon glyphicon-usd logo"></span>
            </div>
        </div>
    </div>

    <!-- Container (Inscrição Section) -->
    <div id="inscricao" class="container-fluid bg-grey">
        <h1 class="text-center">INSCRIÇÃO</h1>
        <div class="row">
            <div class="col-sm-12 slideanim">
                <div class="row">
                    <div class="col-sm-6 form-group">
                        <div class="form-group">
                            <label for="cmpNome">Nome</label>
                            <input type="text" class="form-control" name="cmpNome" id="cmpNome" aria-describedby="helpId" placeholder="">
                        </div>
                    </div>

                    <div class="col-sm-3 form-group">
                        <div class="form-group">
                            <label for="cmpRG">RG</label>
                            <input type="text" class="form-control rg" name="cmpRG" id="cmpRG" aria-describedby="helpId" placeholder="">
                        </div>
                    </div>
                    <div class="col-sm-3 form-group">
                        <div class="form-group">
                            <label for="cmpNascimento">Nascimento</label>
                            <input type="date" class="form-control" name="cmpNascimento" id="cmpNascimento" aria-describedby="helpId" placeholder="">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-2 form-group">
                        <div class="form-group">
                            <label for="cmpCEP">CEP</label>
                            <input type="text" class="form-control cep" name="cmpCEP" id="cmpCEP" aria-describedby="helpId" placeholder="" onchange="getEndereco()">
                        </div>
                    </div>

                    <div class="col-sm-5 form-group">
                        <div class="form-group">
                            <label for="cmpLogradouro">Logradouro</label>
                            <input type="text" class="form-control" name="cmpLogradouro" id="cmpLogradouro" aria-describedby="helpId" placeholder="">
                        </div>
                    </div>

                    <div class="col-sm-2 form-group">
                        <div class="form-group">
                            <label for="cmpNumero">Nº</label>
                            <input type="text" class="form-control" name="cmpNumero" id="cmpNumero" aria-describedby="helpId" placeholder="">
                        </div>
                    </div>

                    <div class="col-sm-2 form-group">
                        <div class="form-group">
                            <label for="cmpCpl">Complemento</label>
                            <input type="text" class="form-control" name="cmpCpl" id="cmpCpl" aria-describedby="helpId" placeholder="">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4 form-group">
                        <div class="form-group">
                            <label for="cmpBairro">Bairro</label>
                            <input type="text" class="form-control" name="cmpBairro" id="cmpBairro" aria-describedby="helpId" placeholder="">
                        </div>
                    </div>
                    <div class="col-sm-5 form-group">
                        <div class="form-group">
                            <label for="cmpCidade">Cidade</label>
                            <input type="text" class="form-control" name="cmpCidade" id="cmpCidade" aria-describedby="helpId" placeholder="">
                        </div>
                    </div>
                    <div class="col-sm-2 form-group">
                        <div class="form-group">
                            <label for="cmpUf">UF</label>
                            <input type="text" class="form-control" name="cmpUf" id="cmpUf" aria-describedby="helpId" placeholder="">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image of location/map -->
        <footer class="container-fluid text-center">
            <a href="#myPage" title="To Top">
                <span class="glyphicon glyphicon-chevron-up"></span>
            </a>
            <a href="https://bitwase.com" target="_blank">
                <p><img src="arquivos/imagens/bitwase.png" width="250px"></p>
                <p>(41)98496.0209</p>
            </a>
        </footer>

        <script>
            $(document).ready(function() {
                // Add smooth scrolling to all links in navbar + footer link
                $(".navbar a, footer a[href='#myPage']").on('click', function(event) {
                    // Make sure this.hash has a value before overriding default behavior
                    if (this.hash !== "") {
                        // Prevent default anchor click behavior
                        event.preventDefault();

                        // Store hash
                        var hash = this.hash;

                        // Using jQuery's animate() method to add smooth page scroll
                        // The optional number (900) specifies the number of milliseconds it takes to scroll to the specified area
                        $('html, body').animate({
                            scrollTop: $(hash).offset().top
                        }, 900, function() {

                            // Add hash (#) to URL when done scrolling (default click behavior)
                            window.location.hash = hash;
                        });
                    } // End if
                });

                $(window).scroll(function() {
                    $(".slideanim").each(function() {
                        var pos = $(this).offset().top;

                        var winTop = $(window).scrollTop();
                        if (pos < winTop + 600) {
                            $(this).addClass("slide");
                        }
                    });
                });
            });

            function getEndereco() {

                $("#cmpLogradouro").val("...");
                $("#cmpBairro").val("...");
                $("#cmpCidade").val("...");
                $("#cmpUf").val("...");
                var cep = $("#cmpCEP").val().replace(/\D/g, ''); //"81110522";
                var url = "https://viacep.com.br/ws/" + cep + "/json/";
                // alert(url);
                $.getJSON(url, function(pagaData) {
                    var logradouro = [];
                    var bairro = [];
                    var localidade = [];
                    var uf = [];
                    $(pagaData).each(function(key, value) {
                        logradouro.push(value.logradouro);
                        bairro.push(value.bairro);
                        localidade.push(value.localidade);
                        uf.push(value.uf);
                    });
                    $("#cmpLogradouro").val(logradouro);
                    $("#cmpBairro").val(bairro);
                    $("#cmpCidade").val(localidade);
                    $("#cmpUf").val(uf);
                    //$().val();
                });
            }
        </script>

</body>

</html>