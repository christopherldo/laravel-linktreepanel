@extends('admin.template')

@section('title', '123 | Links - LinkBree')

@section('content')
    <div class="preheader">
        Página: 123
    </div>

    <div class="area">
        <div class="leftside">
            <header>
                <ul>
                    <li>
                        <a href="{{url('admin/123/links')}}">Links</a>
                        <a href="{{url('admin/123/design')}}">Aparência</a>
                        <a href="{{url('admin/123/stats')}}">Estatísticas</a>
                    </li>
                </ul>
            </header>

            @yield('body')
        </div>
        <div class="rightside">
            <iframe src="{{url('christopherldo')}}" frameborder="0"></iframe>
        </div>
    </div>
@endsection
