<?php

session_start();
date_default_timezone_set('Asia/Dubai');

if (isset($_SESSION['login'])) {
    header('Location: finance.php');
}
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>InDepth Finance</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://s3.amazonaws.com/api_play/src/js/jquery-2.1.1.min.js"></script>
    <script src="https://s3.amazonaws.com/api_play/src/js/vkbeautify.0.99.00.beta.js"></script>
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->

    <script>
        $(function () {
            $("#generate-button").click(function () {
                var instanceurl = $("#instanceurl").val();
                var client_id = $("#client_id").val();
                var client_secret = $("#client_secret").val();
                var redirect_uri = $("#redirect_uri").val();
                var username = $("#username").val();
                var password = $("#password").val();
                if (username !== "" || password !== "")

                {
                    var token_input = $("#token");
                    var result_div = $("#result");
                    document.getElementById("iurl").value = document.getElementById("instanceurl").value;
                    generate_token(instanceurl, client_id, client_secret, redirect_uri, username, password, token_input, result_div);
                }
            });
        });
    </script>

    <script>
        function generate_token(instanceurl, client_id, client_secret, redirect_uri, username, password, token_input, result_div) {
            token_input.val("");
            result_div.html("");
            try
            {
                var xmlDoc;
                var xhr = new XMLHttpRequest();
                xhr.open("POST", instanceurl + "/oauth/token", true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function (e)
                {
                    if (xhr.readyState === 4)
                    {
                        var a = JSON.parse(e.target.responseText);
                        token_input.val(a["access_token"]);
                        if (token_input.val() !== "")
                        {   document.getElementById('invalidCredentials').style.display = 'none';

                            $('#welcome-modal').modal('show');
                            setTimeout(function () {
                                $('#welcome-modal').modal('hide');
                            }, 6000);
                            document.getElementById("generate-report").click();
                        } else {
                            document.getElementById('invalidCredentials').style.display = 'inline';

                        }
                        result_div.html(show_response(e.target.responseText));
                        xmlDoc = this.responseText;
                        txt = "";
                    }


                };
                xhr.send("client_id=" + client_id + "&client_secret=" + client_secret + "&grant_type=password&username=" + username + "&password=" + password + "&redirect_uri=" + redirect_uri);
            } catch (err)
            {
                alert(err.message);
            }
        }
        ;

        function show_response(str) {
            str = vkbeautify.xml(str, 4);
            return str.replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/\n/g, "<br />");
        }
        ;

        function validateForm() {
            var x = document.forms["frm"]["token"].value;
            if (x === "") {
                alert("Generate an access token first");
                return false;
            }
        }
        ;
    </script>
</head>
<body>

<!--API Connecting with demo-->
<input  id="instanceurl" type="hidden" name="instanceurl" value="https://alsanawbar.school"/>
<input  id="client_id" type="hidden" value="ec12b9754faf919e55db12d11a506bc2872c2ba5a6381e2ab1bee91d376a28ec"/>
<input  id="client_secret" type="hidden" value="6a45e4a8054c2290fd10ab486349dd08a9c56294be8c13a705171d65d89e0806"/>
<input  id="redirect_uri" type="hidden" value="https://wps.alsanawbar.school"/>

	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt " data-tilt style="margin-top: -50px;">
					<img src="images/indepth-logo.jpg" alt="InDepth Finance">
				</div>
				<form class="login100-form validate-form"  id="userform" style="margin-top: -100px;" onsubmit = "event.preventDefault();">
					<span class="login100-form-title">
						INDEPTH FINANCE
					</span>

                    <?php
                    if (isset($_SESSION['notloggedin'])) {
                        ?>

                        <div id='noaccess' class="alert alert-warning wrap-input100  m-b-8">
                            <strong>Not Logged in!</strong> Please login first to continue.
                        </div>

                        <?php
                        unset($_SESSION['notloggedin']);
                    }
                    ?>

                    <?php
                    if (isset($_SESSION['noaccess'])) {
                        ?>

                        <div id='noaccess' class="alert alert-danger wrap-input100  m-b-12">
                            <strong>Unauthorized!</strong> You are unauthorized to use this system. <br>Only authorized staffs have the access. <br>Please contact system administrator.
                        </div>

                        <?php
                        unset($_SESSION['noaccess']);
                    }
                    ?>
                    <div id='invalidCredentials' class="alert alert-danger wrap-input100  m-b-12" style="display: none; ">
                        <strong >Invalid!</strong> Username/Password is invalid.
                    </div>

					<div class="wrap-input100 validate-input " data-validate = "Valid username is required">
						<input class="input100" id="username" type="text" name="text" placeholder="Username">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Password is required">
						<input class="input100" type="password"  id="password" name="pass" placeholder="Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					
					<div class="container-login100-form-btn">
						<button class="login100-form-btn" type= "submit" id="generate-button">
							Login
						</button>
					</div>

					<div class="text-center p-t-12">
						<span class="txt1">
							Forgot
						</span>
						<a class="txt2" onclick="alert('Contact InDepth Support at support@indepth.ae');">
							Username / Password?
						</a>
					</div>

					<div class="text-center p-t-136" hidden>
						<a class="txt2" href="#">
							Create your Account
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>
				</form>
                <form name="frm" onsubmit="return validateForm()" action="login.php" method="POST" style="display: none">
                    <input id="token" type="hidden" name="token">
                    <input id="iurl" type="hidden" name="iurl">
                    <input id="user"  name="user">
                    <input type= "submit" id="generate-report" value ="Generate Reports">
                </form>
                <div id="welcome-modal" class="modal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <p style="text-align: center"><strong> Successfully Logged in. </strong></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


		</div>
	</div>

<script>



    var input = document.getElementById("password");
    input.addEventListener("keyup", function (event) {
        document.getElementById("user").value = document.getElementById("username").value;
        if (event.keyCode === 13)
            document.getElementById("generate-button").click();
    });
</script>

	
<!--===============================================================================================-->	
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>