@extends('layouts.tickets')
@section('title', 'Reports')
@section('content')
    <section id="category-one">
        <div class="container">
            <br>
            <br>
            <div class="row">
                <div class="col-md-6">{!!$statusChart->container()!!}</div>
                <div class="col-md-6">{!!$departmentChart->container()!!}</div>
            </div>
        </div>

    </section>
@stop

@section('script')

    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    {!!$statusChart->script() !!}
    {!!$departmentChart->script() !!}
@stop

