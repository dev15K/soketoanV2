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
                    <form method="post" action="{{ route('admin.ban.hang.update', $banhang) }}" class=""
                          id="form_submit_order">
                        @method('PUT')
                        @csrf

                        <div class="row">
                            <div class="col-md-8 col-sm-12">
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
                                                                $label = '';
                                                                $gia = null;

                                                                switch ($banhang->loai_san_pham) {
                                                                    case \App\Enums\LoaiSanPham::NGUYEN_LIEU_THO():
                                                                        $con_lai = ($nguyenlieu->khoi_luong ?? 0) - ($nguyenlieu->khoi_luong_da_phan_loai ?? 0);
                                                                        $label = ($nguyenlieu->ten_nguyen_lieu ?? '') . ' : ' . $con_lai . 'kg';
                                                                        $gia = ($nguyenlieu->khoi_luong ?? 0) > 0 ? ($nguyenlieu->chi_phi_mua / $nguyenlieu->khoi_luong) : null;
                                                                        break;

                                                                    case \App\Enums\LoaiSanPham::NGUYEN_LIEU_PHAN_LOAI():
                                                                        $con_lai = ($nguyenlieu->tong_khoi_luong ?? 0) - ($nguyenlieu->khoi_luong_da_phan_loai ?? 0);
                                                                        $label = ($nguyenlieu->ma_don_hang ?? '') . ' : ' . $con_lai . 'kg';
                                                                        $gia = $nguyenlieu->gia_sau_phan_loai ?? null;
                                                                        break;

                                                                    case \App\Enums\LoaiSanPham::NGUYEN_LIEU_TINH():
                                                                        $con_lai = ($nguyenlieu->tong_khoi_luong ?? 0) - ($nguyenlieu->so_luong_da_dung ?? 0);
                                                                        $label = ($nguyenlieu->code ?? '') . ' : ' . $con_lai . 'kg';
                                                                        $gia = $nguyenlieu->gia_tien ?? null;
                                                                        break;

                                                                    case \App\Enums\LoaiSanPham::NGUYEN_LIEU_SAN_XUAT():
                                                                        $con_lai = ($nguyenlieu->khoi_luong ?? 0) - ($nguyenlieu->khoi_luong_da_dung ?? 0);
                                                                        $label = ($nguyenlieu->code ?? '') . ' : ' . $con_lai . 'kg';
                                                                        $gia = $nguyenlieu->gia_tien ?? null;
                                                                        break;

                                                                    case \App\Enums\LoaiSanPham::NGUYEN_LIEU_THANH_PHAM():
                                                                        $con_lai = ($nguyenlieu->so_luong ?? 0) - ($nguyenlieu->so_luong_da_ban ?? 0);
                                                                        $label = ($nguyenlieu->ten_san_pham ?? '') . ' : ' . $con_lai . 'kg';
                                                                        $gia = $nguyenlieu->price ?? null;
                                                                        break;

                                                                    default:
                                                                        $label = '';
                                                                }
                                                            @endphp

                                                            <option
                                                                {{ $chiTietBanHang->san_pham_id == $nguyenlieu->id ? 'selected' : '' }}
                                                                value="{{ $nguyenlieu->id }}">
                                                                {{ $label }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" min="0" name="gia_bans[]"
                                                           class="form-control gia_bans"
                                                           value="{{ $chiTietBanHang->gia_ban }}" required>
                                                </td>
                                                <td>
                                                    <input type="number" min="1" name="so_luong[]"
                                                           class="form-control so_luong"
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
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="khach_hang_id">Khách hàng</label>
                                    <select id="khach_hang_id" name="khach_hang_id" class="form-control selectCustom"
                                            onchange="changeKhachHang()">
                                        <option value="0" {{ old('khach_hang_id') == 0 ? 'selected' : '' }}>Khách lẻ
                                        </option>
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
                                        <input type="text" class="form-control" id="ten_khach_hang"
                                               name="ten_khach_hang" value="{{ $banhang->khach_le }}" required>
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

                                <div class="pt-3 pb-2 border-top border-bottom mt-3 mb-3">
                                    <table class="table table-bordered">
                                        <colgroup>
                                            <col width="50%">
                                            <col width="50%">
                                        </colgroup>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <label for="tong_tien">Tổng tiền</label>
                                            </td>
                                            <td>
                                                <input type="text"
                                                       class="form-control bg-secondary bg-opacity-10 onlyNumber"
                                                       id="tong_tien" name="tong_tien"
                                                       value="{{ old('tong_tien', $banhang->tong_tien) }}"
                                                       readonly required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="giam_gia">Giảm giá</label>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control onlyNumber" id="giam_gia"
                                                       oninput="calc_total_item()" name="giam_gia"
                                                       value="{{ old('giam_gia', $banhang->giam_gia) }}" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="tong_thanh_toan">Tổng thanh toán</label>
                                            </td>
                                            <td>
                                                <input type="text"
                                                       class="form-control bg-secondary bg-opacity-10 onlyNumber"
                                                       id="tong_thanh_toan" name="tong_thanh_toan"
                                                       value="{{ old('tong_thanh_toan', $banhang->tong_tien - $banhang->giam_gia) }}"
                                                       readonly required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="da_thanht_toan">Khách đưa</label>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control onlyNumber" id="da_thanht_toan"
                                                       name="da_thanht_toan"
                                                       value="{{ old('da_thanht_toan', $banhang->da_thanht_toan) }}"
                                                       oninput="calc_total_item()" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="cong_no">Công nợ</label>
                                            </td>
                                            <td>
                                                <input type="text"
                                                       class="form-control bg-secondary bg-opacity-10 onlyNumber"
                                                       id="cong_no" name="cong_no"
                                                       value="{{ old('cong_no', $banhang->cong_no) }}" readonly
                                                       required>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="loai_quy_id">Loại quỹ</label>
                                    <select class="form-control selectCustom" name="loai_quy_id" id="loai_quy_id">
                                        @foreach($loai_quies as $loai_quy)
                                            <option
                                                {{ $loai_quy->id == $banhang->phuong_thuc_thanh_toan ? 'selected' : '' }}
                                                value="{{ $loai_quy->id }}">{{ $loai_quy->ten_loai_quy }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="note">Ghi chú</label>
                                    <textarea name="note" class="form-control" id="note"
                                              rows="5">{{ old('note', $banhang->note) }}</textarea>
                                </div>
                            </div>
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
                            $label = '';
                            $gia = null;

                            switch ($banhang->loai_san_pham) {
                                case \App\Enums\LoaiSanPham::NGUYEN_LIEU_THO():
                                    $con_lai = ($nguyenlieu->khoi_luong ?? 0) - ($nguyenlieu->khoi_luong_da_phan_loai ?? 0);
                                    $label = ($nguyenlieu->ten_nguyen_lieu ?? '') . ' : ' . $con_lai . 'kg';
                                    $gia = ($nguyenlieu->khoi_luong ?? 0) > 0 ? ($nguyenlieu->chi_phi_mua / $nguyenlieu->khoi_luong) : null;
                                    break;

                                case \App\Enums\LoaiSanPham::NGUYEN_LIEU_PHAN_LOAI():
                                    $con_lai = ($nguyenlieu->tong_khoi_luong ?? 0) - ($nguyenlieu->khoi_luong_da_phan_loai ?? 0);
                                    $label = ($nguyenlieu->ma_don_hang ?? '') . ' : ' . $con_lai . 'kg';
                                    $gia = $nguyenlieu->gia_sau_phan_loai ?? null;
                                    break;

                                case \App\Enums\LoaiSanPham::NGUYEN_LIEU_TINH():
                                    $con_lai = ($nguyenlieu->tong_khoi_luong ?? 0) - ($nguyenlieu->so_luong_da_dung ?? 0);
                                    $label = ($nguyenlieu->code ?? '') . ' : ' . $con_lai . 'kg';
                                    $gia = $nguyenlieu->gia_tien ?? null;
                                    break;

                                case \App\Enums\LoaiSanPham::NGUYEN_LIEU_SAN_XUAT():
                                    $con_lai = ($nguyenlieu->khoi_luong ?? 0) - ($nguyenlieu->khoi_luong_da_dung ?? 0);
                                    $label = ($nguyenlieu->code ?? '') . ' : ' . $con_lai . 'kg';
                                    $gia = $nguyenlieu->gia_tien ?? null;
                                    break;

                                case \App\Enums\LoaiSanPham::NGUYEN_LIEU_THANH_PHAM():
                                    $con_lai = ($nguyenlieu->so_luong ?? 0) - ($nguyenlieu->so_luong_da_ban ?? 0);
                                    $label = ($nguyenlieu->ten_san_pham ?? '') . ' : ' . $con_lai . 'kg';
                                    $gia = $nguyenlieu->price ?? null;
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

            calc_total_item();
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
            calc_total_item();
        }

        function removeItems(el) {
            $(el).parent().closest('tr').remove();
            calc_total_item();
        }
    </script>

    <script>
        function calc_total_item() {
            let total = 0;

            $('#form_submit_order input[name="tong_tien[]"]').each(function () {
                total += parseFloat(this.value) || 0;
            });

            $('#tong_tien').val(total);

            let giam_gia = $('#giam_gia').val() || 0;
            let da_thanht_toan = $('#da_thanht_toan').val() || 0;

            let tong_thanh_toan = total - giam_gia;
            let cong_no = tong_thanh_toan - da_thanht_toan;

            $('#tong_thanh_toan').val(tong_thanh_toan);
            $('#cong_no').val(cong_no);
        }
    </script>
@endsection
