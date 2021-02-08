<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cadastro - LinkBree</title>
    <link rel="stylesheet" href="{{ url('assets/css/admin.login.css') }}">
</head>

<body>
    <div class="loginArea">
        <h1>Cadastro</h1>

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

            <input {{ old('email') ? '' : 'autofocus' }} required value="{{ old('email') }}" type="email" name="email"
                id="email" placeholder="E-mail">
            <input {{ old('email') ? 'autofocus' : '' }} required type="password" name="password" id="password"
                placeholder="Senha">
            <input required type="password" name="password_confirmation" id="password_confirmation"
                placeholder="Confirme a senha">
            <input type="submit" value="Cadastar">

            Já tem uma conta? <a href="{{ route('login') }}">Faça Login</a>
        </form>
    </div>
</body>

</html>
