@extends('layouts.app')

@section('content')

    <div class="body-div px-4 py-1 mb-3">

        <!-- Tabs -->
        @include('settings.tabs')

        <div class="tab-content" id="myTabContent">

            <!-- Password Tab -->
            <div class="tab-pane show active">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{route('settings.passwordupdate')}}" method="post">
                    @csrf
                    <div class="form-div">
                        <div class="row">
                            <div class="col-sm-12 col-md-4 col-xl-4 mb-3 px-2 inputs">
                                <label for="password1">Old Password</label>
                                <div class="d-flex justify-content-between align-items-center inpflex">
                                    <input type="password" name="password" id="password1"
                                        class="form-control border-0 old_password" required>
                                    <i class="fa-solid fa-eye-slash" id="passHide_1"
                                        onclick="togglePasswordVisibility('password1', 'passShow_1', 'passHide_1')"
                                        style="display:none; cursor:pointer;"></i>
                                    <i class="fa-solid fa-eye" id="passShow_1"
                                        onclick="togglePasswordVisibility('password1', 'passShow_1', 'passHide_1')"
                                        style="cursor:pointer;"></i>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4 col-xl-4 mb-3 px-2 inputs">
                                <label for="password2">New Password</label>
                                <div class="d-flex justify-content-between align-items-center inpflex">
                                    <input type="password" name="new_password" id="password2" class="form-control border-0"
                                        required>
                                    <i class="fa-solid fa-eye-slash" id="passHide_2"
                                        onclick="togglePasswordVisibility('password2', 'passShow_2', 'passHide_2')"
                                        style="display:none; cursor:pointer;"></i>
                                    <i class="fa-solid fa-eye" id="passShow_2"
                                        onclick="togglePasswordVisibility('password2', 'passShow_2', 'passHide_2')"
                                        style="cursor:pointer;"></i>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4 col-xl-4 mb-3 px-2 inputs">
                                <label for="password3">Confirm New Password</label>
                                <div class="d-flex justify-content-between align-items-center inpflex">
                                    <input type="password" name="confirm_password" id="password3"
                                        class="form-control border-0" required onchange="pass_same()">
                                    <i class="fa-solid fa-eye-slash" id="passHide_3"
                                        onclick="togglePasswordVisibility('password3', 'passShow_3', 'passHide_3')"
                                        style="display:none; cursor:pointer;"></i>
                                    <i class="fa-solid fa-eye" id="passShow_3"
                                        onclick="togglePasswordVisibility('password3', 'passShow_3', 'passHide_3')"
                                        style="cursor:pointer;"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center align-items-center mt-3">
                        <button type="submit" class="formbtn">Save</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Pasword Script -->
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

@endsection