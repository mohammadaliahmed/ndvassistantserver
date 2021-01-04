@extends('layouts.tickets')
@section('title', 'Edit Notice')
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
                            {{Form::open(['url'=>['/admin/noticeboard',$notice->id], 'class'=>'defaultForm','method' =>'PUT',  'files' => true])}}
                            <div class="small-border"></div>
                            <small>Edit Notice</small>


                            <div class="form-group">
                                <label class="control-label">Title*:</label>
                                <input type="text" class="form-control" name="title" value="{{$notice->title}}"
                                       required/>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Description*:</label>
                                <textarea type="text" class="form-control"  name="description"
                                          required>{{$notice->description}}</textarea>
                            </div>


                            <div class="submit-button">
                                <button type="submit" class="btn btn-default">Update</button>
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
    <script>
        $(document).ready(function () {
            $('.file-upload-input').attr('value', '{{$notice->avatar}}');
        });
    </script>



@stop
