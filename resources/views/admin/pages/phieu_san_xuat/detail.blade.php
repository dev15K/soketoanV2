@extends('admin.layouts.master')
@section('title')
    Chỉnh sửa Phiếu sản xuất
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Chỉnh sửa Phiếu sản xuất</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa Phiếu sản xuất</li>
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
                    <h5 class="card-title">Chỉnh sửa Phiếu sản xuất</h5>
                    <form method="post" action="{{ route('admin.phieu.san.xuat.update', $phieu_san_xuat) }}">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="ten_phieu">Tên phiếu</label>
                            <input type="text" class="form-control" id="ten_phieu" name="ten_phieu"
                                   value="{{ $phieu_san_xuat->ten_phieu }}" required>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="ngay">Ngày</label>
                                <input type="date" class="form-control" id="ngay" name="ngay"
                                       value="{{ \Illuminate\Support\Carbon::parse($phieu_san_xuat->ngay)->format('Y-m-d') }}"
                                       required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="trang_thai">Trạng thái</label>
                                <select id="trang_thai" name="trang_thai" class="form-control">
                                    <option
                                        {{ $phieu_san_xuat->trang_thai == \App\Enums\TrangThaiNguyenLieuTho::ACTIVE() ? 'selected' : '' }}
                                        value="{{ \App\Enums\TrangThaiNguyenLieuTho::ACTIVE() }}">{{ \App\Enums\TrangThaiNguyenLieuTho::ACTIVE() }}</option>
                                    <option
                                        {{ $phieu_san_xuat->trang_thai == \App\Enums\TrangThaiNguyenLieuTho::INACTIVE() ? 'selected' : '' }}
                                        value="{{ \App\Enums\TrangThaiNguyenLieuTho::INACTIVE() }}">{{ \App\Enums\TrangThaiNguyenLieuTho::INACTIVE() }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-2">
                            <div class="w-100 d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Danh sách nguyên liệu</h4>

                                <button type="button" class="btn btn-success btn-sm" onclick="plusItem()">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                            <table class="table table-bordered">
                                <colgroup>
                                    <col width="10%">
                                    <col width="35%">
                                    <col width="35%">
                                    <col width="15%">
                                    <col width="x">
                                </colgroup>
                                <thead>
                                <tr class="text-center">
                                    <th scope="col">Loại nguyên liệu</th>
                                    <th scope="col">Nguyên liệu</th>
                                    <th scope="col">Thành phần</th>
                                    <th scope="col">Khối lượng</th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody id="tbodyListNL" class="text-center">
                                @foreach($dsNLSXChiTiets as $dsNLSXChiTiet)
                                    <tr>
                                        <td>
                                            <select class="form-control" name="loai_nguyen_lieu_ids[]"
                                                    onchange="changeLoaiNguyenLieu(this)">
                                                <option
                                                    {{ $dsNLSXChiTiet->type == 'nguyen_lieu_tinh' ? 'selected' : '' }} value="nguyen_lieu_tinh">
                                                    Nguyên liệu Tinh
                                                </option>
                                                <option
                                                    {{ $dsNLSXChiTiet->type == 'nguyen_lieu_phan_loai' ? 'selected' : '' }} value="nguyen_lieu_phan_loai">
                                                    Nguyên liệu Phân loại
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control" name="nguyen_lieu_ids[]">
                                               @if($dsNLSXChiTiet->type == 'nguyen_lieu_tinh')
                                                    @foreach($nltinhs as $nltinh)
                                                        <option
                                                            {{ $nltinh->id == $dsNLSXChiTiet->nguyen_lieu_id ? 'selected' : '' }}
                                                            value="{{ $nltinh->id }}">{{ $nltinh->ten_nguyen_lieu }}</option>
                                                    @endforeach
                                               @else
                                                    @foreach($nlphanloais as $nlphanloai)
                                                        <option
                                                            {{ $nlphanloai->id == $dsNLSXChiTiet->nguyen_lieu_id ? 'selected' : '' }}
                                                            value="{{ $nlphanloai->id }}">{{ $nlphanloai->ten_nguyen_lieu }}</option>
                                                    @endforeach
                                               @endif
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="ten_nguyen_lieus[]" class="form-control"
                                                   value="{{ $dsNLSXChiTiet->ten_nguyen_lieu }}" required>
                                        </td>
                                        <td>
                                            <input type="number" min="0" name="khoi_luongs[]"
                                                   value="{{ $dsNLSXChiTiet->khoi_luong }}" class="form-control"
                                                   required>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm disabled">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Thêm mới</button>
                    </form>
                </div>

            </div>
        </div>

        <script>
            const baseHtml = `<tr>
                                    <td>
                                        <select class="form-control" name="loai_nguyen_lieu_ids[]"
                                                onchange="changeLoaiNguyenLieu(this)">
                                            <option value="nguyen_lieu_tinh">Nguyên liệu Tinh</option>
                                            <option value="nguyen_lieu_phan_loai">Nguyên liệu Phân loại</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control" name="nguyen_lieu_ids[]">
                                            @foreach($nltinhs as $nltinh)
            <option value="{{ $nltinh->id }}">{{ $nltinh->ten_nguyen_lieu }}</option>
                                            @endforeach
            </select>
        </td>
        <td>
            <input type="text" name="ten_nguyen_lieus[]" class="form-control" required>
        </td>
        <td>
            <input type="number" min="0" name="khoi_luongs[]" class="form-control" required>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm disabled">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    </tr>`;

            async function changeLoaiNguyenLieu(el) {
                const value = $(el).val();
                if (value === 'nguyen_lieu_tinh') {
                    await listNguyenLieuTinh(el);
                } else {
                    await listNguyenLieuPhanLoai(el);
                }
            }

            async function listNguyenLieuTinh(el) {
                let url = `{{ route('api.nguyen.lieu.tinh.list') }}`;

                $.ajax({
                    url: url,
                    type: 'GET',
                    async: false,
                    success: function (data, textStatus) {
                        renderData(el, data.data);
                    },
                    error: function (request, status, error) {
                        let data = JSON.parse(request.responseText);
                        alert(data.message);
                    }
                });
            }

            async function listNguyenLieuPhanLoai(el) {
                let url = `{{ route('api.nguyen.lieu.phan.loai.list') }}`;

                $.ajax({
                    url: url,
                    type: 'GET',
                    async: false,
                    success: function (data, textStatus) {
                        renderData(el, data.data);
                    },
                    error: function (request, status, error) {
                        let data = JSON.parse(request.responseText);
                        alert(data.message);
                    }
                });
            }

            function renderData(el, data) {
                let html = '';
                data.forEach((item) => {
                    html += `<option value="${item.id}">${item.ten_nguyen_lieu}</option>`;
                });
                $(el).parent().next().find('select').html(html);
            }

            function plusItem() {
                $('#tbodyListNL').append(baseHtml);
            }

            function removeItems(el) {
                $(el).parent().closest('tr').remove();
            }
        </script>
    </section>
@endsection
