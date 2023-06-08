@if(session('username')!=null){
    <script>window.location.href='/admin/index';</script>
}
@endif
<!DOCTYPE html>
<html>
    <head>
        <title>Slide Navbar</title>
        <link rel="stylesheet" type="text/css" href="slide navbar style.css">
        <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
        <link rel="stylesheet" href={{asset('assets-1/css/styles.css')}}>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    </head>
    <body>
        <div class="main">
            <input type="checkbox" id="chk" aria-hidden="true">

            <div class="signup">
                <form method="POST" id="regForm">
                    @csrf
                    <label for="chk" aria-hidden="true">Sign up</label>
                    <input type="text" name="username" id="username" placeholder="User name" required="">
                    <span class="name_err"></span>
                    <input type="email" name="email" id="Email" placeholder="Email" required="">
                    <span class="email_err"></span>
                    <input type="password" name="password" id="Password" placeholder="Password" required="">
                    <span class="password_err"></span>
                    <button type="submit">Sign up</button>
                    <span class="logedin"></span>
                </form>
            </div>
            <div class="login">
                <form method="POST" id="myForm">
                    @csrf
                    <label for="chk" aria-hidden="true">Login</label>
                    <input type="email" name="email" id="email" placeholder="Email">
                    <span class="email_err"></span>
                    <input type="password" name="password" id="password" placeholder="Password" >
                    <span class="password_err"></span>
                    <button type="submit">Login</button>
                </form>
            </div>
        </div>
    <script>
        // Login Form Validation
        $(document).ready(function(){
            $('#myForm').submit(function(e){
                e.preventDefault();
                var _token = $("input[name='_token']").val();
                var email = $('#email').val();
                var password = $('#password').val();

                $.ajax({
                    url:"{{route('validateLoginForm')}}",
                    type:"POST",
                    data: {_token:_token,email:email,password:password},
                    success: function(data){
                        if($.isEmptyObject(data.error)){
                            if(data.success){
                                $('.email_err').text('');
                                $('.password_err').text('');
                                window.location.href = "/admin/index";
                            }
                            else{
                                alert(data.failed);
                                window.location.href = '/admin/login';
                            }
                        }
                        else{
                            printErrorMsg(data.error);
                        }
                    },
                    // cache: true,
                    // contentType: true,
                    // processData: true
                });

            });
            function printErrorMsg(msg){
            $.each(msg,function(key,value){
                $('.'+key+'_err').text(value);
            })
        }
        });

        //Registration Form validation
        $(function(){
            $('#regForm').submit(function(e){
                e.preventDefault();
                var _token = $("input[name='_token']").val();
                var username = $('#username').val();
                var email = $('#Email').val();
                var password = $('#Password').val();

                $.ajax({
                    url:"{{route('validateRegForm')}}",
                    type:"POST",
                    data: {_token:_token,username:username,email:email,password:password},
                    success: function(data){
                        if($.isEmptyObject(data.error)){
                            $('.name_err').text('');
                            $('.email_err').text('');
                            $('.password_err').text('');
                            window.location.href = "/admin/login";
                            $('.logedin').text('Click on Login');
                        }
                        else{
                            printErrorMsg(data.error);
                        }
                    },
                    // cache: true,
                    // contentType: true,
                    // processData: true
                });

            });
            function printErrorMsg(msg){
            $.each(msg,function(key,value){
                $('.'+key+'_err').text(value);
            })
        }
        });
    </script>
    </body>
</html>
