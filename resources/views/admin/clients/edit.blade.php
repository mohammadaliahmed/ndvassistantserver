@extends('layouts.tickets')
@section('title', 'Edit Client')
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

                            {{Form::open(['url'=>['/admin/clients',$user->id], 'class'=>'defaultForm','method' =>'PUT',  'files' => true])}}
                            <div class="small-border"></div>
                            <small>Edit Client</small>
                            <h1>
                                @if($user->avatar ==  null)
                                    <img src="{{asset('uploads')}}/avatar.png" alt="avatar" class="img-circle"
                                         style="max-height: 40px;">
                                @else
                                    <img src="{{asset('uploads')}}/{{$user->avatar}}" alt="avatar" class="img-circle"
                                         style="max-height: 100px;width: 100px;">
                                @endif
                                {{$user->name}}</h1>
                            <hr>

                            <div class="form-group">
                                <label class="control-label">Name*:</label>
                                <input type="text" class="form-control" name="name" value="{{$user->name}}" required/>
                            </div>

                            <div class="form-group">
                                <label class="control-label">User Name*:</label>
                                <input type="text" class="form-control" readonly name="username"
                                       value="{{$user->username}}" required/>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Email*:</label>
                                <input type="email" class="form-control" name="email" value="{{$user->email}}"
                                       required/>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">House #:</label>
                                    <input type="number" class="form-control" name="housenumber"
                                           value="{{$user->housenumber}}"
                                           required/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Block*:</label>
                                    <input type="text" class="form-control" name="block" value="{{$user->block}}"
                                           required/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Avatar:</label>

                                <div class="custom-file-upload">
                                    <input type="file" id="file" name="file" value="{{$user->avatar}}"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Role*:</label>
                                <select name="role" class="form-control">
                                    <option value="admin" @if($user->hasRole('admin'))selected @endif>Admin</option>
                                    <option value="staff" @if($user->hasRole('staff'))selected @endif>Staff</option>
                                    <option value="client" @if($user->hasRole('client'))selected @endif>Client</option>
                                </select>
                            </div>

                            <div class="submit-button">
                                <button type="submit" class="btn btn-default">Update</button>
                            </div>

                            {{Form::close()}}

                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <span class="h1"> Tickets by {{$user->name}}</span>
                            <div class="table-section">
                                <h3 class="title clearfix">Tickets <span>List ({{count($tickets)}})</span>
                                </h3>
                                <div class="table-responsive">
                                    <table class="table table-lead ticket-table">
                                        <thead>
                                        <tr>
                                            <th class="heading">Token #</th>
                                            <th class="heading">Title</th>
                                            <th class="heading">Department</th>
                                            <th class="heading">Submitted By</th>
                                            <th class="heading">Status</th>
                                            <th class="heading">Date</th>
                                            <th class="heading">action</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($tickets as $ticket)
                                            <tr id="{{$ticket->id}}">
                                                <td>{{$ticket->token_no}}</td>
                                                <td>
                                                    <a href="{{url('ticket')}}/{{$ticket->id}}/{{str_replace(' ', '-',   strtolower($ticket->subject) )}}">{{$ticket->subject}}</a>
                                                </td>
                                                <td>{{$ticket->departments->name}}</td>
                                                @if($ticket->user_id == Auth::id())
                                                    <td>me</td>
                                                @else
                                                    <td>{{$ticket->submittedBy->name}}</td>
                                                @endif

                                                <td>
                                                    <span class="ticket-status {{$ticket->status}}">
                                                        {{$ticket->status}}
                                                    </span>

                                                </td>


                                                <td>{{$ticket->created_at->format('d-m-Y')}}</td>
                                                <td>
                                                    <a href="{{url('edit/tickets')}}/{{$ticket->id}}" class="eye"
                                                       data-id="{{$ticket->id}}">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>

                                                    <a href="javascript:;" class="eye delete-btn"
                                                       data-id="{{$ticket->id}}">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                {{ $tickets->links() }}
                            </div>

                            <div class="pagination_links clearfix">
                                {{--{{ $tickets->links() }}--}}
                            </div>


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
            $('.file-upload-input').attr('value', '{{$user->avatar}}');
        });
    </script>
@stop

