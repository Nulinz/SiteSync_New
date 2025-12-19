<!DOCTYPE html>
<html>

<head>
    <title>Site Sync</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="dHcoep7zcn3yrvwTcFoWMXFLInpdSrBhRi57vEtCmNE" />

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/images/favicon-new.png') }}" sizes="32*32" type="image/png">

    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- Font / Icons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">

    <!-- jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">

</head>

<body>

    <div class="form-structor">
        <div class="signup">
            <div class="logo d-flex justify-content-center align-items-center" id="signup">
                <img src="{{ asset('assets/images/logo_1.png') }}" height="50px" alt="">
            </div>
        </div>
        <div class="login slide-up">
            <div class="center">
                <h2 class="form-title" id="login">Log In</h2>
                <form action="{{ route('login') }}" method="post">
                    @csrf
                    @if ($errors->has('email'))
                        <div class="text-danger text-center">{{ $errors->first('email') }}</div>
                    @endif
                    <div class="form-holder row">
                        <div class="col-10 mx-auto mb-2">
                            <input type="text" class="form-control" name="employee_code" placeholder="Employee Code"
                                required autofocus>
                            @error('employee_code')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-10 mx-auto mb-2">
                            <div class="inpflex">
                                <input type="password" class="form-control border-0" name="password" id="password1"
                                    minlength="6" placeholder="Password" required>
                                <i class="fa-solid fa-eye-slash" id="passHide_1"
                                    onclick="togglePasswordVisibility('password1', 'passShow_1', 'passHide_1')"
                                    style="display:none; cursor:pointer;"></i>
                                <i class="fa-solid fa-eye" id="passShow_1"
                                    onclick="togglePasswordVisibility('password1', 'passShow_1', 'passHide_1')"
                                    style="cursor:pointer;"></i>
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="submit-btn">Login</button>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.3/gsap.min.js"></script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

<script>
    gsap.fromTo(
        ".loading-page", {
            opacity: 1
        }, {
            opacity: 0,
            display: "none",
            duration: 1,
            delay: 5,
        }
    );

    gsap.fromTo(
        ".name-container", {
            y: 50,
            opacity: 0,
        }, {
            y: 0,
            opacity: 1,
            duration: 1,
            delay: 2.5,
        }
    );
</script>

<script>
    function togglePasswordVisibility(inputId, showId, hideId) {
        let $input = $('#' + inputId);
        let $passShow = $('#' + showId);
        let $passHide = $('#' + hideId);

        if ($input.attr('type') === 'password') {
            $input.attr('type', 'text');
            $passShow.hide();
            $passHide.show();
        } else {
            $input.attr('type', 'password');
            $passShow.show();
            $passHide.hide();
        }
    }
</script>

<script>
    console.clear();

    const loginBtn = document.getElementById('login');
    const signupBtn = document.getElementById('signup');

    loginBtn.addEventListener('click', (e) => {
        let parent = e.target.parentNode.parentNode;
        Array.from(e.target.parentNode.parentNode.classList).find((element) => {
            if (element !== "slide-up") {
                parent.classList.add('slide-up')
            } else {
                signupBtn.parentNode.classList.add('slide-up')
                parent.classList.remove('slide-up')
            }
        });
    });

    signupBtn.addEventListener('click', (e) => {
        let parent = e.target.parentNode;
        Array.from(e.target.parentNode.classList).find((element) => {
            if (element !== "slide-up") {
                parent.classList.add('slide-up')
            } else {
                loginBtn.parentNode.parentNode.classList.add('slide-up')
                parent.classList.remove('slide-up')
            }
        });
    });
</script>

</html>
