@extends('admin.layouts.master')
@section('title')
    Bán hàng
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Bán hàng</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Bán hàng</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        @if(session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Modal -->
        <div class="modal fade" id="orderHistoryModal" tabindex="-1" aria-labelledby="orderHistoryModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="orderHistoryModalLabel">Danh sách lịch sử bán hàng</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12">
                            <div class="card recent-sales overflow-auto">
                                <div class="card-body">
                                    <h5 class="card-title"><label for="date_">Tìm kiếm </label></h5>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex justify-content-start align-items-center gap-4 w-100">
                                            <div class="col-md-4 form-group">
                                                <div class="d-flex justify-content-start align-items-center gap-2">
                                                    <label for="start_date">Từ ngày: </label>
                                                    <input type="date" class="form-control" id="start_date"
                                                           value="{{ $start_date }}" name="start_date">
                                                </div>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <div class="d-flex justify-content-start align-items-center gap-2">
                                                    <label for="end_date">Đến ngày: </label>
                                                    <input type="date" class="form-control" id="end_date"
                                                           value="{{ $end_date }}" name="end_date">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 d-flex justify-content-end align-items-center">
                                            <button class="btn btn-primary" onclick="searchTable()" type="button">Tìm
                                                kiếm
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex mb-4 mt-3 justify-content-end">
                                <button class="btn btn-sm btn-danger" type="button" onclick="confirmDelete('ban_hang')">
                                    Xoá tất cả
                                </button>
                            </div>
                            <div class="card recent-sales overflow-auto">

                                <div class="card-body">
                                    <table class="table table-hover small min-vw-100">
                                        <colgroup>
                                            <col width="50px">
                                            <col width="100px">
                                            <col width="150px">
                                            <col width="300px">
                                            <col width="200px">
                                            <col width="300px">
                                            <col width="250px">
                                            <col width="250px">
                                            <col width="250px">
                                            <col width="250px">
                                        </colgroup>
                                        <thead>
                                        <tr>
                                            <th scope="col">
                                                <input type="checkbox" name="check_all" id="check_all">
                                            </th>
                                            <th scope="col">Hành động</th>
                                            <th scope="col">Ngày tạo</th>
                                            <th scope="col">Khách hàng</th>
                                            <th scope="col">Số điện thoại</th>
                                            <th scope="col">Địa chỉ</th>
                                            <th scope="col">Tổng tiền</th>
                                            <th scope="col">Đã thanh toán</th>
                                            <th scope="col">Phương thức thanh toán</th>
                                            <th scope="col">Công nợ</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($datas as $data)
                                            <tr>
                                                <th scope="row"><input type="checkbox" name="check_item[]"
                                                                       id="check_item{{ $data->id }}"
                                                                       value="{{ $data->id }}"></th>
                                                <td>
                                                    <div class="d-flex gap-2 justify-content-center">
                                                        <a href="{{ route('admin.ban.hang.detail', $data->id) }}"
                                                           class="btn btn-primary btn-sm">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <form action="{{ route('admin.ban.hang.delete', $data->id) }}"
                                                              method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button"
                                                                    class="btn btn-danger btn-sm btnDelete">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d-m-Y') }}</td>
                                                <td>
                                                    @if($data->ban_le)
                                                        {{ $data->khach_le }}
                                                    @else
                                                        {{ $data->khachHang->ten }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($data->ban_le)
                                                        {{ $data->so_dien_thoai }}
                                                    @else
                                                        {{ $data->khachHang->so_dien_thoai }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($data->ban_le)
                                                        {{ $data->dia_chi }}
                                                    @else
                                                        {{ $data->khachHang->dia_chi }}
                                                    @endif
                                                </td>
                                                <td>{{ parseNumber($data->tong_tien) }} VND</td>
                                                <td>{{ parseNumber($data->da_thanht_toan) }} VND</td>
                                                <td>{{ $data->loaiQuy->ten_loai_quy }}</td>
                                                <td>{{ parseNumber($data->cong_no) }} VND</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            {{ $datas->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Thêm mới bán hàng</h5>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#orderHistoryModal">
                            Xem lịch sử bán hàng
                        </button>
                    </div>
                    <form method="post" action="{{ route('admin.ban.hang.store') }}" class="">
                        @csrf
                        <div class="form-group">
                            <label for="khach_hang_id">Khách hàng</label>
                            <select id="khach_hang_id" name="khach_hang_id" class="form-control selectCustom"
                                    onchange="changeKhachHang()">
                                <option value="0" {{ old('khach_hang_id') == 0 ? 'selected' : '' }}>Khách lẻ</option>
                                @foreach($khachhangs as $khachhang)
                                    <option
                                        value="{{ $khachhang->id }}" {{ old('khach_hang_id') == $khachhang->id ? 'selected' : '' }}>
                                        {{ $khachhang->ten }} : {{ $khachhang->so_dien_thoai }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row" id="formKhachLe">
                            <div class="form-group col-md-12">
                                <label for="ten_khach_hang">Tên khách hàng</label>
                                <input type="text" class="form-control" id="ten_khach_hang" name="ten_khach_hang"
                                       value="{{ old('ten_khach_hang') }}" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="so_dien_thoai">Số điện thoại</label>
                                <input type="text" class="form-control" id="so_dien_thoai" name="so_dien_thoai"
                                       value="{{ old('so_dien_thoai') }}" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="dia_chi">Địa chỉ chi tiết</label>
                                <input type="text" class="form-control" id="dia_chi" name="dia_chi"
                                       value="{{ old('dia_chi') }}" required>
                            </div>
                        </div>

                        <div class="row pt-3 mt-4 border-top">
                            <div class="form-group col-md-6">
                                <label for="da_thanht_toan">Khách hàng đã thanh toán</label>
                                <input type="text" class="form-control onlyNumber" id="da_thanht_toan"
                                       name="da_thanht_toan" value="{{ old('da_thanht_toan') }}" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="loai_quy_id">Loại quỹ</label>
                                <select class="form-control selectCustom" name="loai_quy_id" id="loai_quy_id">
                                    @foreach($loai_quies as $loai_quy)
                                        <option
                                            value="{{ $loai_quy->id }}" {{ old('loai_quy_id') == $loai_quy->id ? 'selected' : '' }}>
                                            {{ $loai_quy->ten_loai_quy }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-3" id="formSanPham">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-group col-md-4 mb-2">
                                    <label for="select_kho">Chọn kho</label>
                                    <select id="select_kho" name="select_kho" class="form-control"
                                            onchange="changeLoaiSanPham()">
                                        <option value="">Lựa chọn kho</option>
                                        <option
                                            value="{{ \App\Enums\LoaiSanPham::NGUYEN_LIEU_THO }}">
                                            Kho Nguyên liệu Thô
                                        </option>
                                        <option
                                            value="{{ \App\Enums\LoaiSanPham::NGUYEN_LIEU_PHAN_LOAI }}">
                                            Kho Nguyên liệu Phân loại
                                        </option>
                                        <option
                                            value="{{ \App\Enums\LoaiSanPham::NGUYEN_LIEU_TINH }}">
                                            Kho Nguyên liệu Tinh
                                        </option>
                                        <option
                                            value="{{ \App\Enums\LoaiSanPham::NGUYEN_LIEU_SAN_XUAT }}">
                                            Kho Thành phẩm sản xuất
                                        </option>
                                        <option
                                            value="{{ \App\Enums\LoaiSanPham::NGUYEN_LIEU_THANH_PHAM }}">
                                            Kho đã Đóng gói
                                        </option>
                                    </select>
                                </div>

                                <button class="btn btn-sm btn-primary d-none showForm" type="button"
                                        onclick="addItems()">
                                    <i class="bi bi-plus"></i> Thêm sản phẩm
                                </button>
                            </div>
                            <table class="table table-bordered d-none showForm">
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

                                </tbody>
                            </table>
                        </div>

                        <input type="hidden" name="loai_san_pham" id="loai_san_pham">
                        <button type="submit" class="btn btn-primary mt-2">Thêm mới</button>
                    </form>

                </div>

            </div>
        </div>

        <table class="d-none">
            <tbody>
            <tr id="listSanPham">
                <td>
                    <select name="san_pham_id[]" class="form-control" onchange="changeThongTinSanPham(this)" required>
                        <option value="">Lựa chọn sản phẩm</option>
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

            async function changeLoaiSanPham() {
                const select_kho = $('#select_kho');
                $('#tbodySanPham').empty();
                const loaiSanPham = select_kho.val();
                $('#loai_san_pham').val(loaiSanPham);
                const showForm = $('.showForm');
                showForm.removeClass('d-none');
                await getListSanPham(loaiSanPham);
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
                            ten_ = item.ten_nguyen_lieu + ' : ' +
                                (parseFloat(item.khoi_luong) - parseFloat(item.khoi_luong_da_phan_loai)) + 'kg';
                            gia_ = item.chi_phi_mua / item.khoi_luong;
                            break;
                        case 'NGUYEN_LIEU_PHAN_LOAI':
                            ten_ = item.ma_don_hang + ' : ' +
                                (parseFloat(item.tong_khoi_luong) - parseFloat(item.khoi_luong_da_phan_loai ?? 0)) + 'kg';
                            if (!gia_) {
                                gia_ = item.gia_sau_phan_loai;
                            }
                            break;
                        case 'NGUYEN_LIEU_TINH':
                            ten_ = item.code + ' : ' + (parseFloat(item.tong_khoi_luong) - parseFloat(item.so_luong_da_dung ?? 0)) + 'kg';
                            if (!gia_) {
                                gia_ = item.gia_tien;
                            }
                            break;
                        case 'NGUYEN_LIEU_SAN_XUAT':
                            ten_ = item.code + ' : ' + (parseFloat(item.khoi_luong) - parseFloat(item.khoi_luong_da_dung ?? 0)) + 'kg';
                            if (!gia_) {
                                gia_ = item.gia_tien;
                            }
                            break;
                        case 'NGUYEN_LIEU_THANH_PHAM':
                            ten_ = item.ten_san_pham + ' : ' + (parseFloat(item.so_luong) - parseFloat(item.so_luong_da_ban ?? 0)) + 'kg';
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
                        gia_ = data.chi_phi_mua / data.khoi_luong;
                        break;
                    case 'NGUYEN_LIEU_PHAN_LOAI':
                        gia_ = data.gia_sau_phan_loai;
                        break;
                    case 'NGUYEN_LIEU_TINH':
                        gia_ = data.gia_tien;
                        break;
                    case 'NGUYEN_LIEU_SAN_XUAT':
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

            $(document).ready(function () {
                addItems();
            })

            function addItems(el) {
                const tbody = $('#tbodySanPham');
                const tr = $('#listSanPham').clone();
                tbody.append(tr);
            }

            function removeItems(el) {
                $(el).parent().closest('tr').remove();
            }
        </script>
    </section>

    <script>
        function searchTable() {
            const start_date = $('#start_date').val();
            const end_date = $('#end_date').val();
            window.location.href = "{{ route('admin.ban.hang.index') }}?start_date=" + start_date + "&end_date=" + end_date;
        }

        $(document).ready(function () {
            let start_date = $('#start_date').val();
            let end_date = $('#end_date').val();
            if (start_date || end_date) {
                const modal = new bootstrap.Modal(document.getElementById('orderHistoryModal'));
                modal.show();
            }
        });
    </script>
@endsection
