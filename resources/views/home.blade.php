@extends('layouts.app')
@section('content')
    <div class="bg-white rounded shadow" style="padding: 24px; margin: 40px;">
        {!! $chart->container() !!}
    </div>

    <script src="{{ $chart->cdn() }}"></script>

    {{ $chart->script() }}
@endsection
