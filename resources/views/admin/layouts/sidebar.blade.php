<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('admin.home') }}">
                <i class="bi bi-grid"></i>
                <span>Trang quản trị</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#categories-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-card-list"></i><span>Quản lý kho</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="categories-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('admin.nguyen.lieu.tho.index') }}">
                        <i class="bi bi-circle"></i><span>Kho NL Thô</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.nguyen.lieu.phan.loai.index') }}">
                        <i class="bi bi-circle"></i><span>Kho NL Phân loại</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="bi bi-circle"></i><span>Kho NL Tinh</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="bi bi-circle"></i><span>Kho NL Sản xuất</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="bi bi-circle"></i><span>Kho NL Thành phẩm</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Categories Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#attributes-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Quản lý sản phẩm</span><i
                        class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="attributes-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="">
                        <i class="bi bi-circle"></i><span>Danh sách</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Attributes Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#properties-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-journal-text"></i><span>Quản lí nhà cung cấp</span><i
                        class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="properties-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('admin.nha.cung.cap.index') }}">
                        <i class="bi bi-circle"></i><span>Danh sách</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Properties Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#products-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-layout-text-window-reverse"></i><span>Bán hàng</span><i
                        class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="products-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="">
                        <i class="bi bi-circle"></i><span>Danh sách</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Products Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#orders-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-bar-chart"></i><span>Quản lý khách hàng</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="orders-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="">
                        <i class="bi bi-circle"></i><span>Danh sách</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Orders Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#news-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-newspaper"></i><span>Sổ quỹ</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="news-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="#">
                        <i class="bi bi-circle"></i><span>Danh sách</span>
                    </a>
                </li>
            </ul>
        </li><!-- End News Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#purchases-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-people"></i><span>Quản lý nhân sự</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="purchases-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="">
                        <i class="bi bi-circle"></i><span>Danh sách</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Purchases Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#consultants-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-question-circle"></i><span>Lương + OKR</span><i
                        class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="consultants-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="">
                        <i class="bi bi-circle"></i><span>Danh sách</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Consultants Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('admin.app.setting.index') }}">
                <i class="bi bi-gear"></i>
                <span>Cài đặt website</span>
            </a>
        </li><!-- End Setting Page Nav -->

        <li class="nav-heading">Trang</li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#">
                <i class="bi bi-person"></i>
                <span>Trang cá nhân</span>
            </a>
        </li><!-- End Profile Page Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('auth.logout') }}">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Đăng xuất</span>
            </a>
        </li><!-- End Logout Page Nav -->

    </ul>

</aside>
