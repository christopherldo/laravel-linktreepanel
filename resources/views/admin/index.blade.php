@extends('admin.template')

@section('title', 'Home - LinkBree')

@section('content')

    <header>
        <h2>Suas páginas</h2>
    </header>

    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th width="20">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pages as $page)
                <tr>
                    <td>{{$page->op_title}} ({{$page->slug}})</td>
                    <td>
                        <a href="{{url('/'.$page->slug)}}" target="_blank">Abrir</a>
                        <a href="{{url('admin/'.$page->slug.'/links')}}">Links</a>
                        <a href="{{url('admin/'.$page->slug.'/design')}}">Aparência</a>
                        <a href="{{url('admin/'.$page->slug.'/stats')}}">Estatísticas</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection
