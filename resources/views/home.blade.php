@extends('layouts.app')
@section('content')
    @include('default')
    <div class="bg-white rounded shadow" style="padding: 24px; margin: 10px;">
        {!! $chart->container() !!}
    </div>

    <script src="{{ $chart->cdn() }}"></script>

    {{ $chart->script() }}
@endsection
