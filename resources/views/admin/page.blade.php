@extends('admin.template')

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            {{ $page->op_title }}
        </div>
        <div class="card-body">
            <div class="row row-cols-lg-6 m-2 d-flex flex-wrap justify-content-center justify-content-lg-start">
                <a class="btn m-1 {{ $menu === 'links' || $menu === 'newLink' || $menu === 'editLink' ? 'btn-primary' : 'btn-dark' }}"
                    href="{{ url('admin/' . $page->slug . '/links') }}">Links</a>
                <a class="btn m-1 {{ $menu === 'design' ? 'btn-primary' : 'btn-dark' }}"
                    href="{{ url('admin/' . $page->slug . '/design') }}">Aparência</a>
                <a class="btn m-1 {{ $menu === 'stats' ? 'btn-primary' : 'btn-dark' }}"
                    href="{{ url('admin/' . $page->slug . '/stats') }}">Estatísticas</a>
                @if ($menu !== 'newLink')
                    <a class="btn m-1 {{ $menu === 'newLink' ? 'btn-primary' : 'btn-warning' }}"
                        href="{{ route('admin.newlink', $page->slug) }}">
                        Novo Link
                    </a>
                @endif
            </div>
            <div class="d-flex flex-wrap justify-content-center">
                <div class="flex-fill">
                    @yield('body')
                </div>
                @if ($menu === 'links')
                    <iframe class="my-4 rounded-3" style="width: 400px; min-height: 700px;"
                        src="{{ url($page->slug) }}" frameborder="0"></iframe>
                @endif
            </div>
        </div>
    </div>
@endsection
