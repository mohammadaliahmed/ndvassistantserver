@extends('layouts.tickets')
@section('title', 'Add new Admin')
@section('content')
    <section id="category-one">
        <div class="category-one">
            <div class="container contact">
                <div class="submit-area">
                    <div class="row">

                        <div class="col-md-10 col-md-offset-1">
                            @if(Session::has('message'))
                                <div class="alert alert-success alert-dismissable">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Alert!</strong> {{ Session::get('message') }}
                                </div>
                            @endif
                            @if(count($errors->all()))
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger alert-dismissable">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <strong>Alert!</strong> {{ $error }}
                                    </div>
                                @endforeach
                            @endif

                            {{Form::open(['url'=>'/admin/sendnotification', 'method' =>'post'])}}
                            <div class="small-border"></div>
                            <h1>Send Notification</h1>
                            <hr>

                            <div class="form-group">
                                <label class="control-label">Title*:</label>
                                <input type="text" class="form-control" name="title" value="{{ old('title') }}"
                                       required/>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Message*:</label>
                                <textarea type="text" class="form-control" name="message"
                                          required>
                                    {{ old('message') }}
                                </textarea>
                            </div>


                            <div class="submit-button">
                                <button type="submit" class="btn btn-default">Send</button>
                            </div>

                            {{Form::close()}}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('script')

@stop

