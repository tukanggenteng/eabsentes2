@extends('layouts.app')

@section('body')
    <body class="hold-transition login-page">
    <div class="login-box">
        
        <!-- /.login-logo -->
        <div class="login-box-body">
            <div class="login-logo">
                <img src="/dist/img/logo.png" alt="">
            </div>
            <div style="font-size: 35px;text-align: center;max-width: 100%;">
                <strong>E-ABSEN</strong>
                <p style="font-size: 15px;text-align: center;max-width: 100%;"><small>Pemerintah Provinsi Kalimantan Selatan</small></p>
            </div>

            <p class="login-box-msg">
                    Buku Panduan <a href="https://www.dropbox.com/s/mll1adop1b02rrv/Tutorial%20Eabsen.zip?dl=0">E-Absen</a>
                <br>
                    Panduan Video <a href="https://www.youtube.com/playlist?list=PLxEvuuapMZIoXf6yhyDtjJrC3HneExOYx">E-Absen</a>
            </p>

            <form action="/" method="post">
                <div class="form-group has-feedback">
                    <input type="text" id="username" name="username" class="form-control" placeholder="Email/Username/NIP">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                {{csrf_field()}}
                <div class="form-group has-feedback">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <!-- /.col -->
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                    </div>
                    <hr>
                    <div class="col-xs-12">
                    @if (session('error')=="Logout Berhasil")
                        @if (!empty(session('error')))
                            <div class="alert alert-success alert-dismissible">
                                <button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button>
                                {{ session('error') }}
                            </div>
                        @endif
                    @else
                        @if (!empty(session('error')))
                            <div class="alert alert-danger alert-dismissible">
                                <button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button>
                                {{ session('error') }}
                            </div>
                        @endif
                    @endif
                        <p><small>Dikembangkan oleh <a href="diskominfo.kalselprov.go.id">Diskominfo Prov. Kalsel</a> &copy; 2017</small></p>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery 3 -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="plugins/iCheck/icheck.min.js"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
    </body>
    @endsection
