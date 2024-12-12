<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="main.css" rel="stylesheet">
    <?php
    session_start();
    ?>
</head>

<body>
    <!-- LOGIN OVERLAY START -->
    <div class="align-self-center" id="loginOverlay">
        <div class="modal modal-sheet position-static d-block p-4 py-md-5" tabindex="-1" role="dialog" id="modalSignin">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-4 shadow">
                    <div class="modal-header p-5 pb-4 border-bottom-0">
                        <h1 class="fw-bold mb-0 fs-2">Log in</h1>
                        <button onclick="hide()" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-5 pt-0">
                        <form method="post" action="scripts/loginScript.php">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control rounded-3" id="loginEmail" name="loginEmail" placeholder="name@example.com">
                                <label for="loginEmail">Email address</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control rounded-3" id="loginPassword" name="loginPassword" placeholder="Password">
                                <label for="loginPassword">Password</label>
                            </div>
                            <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Log in</button>
                        </form>
                        <a style="color: rgba(var(--bs-link-color-rgb),var(--bs-link-opacity,1)); text-decoration: underline;" onclick="signupDisplay()">Don't have an account?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- LOGIN OVERLAY END -->

    <!-- SIGN UP OVERLAY START -->
    <div class="align-self-center" id="signupOverlay">
        <div class="modal modal-sheet position-static d-block p-4 py-md-5" tabindex="-1" role="dialog" id="modalSignin">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-4 shadow">
                    <div class="modal-header p-5 pb-4 border-bottom-0">
                        <h1 class="fw-bold mb-0 fs-2">Sign up for free</h1>
                        <button onclick="hide()" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-5 pt-0">

                        <form action="scripts/signupScript.php" method="post">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control rounded-3" id="signupEmail" name="signupEmail" placeholder="name@example.com">
                                <label for="signupEmail">Email address</label>
                            </div>
                            <div class="mb-3 d-inline-flex">
                                <div class="form-floating ">
                                    <input type="text" class="form-control rounded-3" id="firstname" name="firstname" placeholder="John">
                                    <label for="firstname">First Name</label>
                                </div>
                                <div class="form-floating">
                                    <input type="text" class="form-control rounded-3" id="lastname" name="lastname" placeholder="Doe">
                                    <label for="lastname">Last Name</label>
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control rounded-3" id="signupPassword" name="signupPassword" placeholder="Password">
                                <label for="signupPassword">Password</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control rounded-3" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password">
                                <label for="confirmPassword">Confirm Password</label>
                            </div>
                            <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Sign up</button>
                            <small class="text-body-secondary">By clicking Sign up, you agree to the terms of use.</small>
                        </form>
                        <a style="color: rgba(var(--bs-link-color-rgb),var(--bs-link-opacity,1)); text-decoration: underline;" onclick="loginDisplay()">Have an account?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- SIGN UP OVERLAY END -->

    <!-- BEGINNING OF THE HEADER -->
    <header class="p-3 mb-3 border-bottom">
        <div class="container" bis_skin_checked="1">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start" bis_skin_checked="1">
                <img class="bi me-2" height="55px" role="img" aria-label="The Car Garage Company" src="/img/logo.PNG">

                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a style="pointer-events: none; cursor: default;" href="index.php" class="nav-link px-2 link-secondary">Home</a></li>
                    <li><a href="#" class="nav-link px-2 link-body-emphasis">Book service</a></li>
                    <li><a href="#" class="nav-link px-2 link-body-emphasis">Inquire</a></li>
                    <li><a href="#" class="nav-link px-2 link-body-emphasis">FAQs</a></li>
                    <li><a href="#" class="nav-link px-2 link-body-emphasis">About</a></li>
                </ul>

                <?php if (isset($_SESSION['loggedIn']) && $_SESSION["loggedIn"] === true && $_SESSION['admin'] === 0) { ?>
                    <div class="dropdown text-end" bis_skin_checked="1">
                        <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="/img/profile.png" alt="mdo" width="32" height="32" class="rounded-circle">
                        </a>
                        <ul class="dropdown-menu text-small">
                            <li><a href="#" class="dropdown-item">Previous bookings</a></li>
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="/scripts/logout.php">Sign out</a></li>
                        </ul>
                    </div>
                <?php } elseif (isset($_SESSION['loggedIn']) && $_SESSION["loggedIn"] === true && $_SESSION['admin'] === 1) { ?>
                    <ul class="nav col-12 col-lg-auto my-2 justify-content-center my-md-0 text-small">
                        <li>
                            <a href="#" class="nav-link text-secondary">
                                <svg class="bi d-block mx-auto mb-1" width="24" height="24">
                                    <use xlink:href="#home"></use>
                                </svg>
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link text-white">
                                <svg class="bi d-block mx-auto mb-1" width="24" height="24">
                                    <use xlink:href="#speedometer2"></use>
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link text-white">
                                <svg class="bi d-block mx-auto mb-1" width="24" height="24">
                                    <use xlink:href="#table"></use>
                                </svg>
                                Orders
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link text-white">
                                <svg class="bi d-block mx-auto mb-1" width="24" height="24">
                                    <use xlink:href="#grid"></use>
                                </svg>
                                Products
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link text-white">
                                <svg class="bi d-block mx-auto mb-1" width="24" height="24">
                                    <use xlink:href="#people-circle"></use>
                                </svg>
                                Customers
                            </a>
                        </li>
                        <li>
                            <a style="text-decoration: none;" href="scripts/logout.php">
                                <button type="button" class="btn btn-danger">Logout</button>
                            </a>
                        </li>
                    </ul>
                <?php } else { ?>
                    <div class='text-end' bis_skin_checked='1'>
                        <button onclick="loginDisplay()" type="button" class="btn btn-outline-dark me-2">Login</button>
                        <button onclick="signupDisplay()" type="button" class="btn btn-warning">Sign-up</button>
                    </div>
                <?php } ?>

            </div>
        </div>
    </header>
    <!-- THIS IS THE END OF THE HEADER -->

    <header>
        <h1>Welcome to AutoCare Garage</h1>
        <p>Your trusted partner for all car servicing needs</p>
    </header>
    <div class="container">
        <section class="services">
            <div class="service">
                <img src="https://via.placeholder.com/300x200" alt="Oil Change">
                <h3>Oil Change</h3>
                <p>Keep your engine running smoothly with our quick and affordable oil change service.</p>
            </div>
            <div class="service">
                <img src="https://via.placeholder.com/300x200" alt="Brake Service">
                <h3>Brake Service</h3>
                <p>Ensure your safety with our comprehensive brake inspection and repair services.</p>
            </div>
            <div class="service">
                <img src="https://via.placeholder.com/300x200" alt="Tire Rotation">
                <h3>Tire Rotation</h3>
                <p>Extend the life of your tires with our professional tire rotation service.</p>
            </div>
            <div class="service">
                <img src="https://via.placeholder.com/300x200" alt="Battery Check">
                <h3>Battery Check</h3>
                <p>Stay powered up with our thorough battery testing and replacement services.</p>
            </div>
            <div class="service">
                <img src="https://via.placeholder.com/300x200" alt="Engine Diagnostics">
                <h3>Engine Diagnostics</h3>
                <p>Identify and fix engine issues quickly with our advanced diagnostic tools.</p>
            </div>
            <div class="service">
                <img src="https://via.placeholder.com/300x200" alt="AC Service">
                <h3>AC Service</h3>
                <p>Stay cool with our complete air conditioning service and repair.</p>
            </div>
        </section>
    </div>
    <footer>
        <p>&copy; 2024 AutoCare Garage. All rights reserved.</p>
    </footer>

    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>