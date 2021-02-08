@extends('admin.template')

@section('title', $page->op_title . ' | Links - LinkBree')

@section('content')
    <div class="preheader">
        {{$page->op_title}}
    </div>

    <div class="area">
        <div class="leftside">
            <header>
                <ul>
                    <li @if($menu === 'links') class="active" @endif>
                        <a href="{{ url('admin/'. $page->slug .'/links') }}">Links</a>
                    </li>
                    <li @if($menu === 'design') class="active" @endif>
                        <a href="{{ url('admin/'. $page->slug .'/design') }}">Aparência</a>
                    </li>
                    <li @if($menu === 'stats') class="active" @endif>
                        <a href="{{ url('admin/'. $page->slug .'/stats') }}">Estatísticas</a>
                    </li>
                </ul>
            </header>

            @yield('body')
        </div>
        <div class="rightside">
            <iframe src="{{ url($page->slug) }}" frameborder="0"></iframe>
        </div>
    </div>
@endsection
