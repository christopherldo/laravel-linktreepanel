<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LinkBree</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 20px;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #ddd;
        }
        a {
            height: 100px;
            width: 300px;
            max-width: 100%;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            font-size: 20px;
            display: flex;
            padding: 10px 20px;
            color: #fff;
            background-color: #111;
            border-radius: 10px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <a href="{{route('admin.index')}}">Clique e crie o seu link</a>
</body>
</html>
