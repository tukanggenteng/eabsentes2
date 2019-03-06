@extends('layouts.app')

@section('title')
Manajemen User
@endsection

@push('style')
<link rel="stylesheet" href="{{asset('bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('bower_components/font-awesome/css/font-awesome.min.css')}}">
<!-- Ionicons -->
<link rel="stylesheet" href="{{asset('bower_components/Ionicons/css/ionicons.min.css')}}">
<!-- daterange picker -->
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('bower_components/select2/dist/css/select2.min.css')}}">
@endpush
@section('body')
    <body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <b>REGISTER EABSEN</b>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Daftar dengan data yang sesuai dengan akun anda.</p>

            <form action="/user/registerpost" method="POST">
                <div class="form-group has-feedback @if($errors->has('email'))
                        has-error
                     @endif">
                    <input type="email" id="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback @if($errors->has('username'))
                       has-error
                    @endif">
                    <input type="text" id="username" name="username" class="form-control" value="{{ old('username') }}" placeholder="Username">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback @if($errors->has('password'))
                        has-error
                     @endif">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback @if($errors->has('name'))
                        has-error
                     @endif">
                    <input type="text" id="name" name="name" class="form-control" placeholder="Nama" value="{{ old('name') }}">
                    <input type="hidden" id="selectrole" name="selectrole" value="1">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>

                {{csrf_field()}}
                <div class="form-group has-feedback">
                    <select class="form-control select2" id="selectinstansi" name="selectinstansi" style="width: 100%;">
                        @foreach($instansis as $instansi)
                            <option value="{{$instansi->id}}">{{$instansi->namaInstansi}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <!-- /.col -->
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Daftar</button>
                    </div>
                    <!-- /.col -->
                    <hr>
                    @if (count($errors)>0)
                    <div class="col-xs-12">
                        <div class="alert alert-danger alert-dismissible">
                            <button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button>
                            @if($errors->has('email'))
                               Kesalahan mengisi email !
                            @elseif($errors->has('username'))
                                Kesalahan mengisi username !
                            @elseif($errors->has('password'))
                                Kesalahan mengisi password !
                            @elseif($errors->has('name'))
                                Kesalahan mengisi nama !
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </form>
        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->

    <script src="{{asset('bower_components/jquery/dist/jquery.min.js')}}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <!-- Select2 -->
    <script src="{{asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>
    <!-- InputMask -->
    <script src="{{asset('plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script src="{{asset('plugins/input-mask/jquery.inputmask.date.extensions.js')}}"></script>
    <script src="{{asset('plugins/input-mask/jquery.inputmask.extensions.js')}}"></script>
    <!-- date-range-picker -->
    <script src="{{asset('bower_components/moment/min/moment.min.js')}}"></script>
    <script src="{{asset('bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <!-- bootstrap color picker -->
    <script src="{{asset('bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js')}}"></script>
    <!-- bootstrap time picker -->
    <script src="{{asset('plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
    <!-- SlimScroll -->
    <script src="{{asset('bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
    <!-- iCheck 1.0.1 -->
    <script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
    <!-- FastClick -->
    <script src="{{asset('bower_components/fastclick/lib/fastclick.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('#instansi').select2();
        })
    </script>
    </body>
@endsection
