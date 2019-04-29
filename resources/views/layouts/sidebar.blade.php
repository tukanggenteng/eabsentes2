@if (Auth::user()->role->namaRole!="kadis")
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
                            <li><a href="/harilibur"><i class="fa fa-circle-o"></i> Hari Libur Pegawai</a></li>
                            <li><a href="/role/harilibur"><i class="fa fa-circle-o"></i> Aturan Hari Libur Pegawai</a></li>
                            <li><a href="/finger"><i class="fa fa-circle-o"></i> Manajemen Finger</a></li>
                            <li><a href="/jadwalkerja"><i class="fa fa-circle-o"></i> Jadwal Kerja</a></li>
                            <li><a href="/pegawai/import"><i class="fa fa-circle-o"></i> Import Pegawai</a></li>
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
                            <li><a href="/historyfingerpegawai"><i class="fa fa-circle-o"></i> History Fingerprint</a></li>
                            <li><a href="/historycrashraspberry"><i class="fa fa-circle-o"></i> History Crash Raspberry</a></li>
                            <li><a href="/queue/pegawai"><i class="fa fa-circle-o"></i> <span> Antrian Distribusi Pegawai</span></a></li>
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
                @elseif (Auth::user()->role->namaRole=="rs")
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
                            <li><a href="/manajemenpegawaikhusus"><i class="fa fa-circle-o"></i> Manajemen Pegawai</a></li>
                            <li><a href="/harilibur/pegawai"><i class="fa fa-circle-o"></i> Pegawai Libur Nasional</a></li>
                            <li><a href="/role/harilibur"><i class="fa fa-circle-o"></i> Hari Libur Nasional</a></li>
                            <li><a href="/harikerja"><i class="fa fa-circle-o"></i> Atur Hari Kerja</a></li>
                            <li><a href="/jadwalkerjapegawai"><i class="fa fa-circle-o"></i> Jadwal Pegawai</a></li>
                            <li><a href="/keteranganabsen"><i class="fa fa-circle-o"></i> Keterangan Absen</a></li>
                            <li><a href="/rekapabsensipegawai"><i class="fa fa-circle-o"></i> Keterangan Absen Harian</a></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-users"></i>
                            <span>User</span>
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="/userkhusus"><i class="fa fa-circle-o"></i> Manajemen User</a></li>
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
                        <li><a href="/laporanmingguan"><i class="fa fa-circle-o"></i> Mingguan</a></li>
                            <li><a href="/laporanbulan"><i class="fa fa-circle-o"></i> Bulanan</a></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-bullseye"></i>
                                    <span>Alat</span>
                                        <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                            @if ($notification[0]['pegawaifinger']>0)
                                                <small class="label pull-right bg-red">{{$notification[0]['pegawaifinger']}}</small>
                                            @endif
                                        </span>

                        </a>
                        <ul class="treeview-menu">
                        <li><a href="/alat/instansi"><i class="fa fa-circle-o"></i> <span> Alat untuk data pegawai</span>
                                    @if ($notification[0]['pegawaifinger']>0)
                                        <small class="label pull-right bg-red">{{$notification[0]['pegawaifinger']}}</small>
                                    @endif
                        </a></li>
                        
                        </ul>
                    </li>
                @elseif (Auth::user()->role->namaRole=="karu")
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
                            <li><a href="/manajemanpegawairuangan"><i class="fa fa-circle-o"></i> Manajemen Pegawai</a></li>
                            <li><a href="/jadwalkerjapegawaiharian"><i class="fa fa-circle-o"></i> Jadwal Pegawai</a></li>
                            <li><a href="/keteranganabsen"><i class="fa fa-circle-o"></i> Keterangan Absen</a></li>
                            <li><a href="/rekapabsensipegawai"><i class="fa fa-circle-o"></i> Keterangan Absen Harian</a></li>
                        </ul>
                    </li>
                @elseif (Auth::user()->role->namaRole=="bkd")
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-area-chart"></i>
                            <span>Grafik</span>
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="/monitoring/grafik/harian"><i class="fa fa-circle-o"></i> Monitoring Grafik Harian</a></li>
                            <li><a href="/monitoring/grafik/bulanan"><i class="fa fa-circle-o"></i> Monitoring Grafik Bulanan</a></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-folder-o"></i>
                            <span>Rekap Absen</span>
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="/monitoring/pegawai"><i class="fa fa-circle-o"></i> Monitoring Absen Pegawai</a></li>
                            <li><a href="/monitoring/instansi"><i class="fa fa-circle-o"></i> Monitoring Absen Instansi</a></li>
                        </ul>
                    </li>
                    <!-- <li class="treeview">
                        <a href="#">
                            <i class="fa fa-dashboard"></i> <span>Rekap</span>
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="/rekapbulanan/rekapbulanan/admin"><i class="fa fa-circle-o"></i> Verifikasi Surat</a></li>
                        </ul>
                    </li> -->
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
                            <li><a href="/harilibur/pegawai"><i class="fa fa-circle-o"></i> Pegawai Libur Nasional</a></li>
                            <li><a href="/role/harilibur"><i class="fa fa-circle-o"></i> Hari Libur Nasional</a></li>
                            <li><a href="/harikerja"><i class="fa fa-circle-o"></i> Atur Hari Kerja</a></li>
                            <li><a href="/jadwalkerjapegawai"><i class="fa fa-circle-o"></i> Jadwal Pegawai</a></li>
                            <li><a href="/keteranganabsen"><i class="fa fa-circle-o"></i> Keterangan Absen</a></li>
                            <li><a href="/rekapabsensipegawai"><i class="fa fa-circle-o"></i> Keterangan Absen Harian</a></li>

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
                        <li><a href="/laporanmingguan"><i class="fa fa-circle-o"></i> Mingguan</a></li>
                        <li><a href="/laporanbulan"><i class="fa fa-circle-o"></i> Bulanan</a></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-bullseye"></i>
                                    <span>Alat</span>
                                        <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                        
                                        @if ($notification[0]['pegawaifinger']>0)
                                            <small class="label pull-right bg-red">{{$notification[0]['pegawaifinger']}}</small>
                                        @endif
                                        </span>

                        </a>
                        <ul class="treeview-menu">
                        <li><a href="/alat/instansi"><i class="fa fa-circle-o"></i> <span> Alat untuk data pegawai</span>
                                    @if ($notification[0]['pegawaifinger']>0)
                                        <small class="label pull-right bg-red">{{$notification[0]['pegawaifinger']}}</small>
                                    @endif
                        </a></li>
                        <li><a href="/queue/pegawai"><i class="fa fa-circle-o"></i> <span> Antrian Distribusi Pegawai</span>
                        </a></li>
                        </ul>
                    </li>
                @endif
            </ul>
    </aside>
@endif