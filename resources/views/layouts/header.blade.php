
<header class="main-header">
    <!-- Logo -->
    
        <!-- <a href="/home/ruangan" class="logo">
           
            <span class="logo-mini"><b>EA</b></span>
            <span class="logo-lg"><b>e-Absen</b></span>
        </a> -->
    
        <a href="/" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>EA</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>e-Absen</b></span>
        </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
            <li class="dropdown notifications-menu">
                @if ((Auth::user()->role->namaRole=="rs") || (Auth::user()->role->namaRole=="user"))
                
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-bell-o"></i>
                <span class="label label-warning">
                    <?php
                        $hitung=0;

                        if ($notification[1]['updatefinger']>0)
                        {
                            $hitung=$hitung+1;
                        }
                        if ($notification[0]['pegawaifinger']>0)
                        {
                            $hitung=$hitung+1;
                        }
                        echo $hitung;
                    ?>
                </span>
                </a>
                <ul class="dropdown-menu">
                <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                    @if ($notification[0]['pegawaifinger']>0)
                    <li>
                        <a href="/alat/instansi">
                        <i class="fa fa-users text-aqua"></i> {{$notification[0]['pegawaifinger']}} Pegawai belum ditambahkan
                        </a>
                    </li>
                    @endif
                    @if ($notification[1]['updatefinger']>0)
                    <li>
                        <a href="/alat/instansi/sidikjari">
                        <i class="fa fa-users text-yellow"></i> {{$notification[1]['updatefinger']}} Pegawai sidik jari belum berubah
                        </a>
                    </li>
                    @endif
                    </ul>
                </li>
                <!-- <li class="footer"><a href="#">View all</a></li> -->
                </ul>
                @endif
            </li>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{asset('dist/img/avatarumum.png')}}" class="user-image" alt="User Image">
                        <span class="hidden-xs">{{Auth::user()->name}}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="{{asset('dist/img/avatarumum.png')}}" class="img-circle" alt="User Image">

                            <p>
                                {{Auth::user()->name}}
                                <small>{{Auth::user()->instansi->namaInstansi}}</small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="/changepassword" class="btn btn-default btn-flat">Edit Profil</a>
                            </div>
                            <div class="pull-right">
                                <a href="/logout" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
