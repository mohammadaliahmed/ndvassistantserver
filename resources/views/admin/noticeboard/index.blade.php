@extends('layouts.tickets')
@section('title', 'Tickets')
@section('content')
    <div class="page-content">
        <section id="category-one">
            <div class="category-one">
                <div class="container">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            @if(Session::has('message'))
                                <div class="alert alert-success alert-dismissable">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Alert!</strong> {{ Session::get('message') }}
                                </div>
                            @endif

                            <div class="table-section">
                                <div class="table-responsive">
                                    <table class="table table-lead user-table">
                                        <h3 class="title clearfix">Staff <span>List</span>
                                            <a href="{{url('admin/noticeboard/create')}}" class="pull-right">Add new</a>

                                        </h3>
                                        <thead>
                                        <tr>
                                            <th class="heading">Title</th>
                                            <th class="heading">Description</th>
                                            <th class="heading">Action</th>


                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($notices as $notice)
                                            <tr id="{{$notice->id}}">
                                                <td>{{$notice->title}}</td>
                                                <td>{{$notice->description}}</td>
                                                <td><a href="{{url('admin/noticeboard')}}/{{$notice->id}}/edit"
                                                       class="eye">
                                                        <i class="fa fa-pencil"></i></a>
                                                    <a href="#" class="eye delete-btn" data-id="{{$notice->id}}">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@stop
@section('script')

    <script>
        $(document).ready(function () {
            $(document).on('click', '.delete-btn', function () {
                var id = $(this).attr('data-id');
                swal({
                        title: "Are you sure?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        cancelButtonText: "No, cancel!",
                        closeOnConfirm: false,
                        closeOnCancel: true
                    },
                    function(isConfirm){
                        if (isConfirm) {
                            $.ajax({
                                type: 'DELETE',
                                url: '{{url('/admin/noticeboard')}}'+"/"+id,
                                data:{
                                    id:id,
                                    '_token': '{{csrf_token()}}'
                                },
                                success: function (data) {
                                    $('.user-table tr#'+id+'').hide();
                                    swal("Deleted!", "Notice has been deleted.", "success");

                                }
                            })
                        } else {
                            swal("Cancelled", "Notice is safe :)", "error");
                        }
                    });
            });
        });
    </script>

@stop

