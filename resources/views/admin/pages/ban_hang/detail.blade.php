@extends('admin.layouts.master')
@section('title')
    Chỉnh sửa bán hàng
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Chỉnh sửa bán hàng</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa bán hàng</li>
            </ol>
        </nav>
    </div>
    @if(session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif
    <section class="section">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <h5 class="card-title">Chỉnh sửa bán hàng</h5>
                    <form method="post" action="{{ route('admin.ban.hang.update', $banhang) }}">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="khach_hang_id">Khách hàng</label>
                            <select id="khach_hang_id" name="khach_hang_id" class="form-control selectCustom"
                                    onchange="changeKhachHang()">
                                <option value="0">Khách lẻ</option>
                                @foreach($khachhangs as $khachhang)
                                    <option
                                        {{ $khachhang->id == $banhang->khach_hang_id ? 'selected' : '' }}
                                        value="{{ $khachhang->id }}">{{ $khachhang->ten }}
                                        - {{ $khachhang->so_dien_thoai }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row" id="formKhachLe">
                            <div class="form-group col-md-12">
                                <label for="ten_khach_hang">Tên khách hàng</label>
                                <input type="text" class="form-control" id="ten_khach_hang" name="ten_khach_hang"
                                       value="{{ $banhang->khach_le }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="so_dien_thoai">Số điện thoại</label>
                                <input type="text" class="form-control" id="so_dien_thoai" name="so_dien_thoai"
                                       value="{{ $banhang->so_dien_thoai }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="dia_chi">Địa chỉ chi tiết</label>
                                <input type="text" class="form-control" id="dia_chi"
                                       value="{{ $banhang->dia_chi }}" name="dia_chi" required>
                            </div>
                        </div>

                        <div class="row pt-3 mt-4 border-top">
                            <div class="form-group col-md-4">
                                <label for="ok">Tổng tiền</label>
                                <input type="text" class="form-control onlyNumber" disabled readonly id="ok"
                                       name="ok" value="{{ $banhang->tong_tien }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="da_thanht_toan">Khách hàng đã thanh toán</label>
                                <input type="text" class="form-control onlyNumber" id="da_thanht_toan"
                                       name="da_thanht_toan" value="{{ $banhang->da_thanht_toan }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="loai_quy_id">Loại quỹ</label>
                                <select class="form-control selectCustom" name="loai_quy_id" id="loai_quy_id">
                                    @foreach($loai_quies as $loai_quy)
                                        <option {{ $loai_quy->id == $banhang->phuong_thuc_thanh_toan ? 'selected' : '' }}
                                                value="{{ $loai_quy->id }}">{{ $loai_quy->ten_loai_quy }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-3" id="formSanPham">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-group col-md-4 mb-2">
                                    <label for="select_kho">Chọn kho</label>
                                    <select id="select_kho" name="select_kho" class="form-control"
                                            onchange="changeLoaiSanPham()" disabled readonly="">
                                        <option value="">Lựa chọn kho</option>
                                        <option
                                            {{ $banhang->loai_san_pham == \App\Enums\LoaiSanPham::NGUYEN_LIEU_THO ? 'selected' : '' }}
                                            value="{{ \App\Enums\LoaiSanPham::NGUYEN_LIEU_THO }}">
                                            Nguyên liệu Thô
                                        </option>
                                        <option
                                            {{ $banhang->loai_san_pham == \App\Enums\LoaiSanPham::NGUYEN_LIEU_PHAN_LOAI ? 'selected' : '' }}
                                            value="{{ \App\Enums\LoaiSanPham::NGUYEN_LIEU_PHAN_LOAI }}">
                                            Nguyên liệu Phân loại
                                        </option>
                                        <option
                                            {{ $banhang->loai_san_pham == \App\Enums\LoaiSanPham::NGUYEN_LIEU_TINH ? 'selected' : '' }}
                                            value="{{ \App\Enums\LoaiSanPham::NGUYEN_LIEU_TINH }}">
                                            Nguyên liệu Tinh
                                        </option>
                                        <option
                                            {{ $banhang->loai_san_pham == \App\Enums\LoaiSanPham::NGUYEN_LIEU_THANH_PHAM ? 'selected' : '' }}
                                            value="{{ \App\Enums\LoaiSanPham::NGUYEN_LIEU_THANH_PHAM }}">
                                            Nguyên liệu Thành phẩm
                                        </option>
                                    </select>
                                </div>

                                <button class="btn btn-sm btn-primary showForm" type="button"
                                        onclick="addItems()">
                                    <i class="bi bi-plus"></i> Thêm sản phẩm
                                </button>
                            </div>
                            <table class="table table-bordered showForm">
                                <colgroup>
                                    <col width="x">
                                    <col width="25%">
                                    <col width="15%">
                                    <col width="25%">
                                    <col width="5%">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th scope="col">Tên sản phẩm</th>
                                    <th scope="col">Giá bán</th>
                                    <th scope="col">Số lượng/Khối lượng</th>
                                    <th scope="col">Tổng tiền</th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody id="tbodySanPham">
                                @foreach($chiTietBanHangs as $chiTietBanHang)
                                    <tr>
                                        <td>
                                            <select name="san_pham_id[]" class="form-control"
                                                    onchange="changeThongTinSanPham(this)" required>
                                                @foreach($nguyenlieus as $nguyenlieu)
                                                    @php
                                                        switch ($banhang->loai_san_pham) {
                                                            case \App\Enums\LoaiSanPham::NGUYEN_LIEU_THO():
                                                                $label = $nguyenlieu->ten_nguyen_lieu ?? '';
                                                                break;
                                                            case \App\Enums\LoaiSanPham::NGUYEN_LIEU_TINH():
                                                                $label = $nguyenlieu->gia_tien ?? '';
                                                                break;
                                                            case \App\Enums\LoaiSanPham::NGUYEN_LIEU_THANH_PHAM():
                                                                $label = $nguyenlieu->sanPham->ten_san_pham ?? '';
                                                                break;

                                                            case \App\Enums\LoaiSanPham::NGUYEN_LIEU_PHAN_LOAI():
                                                               $label = $nguyenlieu->nguyenLieuTho->ten_nguyen_lieu . ' - ' . $nguyenlieu->nguyenLieuTho->code;
                                                                break;
                                                            default:
                                                                $label = '';
                                                        }
                                                    @endphp

                                                    <option
                                                        {{ $chiTietBanHang->san_pham_id == $nguyenlieu->id ? 'selected' : '' }}
                                                        value="{{ $nguyenlieu->id }}">{{ $label }}</option>
                                                @endforeach

                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" min="0" name="gia_bans[]" class="form-control gia_bans"
                                                   value="{{ $chiTietBanHang->gia_ban }}" required>
                                        </td>
                                        <td>
                                            <input type="number" min="1" name="so_luong[]" class="form-control so_luong"
                                                   value="{{ $chiTietBanHang->so_luong }}"
                                                   oninput="changeGiaSanPham(this)" required>
                                        </td>
                                        <td>
                                            <input type="text" name="tong_tien[]" class="form-control tong_tien"
                                                   value="{{ $chiTietBanHang->tong_tien }}" disabled readonly>
                                        </td>
                                        <td>
                                            <button type="button" onclick="removeItems(this)"
                                                    class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <input type="hidden" name="loai_san_pham" id="loai_san_pham"
                               value="{{ $banhang->loai_san_pham }}">
                        <button type="submit" class="btn btn-primary mt-2">Lưu thay đổi</button>
                    </form>

                </div>

            </div>
        </div>
    </section>
    <table class="d-none">
        <tbody>
        <tr id="listSanPham">
            <td>
                <select name="san_pham_id[]" class="form-control" onchange="changeThongTinSanPham(this)" required>
                    @foreach($nguyenlieus as $nguyenlieu)
                        @php
                            switch ($banhang->loai_san_pham) {
                                case \App\Enums\LoaiSanPham::NGUYEN_LIEU_THO():
                                    $label = $nguyenlieu->ten_nguyen_lieu ?? '';
                                    break;
                                case \App\Enums\LoaiSanPham::NGUYEN_LIEU_TINH():
                                    $label = $nguyenlieu->gia_tien ?? '';
                                    break;
                                case \App\Enums\LoaiSanPham::NGUYEN_LIEU_THANH_PHAM():
                                    $label = $nguyenlieu->sanPham->ten_san_pham ?? '';
                                    break;

                                case \App\Enums\LoaiSanPham::NGUYEN_LIEU_PHAN_LOAI():
                                   $label = $nguyenlieu->nguyenLieuTho->ten_nguyen_lieu ?? '' . ' - ' . $nguyenlieu->nguyenLieuTho->code;
                                    break;

                                default:
                                    $label = '';
                            }
                        @endphp

                        <option value="{{ $nguyenlieu->id }}">{{ $label }}</option>
                    @endforeach

                </select>
            </td>
            <td>
                <input type="text" min="0" name="gia_bans[]" class="form-control gia_bans" required>
            </td>
            <td>
                <input type="number" min="1" name="so_luong[]" class="form-control so_luong" value="1"
                       oninput="changeGiaSanPham(this)" required>
            </td>
            <td>
                <input type="text" name="tong_tien[]" class="form-control tong_tien" disabled readonly>
            </td>
            <td>
                <button type="button" onclick="removeItems(this)" class="btn btn-danger btn-sm">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>
        </tbody>
    </table>

    <script>
        async function changeKhachHang() {
            const khachHangId = $('#khach_hang_id').val();
            if (khachHangId !== 0) {
                await selectKhachHang(khachHangId);
            }
        }

        async function selectKhachHang(id) {
            const url = `{{ route('api.khach.hang.detail') }}?id=${id}`;
            $.ajax({
                url: url,
                type: 'GET',
                async: false,
                success: function (data, textStatus) {
                    renderKhachHnag(data.data);
                },
                error: function (request, status, error) {
                    let data = JSON.parse(request.responseText);
                    alert(data.message);
                }
            });
        }

        function renderKhachHnag(data) {
            const tenKhachHang = data.ten;
            const soDienThoai = data.so_dien_thoai;
            const diaChi = data.dia_chi;
            $('#ten_khach_hang').val(tenKhachHang);
            $('#so_dien_thoai').val(soDienThoai);
            $('#dia_chi').val(diaChi);
        }

        async function getListSanPham(loaiSanPham) {
            let url = '';
            switch (loaiSanPham) {
                case 'NGUYEN_LIEU_THO':
                    url = `{{ route('api.nguyen.lieu.tho.list') }}`;
                    break;
                case 'NGUYEN_LIEU_PHAN_LOAI':
                    url = `{{ route('api.nguyen.lieu.phan.loai.list') }}`;
                    break;
                case 'NGUYEN_LIEU_TINH':
                    url = `{{ route('api.nguyen.lieu.tinh.list') }}`;
                    break;
                case 'NGUYEN_LIEU_SAN_XUAT':
                    url = `{{ route('api.nguyen.lieu.san.xuat.list') }}`;
                    break;
                case 'NGUYEN_LIEU_THANH_PHAM':
                    url = `{{ route('api.nguyen.lieu.thanh.pham.list') }}`;
                    break;
            }

            $.ajax({
                url: url,
                type: 'GET',
                async: false,
                success: function (data, textStatus) {
                    renderSanPham(data.data, loaiSanPham);
                },
                error: function (request, status, error) {
                    let data = JSON.parse(request.responseText);
                    alert(data.message);
                }
            });
        }

        function renderSanPham(data, loaiSanPham) {
            let html = '';
            let gia_ = null;
            data.forEach((item) => {
                let ten_;
                switch (loaiSanPham) {
                    case 'NGUYEN_LIEU_THO':
                        ten_ = item.ten_nguyen_lieu + ' - ' + item.ghi_chu ?? '';
                        gia_ = null;
                        break;
                    case 'NGUYEN_LIEU_PHAN_LOAI':
                        ten_ = item.ten_nguyen_lieu_tho + ' - ' + item.ma_don_hang + ' - ' + item.ghi_chu ?? '';
                        if (!gia_) {
                            gia_ = item.gia_sau_phan_loai;
                        }
                        break;
                    case 'NGUYEN_LIEU_TINH':
                        ten_ = item.code;
                        if (!gia_) {
                            gia_ = item.gia_tien;
                        }
                        break;
                    case 'NGUYEN_LIEU_THANH_PHAM':
                        ten_ = item.ten_san_pham + ' - ' + item.so_lo_san_xuat + ' - ' + item.ghi_chu ?? '';
                        if (!gia_) {
                            gia_ = item.price;
                        }
                        break;
                }
                html += `<option value="${item.id}">${ten_}</option>`;
            });

            const listSanPham = $('#listSanPham');
            listSanPham.find('select').empty().append(html);
            listSanPham.find('input.gia_bans').val(gia_);
            listSanPham.find('input.tong_tien').val(gia_);
        }

        function changeGiaSanPham(el) {
            const totalEl = $(el).closest('tr').find('input.tong_tien');
            const gia_ = $(el).closest('tr').find('input.gia_bans').val();
            const so_luong = $(el).closest('tr').find('input.so_luong').val();

            totalEl.val(gia_ * so_luong);
        }

        function changeThongTinSanPham(el) {
            const loaiSanPham = $('#loai_san_pham').val();
            const id = $(el).val();
            layThongTinNguyenLieu(id, el, loaiSanPham);
        }

        function renderChiTietSanPham(data, element, loaiSanPham) {
            let gia_ = null;
            switch (loaiSanPham) {
                case 'NGUYEN_LIEU_THO':
                    gia_ = null;
                    break;
                case 'NGUYEN_LIEU_PHAN_LOAI':
                    gia_ = data.gia_sau_phan_loai;
                    break;
                case 'NGUYEN_LIEU_TINH':
                    gia_ = data.gia_tien;
                    break;
                case 'NGUYEN_LIEU_THANH_PHAM':
                    gia_ = data.price;
                    break;
            }

            $(element).closest('tr').find('input.gia_bans').val(gia_);

            changeGiaSanPham(element);
        }

        function layThongTinNguyenLieu(id, el, loaiSanPham) {
            const url = `{{ route('api.chi.tiet.nguyen.lieu') }}?id=${id}&type=${loaiSanPham}`;

            $.ajax({
                url: url,
                type: 'GET',
                async: false,
                success: function (data, textStatus) {
                    renderChiTietSanPham(data.data, el, loaiSanPham);
                },
                error: function (request, status, error) {
                    let data = JSON.parse(request.responseText);
                    alert(data.message);
                }
            });
        }

        function addItems(el) {
            const tbody = $('#tbodySanPham');
            const tr = $('#listSanPham').clone();
            tbody.append(tr);
        }

        function removeItems(el) {
            $(el).parent().closest('tr').remove();
        }
    </script>
@endsection
