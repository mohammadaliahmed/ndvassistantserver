@extends('layouts.tickets')
@section('title', 'Add Notice')
@section('content')
    <section id="category-one">
        <div class="category-one">
            <div class="container contact">
                <div class="submit-area">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">

                            @if(count($errors->all()))
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger alert-dismissable">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <strong>Alert!</strong> {{ $error }}
                                    </div>
                                @endforeach
                            @endif

                            {{Form::open(['url'=>'/admin/noticeboard', 'method' =>'post',  'files' => true])}}
                            <div class="small-border"></div>
                            <h1>Add Notice</h1>
                            <hr>

                            <div class="form-group">
                                <label class="control-label">Title*:</label>
                                <input type="text" class="form-control" name="title" value="{{ old('title') }}" required/>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Description</label>
                                <textarea type="text" class="form-control" name="description"
                                          required>{{ old('description') }}</textarea>
                            </div>


                            <div class="submit-button">
                                <button type="submit" class="btn btn-default">Save</button>
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

