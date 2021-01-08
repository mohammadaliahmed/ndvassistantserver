@extends('layouts.tickets')
@section('title', 'Tickets')
@section('content')

    <div class="page-content">
        <section id="category-one">
            <div class="category-one">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">


                            <div class="table-section">
                                <center>
                                    <br>
                                    <br>
                                    <span class="heading">Tickets Past Due</span>
                                    <br>
                                    <br>
                                    <img src="images/ic_pending_tickets.png">&emsp;&emsp;<span class="red-count">4</span>
                                    <br>
                                    <br>
                                    <br>
                                </center>
                            </div>
                        </div>
                        <div class="col-md-4 ">
                            <div class="table-section">
                                <center>
                                    <br>
                                    <br>
                                    <span class="heading">New Tickets Today</span>
                                    <br>
                                    <br>
                                    <img src="images/ic_new_tickets.png">&emsp;&emsp;<span class="blue-count">4</span>
                                    <br>
                                    <br>
                                    <br>
                                </center>

                            </div>
                        </div>
                        <div class="col-md-4 ">
                            <div class="table-section">
                                <center>
                                    <br>
                                    <br>
                                    <span class="heading">Tickets Past Due</span>
                                    <br>
                                    <br>
                                    <img src="images/ic_tickets_closed.png">&emsp;&emsp;<span class="orange-count">4</span>
                                    <br>
                                    <br>
                                    <br>
                                </center>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>


@stop
@section('script')
    <script src="https://www.gstatic.com/firebasejs/8.0.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.0.1/firebase-messaging.js"></script>
    {{--<script src="../init.js"/>--}}
    <script>
        const firebaseConfig = {
            apiKey: "AIzaSyD2E1rfj5irI_BmZTACEuv1KR9Amyql_aU",
            authDomain: "ndvassistant.firebaseapp.com",
            projectId: "ndvassistant",
            storageBucket: "ndvassistant.appspot.com",
            messagingSenderId: "355087823038",
            appId: "1:355087823038:web:dea3ffd052adb6ad126eef",
            measurementId: "G-BRTBKGRDH1"
        };
        firebase.initializeApp(firebaseConfig);


        const messaging = firebase.messaging();
        messaging.requestPermission()
            .then(function (token) {
                console.log('have permission');
                return messaging.getToken();
            })
            .then(function (abctoken) {
                console.log(abctoken)




                <?php echo e(\Illuminate\Support\Facades\Auth::user()->id)?>;
                $.ajax({
                    type: 'POST',
                    url: '{{url('/adminFcmKey/')}}',
                    data: {
                        'adminId':{{\Illuminate\Support\Facades\Auth::user()->id}},
                        'fcmKey': abctoken,
                        '_token': '{{csrf_token()}}'

                    },
                    success: function (data) {
//                        $('.reply_to_delete').hide();
//                        swal("Updated", "Reply message has been deleted.", "success");

                    }
                })
            })
            .catch(function (error) {
                console.log('error here' + error);
            })

        messaging.onMessage(function (payload) {
            console.log('onMessage', payload);

        })
    </script>

    <script>

        $(document).ready(function () {
            $('.navbar-default .navbar-nav li').removeClass('active');
            $('.navbar-default .navbar-nav li.ticket').addClass('active');

            $(".delete-btn").on('click', function () {
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
                    function (isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                type: 'POST',
                                url: '{{url('/delete/tickets')}}' + "/" + id,
                                data: {
                                    id: id,
                                    '_token': '{{csrf_token()}}'
                                },
                                success: function (data) {
                                    $('.ticket-table tr#' + id + '').hide();
                                    swal("Deleted!", "Record has been deleted.", "success");

                                }
                            })
                        } else {
                            swal("Cancelled", "Record is safe :)", "error");
                        }
                    });
            });
        });
    </script>

@stop

