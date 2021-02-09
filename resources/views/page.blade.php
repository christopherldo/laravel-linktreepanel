<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="{{ $principal_color }}">
    <title>{{ $title }} - LinkBree</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            font-family: Helvetica, Arial, sans-serif;
            background: {{ $bg }};
            color: {{ $font_color }};
        }

        .profileImage img {
            width: auto;
            height: 100px;
            border-radius: 10px;
        }

        .profileTitle {
            font-size: 17px;
            font-weight: bold;
            margin-top: 10px;
        }

        .profileDescription {
            max-width: 300px;
            text-align: center;
            font-size: 15px;
            margin-top: 5px;
        }

        .linkArea {
            display: flex;
            flex-flow: column;
            align-items: center;
            width: 100%;
            margin: 25px 0;
        }

        .linkArea a {
            width: 100%;
            max-width: 920px;
            display: block;
            padding: 15px;
            text-align: center;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            margin-top: 7.5px;
            margin-bottom: 7.5px;
            box-shadow:
                0 2.8px 2.2px rgba(0, 0, 0, 0.03),
                0 6.7px 5.3px rgba(0, 0, 0, 0.04),
                0 5px 17.9px rgba(0, 0, 0, 0.07),
                0 5px 33.4px rgba(0, 0, 0, 0.08),
                0 1px 1px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease-in-out 0s;
        }

        .linkArea a:hover {
            opacity: 0.5;
        }

        .linkArea a.link-square {
            border-radius: 0
        }

        .linkArea a.link-rounded {
            border-radius: 10px;
        }

        .banner {
            text-align: center;
        }

        .banner a {
            color: {{ $font_color }};
        }

    </style>
</head>

<body>

    <div class="profileImage">
        <img src="{{ $profile_image }}" alt="Profile Image">
    </div>

    <div class="profileTitle">
        {{ $title }}
    </div>

    <div class="profileDescription">
        {{ $description }}
    </div>

    <div class="linkArea">
        @foreach ($links as $link)
            <a href="{{ $link->href }}" class="link-{{ $link->op_border_type }}"
                style="background-color: {{ $link->op_bg_color }}; color: {{ $link->op_text_color }}"
                target="_blank">
                {{ $link->title }}
            </a>
        @endforeach
    </div>

    <div class="banner">
        Feito com ❤️ por <a href="{{route('page', 'christopherldo')}}">christopherldo</a>
    </div>
</body>

</html>
