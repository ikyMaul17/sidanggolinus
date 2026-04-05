<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="index.html" class="app-brand-link">
            
            <span class="app-brand-text demo menu-text fw-bolder ms-2">golinus</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
 
    <!-- Dashboard -->
    <li class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
        <a href="{{ url('dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div data-i18n="Analytics">Dashboard</div>
        </a>
    </li>

    <!-- Forms & Tables -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Master Penumpang</span></li>
    <!-- Forms -->

    <!-- Tables -->
    <li class="menu-item {{ request()->is('fakultas') ? 'active' : '' }}">
        <a href="{{ url('fakultas') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-bookmark"></i>
        <div data-i18n="Tables">Fakultas</div>
        </a>
    </li>

    <li class="menu-item {{ request()->is('jurusan') ? 'active' : '' }}">
        <a href="{{ url('jurusan') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-book-reader"></i>
        <div data-i18n="Tables">Jurusan</div>
        </a>
    </li>

    <li class="menu-item {{ request()->is('penumpang') ? 'active' : '' }}">
        <a href="{{ url('penumpang') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-user"></i>
        <div data-i18n="Tables">Penumpang</div>
        </a>
    </li>

     <!-- Forms & Tables -->
     <li class="menu-header small text-uppercase"><span class="menu-header-text">Master Bus</span></li>
     <!-- Forms -->

    <li class="menu-item {{ request()->is('bus') ? 'active' : '' }}">
        <a href="{{ url('bus') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-bus"></i>
        <div data-i18n="Tables">Bus</div>
        </a>
    </li>

    <li class="menu-item {{ request()->is('supir') ? 'active' : '' }}">
        <a href="{{ url('supir') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-user-voice"></i>
        <div data-i18n="Tables">Supir</div>
        </a>
    </li>

    <li class="menu-item {{ request()->is('halte_pergi') ? 'active' : '' }}">
        <a href="{{ url('halte_pergi') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-map-pin"></i>
        <div data-i18n="Tables">Halte Pergi</div>
        </a>
    </li>

    <li class="menu-item {{ request()->is('halte_pulang') ? 'active' : '' }}">
        <a href="{{ url('halte_pulang') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-map-pin"></i>
        <div data-i18n="Tables">Halte Pulang</div>
        </a>
    </li>

    <!-- Forms & Tables -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Bus Condition</span></li>
    <!-- Forms -->

    <li class="menu-item {{ request()->is('inspection_items') ? 'active' : '' }}">
        <a href="{{ url('inspection_items') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-list-check"></i>
        <div data-i18n="Tables">Item Inspeksi</div>
        </a>
    </li>

    <li class="menu-item {{ request()->is('daily_condition_records') ? 'active' : '' }}">
        <a href="{{ url('daily_condition_records') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-clipboard"></i>
        <div data-i18n="Tables">Cek Rutin Harian</div>
        </a>
    </li>

    <!-- Forms & Tables -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Master User</span></li>
    <!-- Forms -->

    <li class="menu-item {{ request()->is('admin') ? 'active' : '' }}">
        <a href="{{ url('admin') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-user-check"></i>
        <div data-i18n="Tables">Admin</div>
        </a>
    </li>

    <!-- Forms & Tables -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Pemesanan</span></li>
    <!-- Forms -->

    <li class="menu-item {{ request()->is('history_booking') ? 'active' : '' }}">
        <a href="{{ url('history_booking') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-map-alt"></i>
        <div data-i18n="Tables">Histori Pemesanan</div>
        </a>
    </li>

    <li class="menu-item {{ request()->is('tracking_map') ? 'active' : '' }}">
        <a href="{{ url('tracking_map') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-map-pin"></i>
        <div data-i18n="Tables">Map</div>
        </a>
    </li>

    <li class="menu-item {{ request()->is('list_feedback_penumpang') ? 'active' : '' }}">
        <a href="{{ url('list_feedback_penumpang') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-comment-dots"></i>
        <div data-i18n="Tables">Umpan Balik Penumpang</div>
        </a>
    </li>

    <li class="menu-item {{ request()->is('list_feedback_supir') ? 'active' : '' }}">
        <a href="{{ url('list_feedback_supir') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-message-square-dots"></i>
        <div data-i18n="Tables">Umpan Balik Supir</div>
        </a>
    </li>

    <li class="menu-item {{ request()->is('list_kendala') ? 'active' : '' }}">
        <a href="{{ url('list_kendala') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-cog"></i>
        <div data-i18n="Tables">Kendala</div>
        </a>
    </li>

    <!-- Forms & Tables -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Umpan Balik</span></li>
    <!-- Forms -->

    <li class="menu-item {{ request()->is('pertanyaan') ? 'active' : '' }}">
        <a href="{{ url('pertanyaan') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-info-circle"></i>
        <div data-i18n="Tables">Pertanyaan</div>
        </a>
    </li>

    <li class="menu-item {{ request()->is('laporan_umpan_balik') ? 'active' : '' }}">
        <a href="{{ url('laporan_umpan_balik') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-file"></i>
        <div data-i18n="Tables">Laporan</div>
        </a>
    </li>

    <!-- Forms & Tables -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Setting Web</span></li>
    <!-- Forms -->

    <li class="menu-item {{ request()->is('faq') ? 'active' : '' }}">
        <a href="{{ url('faq') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-chat"></i>
        <div data-i18n="Tables">FAQ</div>
        </a>
    </li>

    <li class="menu-item {{ request()->is('announcement') ? 'active' : '' }}">
        <a href="{{ url('announcement') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-info-circle"></i>
        <div data-i18n="Tables">Pengumuman</div>
        </a>
    </li>

    <li class="menu-item {{ request()->is('tutorial') ? 'active' : '' }}">
        <a href="{{ url('tutorial') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-info-circle"></i>
        <div data-i18n="Tables">Tutorial</div>
        </a>
    </li>


    </ul>
</aside>
