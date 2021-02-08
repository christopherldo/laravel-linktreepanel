<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - LinkBree</title>
    <link rel="stylesheet" href="{{url('assets/css/admin.login.css')}}">
</head>
<body>
    <div class="loginArea">
        <h1>Login</h1>

        @if ($errors)
            <div class="error">
                <ul>
                    @foreach ($errors as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST">
            @csrf
            <input {{old('email') ? '' : 'autofocus'}} required value="{{old('email')}}" type="email" name="email" id="email" placeholder="E-mail">
            <input {{old('email') ? 'autofocus' : ''}} required type="password" name="password" id="password" placeholder="Senha">
            <input type="submit" value="Entrar">

            Ainda não tem cadastro? <a href="{{route('register')}}">Cadastre-se</a>
        </form>
    </div>
</body>
</html>
