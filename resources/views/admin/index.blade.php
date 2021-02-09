@extends('admin.template')

@section('title', 'Home - LinkBree')

@section('content')

    <header>
        <h2>Suas páginas</h2>
    </header>

    @if(count($pages) < 2)
        <a class="bigbutton" href="{{route('admin.newpage')}}">
            Nova Página
        </a>
    @endif

    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th width="300">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pages as $page)
                <tr>
                    <td>{{$page->op_title}} ({{$page->slug}})</td>
                    <td>
                        <a href="{{route('page', $page->slug)}}" target="_blank">Abrir</a>
                        <a href="{{route('admin.links', $page->slug)}}">Links</a>
                        <a href="{{route('admin.design', $page->slug)}}">Aparência</a>
                        <a href="{{route('admin.stats', $page->slug)}}">Estatísticas</a>
                        <a href="{{route('admin.delete', $page->slug)}}">Excluir</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection
