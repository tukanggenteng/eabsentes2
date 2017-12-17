
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{asset('dist/img/avatarumum.png')}}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{Auth::user()->name}}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">Navigasi Utama</li>
            @if (Auth::user()->role->namaRole=="admin")
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i> <span>Pegawai</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="/pegawai"><i class="fa fa-circle-o"></i> Manajemen Pegawai</a></li>
                    <li><a href="/finger"><i class="fa fa-circle-o"></i> Manajemen Finger</a></li>
                    <li><a href="/jadwalkerja"><i class="fa fa-circle-o"></i> Jadwal Kerja</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-bank"></i> <span>Instansi</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="/instansi"><i class="fa fa-circle-o"></i> Manajemen Instansi</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-thumbs-up"></i> <span>Fingerprint</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="/macaddress"><i class="fa fa-circle-o"></i> Manajemen Mac Addr</a></li>
                    <li><a href="/trigger"><i class="fa fa-circle-o"></i> Atur Trigger</a></li>
                    <li><a href="/raspberry"><i class="fa fa-circle-o"></i> Status Raspberry</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>Rekap</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="/rekapbulanan/rekapbulanan/admin"><i class="fa fa-circle-o"></i> Rekap Absen Mingguan</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i> <span>User</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="/user"><i class="fa fa-circle-o"></i> Manajemen User</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-file-text"></i> <span>Surat</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="/ijin/admin"><i class="fa fa-circle-o"></i> Ijin</a></li>
                    <li><a href="/sakit/admin"><i class="fa fa-circle-o"></i> Sakit</a></li>
                    <li><a href="/cuti/admin"><i class="fa fa-circle-o"></i> Cuti</a></li>
                    <li><a href="/tugasbelajar/admin"><i class="fa fa-circle-o"></i> Tugas Belajar</a></li>
                    <li><a href="/tugasluar/admin"><i class="fa fa-circle-o"></i> Tugas Luar</a></li>
                    <li><a href="/rapatundangan/admin"><i class="fa fa-circle-o"></i> Rapat Undangan</a></li>
                    <li><a href="/ijinterlambat/admin"><i class="fa fa-circle-o"></i> Ijin Terlambat</a></li>
            </li>
            @else
            <li>
                <a href="/timeline">
                    <i class="fa fa-calendar"></i> <span>Timeline</span>
                    
                </a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span>Pegawai</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="/pegawai/show"><i class="fa fa-circle-o"></i> Manajemen Pegawai</a></li>
                    <li><a href="/harikerja"><i class="fa fa-circle-o"></i> Hari Kerja</a></li>
                    <li><a href="/jadwalkerjapegawai"><i class="fa fa-circle-o"></i> Jadwal Pegawai</a></li>
                    <li><a href="/rekapabsensipegawai"><i class="fa fa-circle-o"></i> Keterangan Absen</a></li>
                    <li><a href="/transrekap"><i class="fa fa-circle-o"></i> Laporan Surat</a></li>

                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-file-text"></i> <span>Surat</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="/transrekap/download/ijin"><i class="fa fa-circle-o"></i> Ijin</a></li>
                  <li><a href="/transrekap/download/sakit"><i class="fa fa-circle-o"></i> Sakit</a></li>
                  <li><a href="/transrekap/download/cuti"><i class="fa fa-circle-o"></i> Cuti</a></li>
                  <li><a href="/transrekap/download/tl"><i class="fa fa-circle-o"></i> Tugas Luar</a></li>
                  <li><a href="/transrekap/download/tb"><i class="fa fa-circle-o"></i> Tugas Belajar</a></li>
                  <li><a href="/transrekap/download/ru"><i class="fa fa-circle-o"></i> Rapat Undangan</a></li>
                  <li><a href="/transrekap/download/it"><i class="fa fa-circle-o"></i> Ijin Terlambat</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-calendar"></i> <span>Laporan</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="/laporanharian"><i class="fa fa-circle-o"></i> Harian</a></li>
                    <li><a href="/laporanbulan"><i class="fa fa-circle-o"></i> Bulanan</a></li>
                </ul>
            </li>
            @endif
        </ul>
</aside>
