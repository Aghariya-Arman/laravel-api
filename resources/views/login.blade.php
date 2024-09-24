<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>login-page</title>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-6">
                <form method="POST">
                    <h3 class="text-center text-primary">LOGIN</h3>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                        <input type="email" id="email" class="form-control" name="email" id="exampleInputEmail1"
                            aria-describedby="emailHelp">

                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input type="password" id="password" class="form-control" name="password"
                                id="exampleInputPassword1">
                        </div>
                        <button id="loginButton" class="btn btn-primary">Login</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $("#loginButton").on('click', function() {
                const email = $('#email').val();
                const password = $('#password').val();

                $.ajax({
                    url: '/api/login',
                    type: 'POST',
                    'contentType': 'application/json',
                    data: JSON.stringify({
                        email: email,
                        password: password,
                    }),
                    success: function(response) {
                        //console.log(response);
                        localStorage.setItem('api_token', response.token)
                        window.location.href = "http://localhost:8000/allpost";
                    },
                    error: function(xhr, status, error) {
                        alert('Error:' + xhr.responseText)
                    }

                });

            });
        });
    </script>
</body>

</html>
