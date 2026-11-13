<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="icon" href="https://res.cloudinary.com/dwg2nov2v/image/upload/v1733588039/back_blanc_uqujoo.jpg" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Appliquer la police globale Poppins */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fa;
        }

        /* Ajouter des bords arrondis aux champs de saisie */
        .form-control {
            border-radius: 25px;
        }

        .btn {
            border-radius: 25px;
            font-weight: bold;
            
        }

        .password-container {
            position: relative;
        }

        .password-container i {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }

        /* Amélioration de l'alignement du logo et du formulaire */
        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-container img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Style du titre */
        .card-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: #333;
            margin-top: 15px;
        }

        /* Centrera le formulaire dans la page */
        .card {
            background-color: #d6dce1;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 20px;
        }

        .container {
            margin-top: 50px;
        }

        /* Style du footer pour les liens */
        .card-footer a {
            text-decoration: none;
        }

        .card-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <!-- Logo en haut du formulaire -->
                        <div class="logo-container">
                            <img src="https://res.cloudinary.com/dwg2nov2v/image/upload/v1733588039/back_blanc_uqujoo.jpg" alt="Logo">
                        </div>
                        <h5 class="card-title text-center">Registration</h5>
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                            </div>
                            <div class="form-group password-container">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Min. 8 characters, including a number" required>
                                <i class="fas fa-eye" id="togglePassword"></i>
                            </div>
                            <div class="form-group password-container">
                                <label for="password_confirmation">Confirm password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" required>
                                <i class="fas fa-eye" id="togglePasswordConfirmation"></i>
                            </div>
                            <div class="text-center">
                            <button type="submit" class="btn btn-primary ">Register</button>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger mt-3">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </form>
                        <div class="card-footer text-center mt-3">
                            <p>Already have an account? <a href="{{ route('login') }}">Log in here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Script pour afficher/cacher le mot de passe
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });

        // Script pour afficher/cacher le mot de passe de confirmation
        const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
        const passwordConfirmationInput = document.getElementById('password_confirmation');

        togglePasswordConfirmation.addEventListener('click', function () {
            const type = passwordConfirmationInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirmationInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>

</body>
</html>
