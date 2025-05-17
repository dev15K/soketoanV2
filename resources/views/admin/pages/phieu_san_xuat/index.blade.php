@extends('admin.layouts.master')
@section('title')
    Phiếu sản xuất
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Phiếu sản xuất</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active"> Phiếu sản xuất</li>
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
        <div class="col-12">
            <div class="card recent-sales overflow-auto">
                <div class="card-body">
                    <h5 class="card-title"><label for="inlineFormInputGroup">Tìm kiếm theo tên phiếu sản
                            xuất</label>
                    </h5>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-start align-items-center gap-4 w-100">
                            <div class="col-md-4 form-group">
                                <div class="d-flex justify-content-start align-items-center gap-2">
                                    <label for="ngay">Ngày: </label>
                                    <input type="date" class="form-control" id="ngay" name="ngay">
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="inlineFormInputGroup"
                                           placeholder="Tìm kiếm theo tên phiếu sản xuất">
                                    <div class="input-group-prepend">
                                        <button type="button" class="input-group-text">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex justify-content-end align-items-center">
                            <button class="btn btn-primary" type="button">Tìm kiếm</button>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Thêm mới Phiếu sản xuất</h5>
                        <button class="btn btn-sm btn-primary btnShowOrHide" type="button">Mở rộng</button>
                    </div>
                    <form method="post" action="{{ route('admin.phieu.san.xuat.store') }}" class="d-none">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="code">Mã Phiếu</label>
                                <input type="text" class="form-control bg-secondary bg-opacity-10" id="code" name="code"
                                       value="{{ $code }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="so_lo_san_xuat">Số LÔ SX</label>
                                <input type="text" class="form-control bg-secondary bg-opacity-10" id="so_lo_san_xuat"
                                       name="so_lo_san_xuat" value="{{ $so_lo_san_xuat }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="nguyen_lieu_id">Mã lô hàng</label>
                                <select id="nguyen_lieu_id" name="nguyen_lieu_id" class="form-control">
                                    @foreach($nltinhs as $nltinh)
                                        <option value="{{ $nltinh->id }}">{{ $nltinh->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="tong_khoi_luong">Khối lượng</label>
                                <input type="text" class="form-control onlyNumber" id="tong_khoi_luong"
                                       name="tong_khoi_luong" value="" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="ngay">Ngày</label>
                                <input type="date" class="form-control" id="ngay" name="ngay" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="trang_thai">Trạng thái</label>
                                <select id="trang_thai" name="trang_thai" class="form-control">
                                    <option
                                            value="{{ \App\Enums\TrangThaiNguyenLieuTho::ACTIVE() }}">{{ \App\Enums\TrangThaiNguyenLieuTho::ACTIVE() }}</option>
                                    <option
                                            value="{{ \App\Enums\TrangThaiNguyenLieuTho::INACTIVE() }}">{{ \App\Enums\TrangThaiNguyenLieuTho::INACTIVE() }}</option>
                                </select>
                            </div>
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

        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">

                    <table class="table table-hover">
                        <colgroup>
                            <col width="5%">
                            <col width="12%">
                            <col width="12%">
                            <col width="12%">
                            <col width="12%">
                            <col width="x">
                            <col width="10%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Ngày</th>
                            <th scope="col">Mã phiếu</th>
                            <th scope="col">Số LÔ SX</th>
                            <th scope="col">Mã lô hàng</th>
                            <th scope="col">Tổng khối lượng</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($datas as $data)
                            <tr>
                                <th scope="row">{{ $loop->index + 1 }}</th>
                                <td>{{ \Carbon\Carbon::parse($data->ngay)->format('d/m/Y') }}</td>
                                <td>{{ $data->code }}</td>
                                <td>{{ $data->so_lo_san_xuat }}</td>
                                <td>{{ $data->nguyen_lieu_id ? $data->nguyenLieuTinh->code : ''}}</td>
                                <td>{{ number_format($data->tong_khoi_luong) }} kg</td>
                                <td>{{ $data->trang_thai }}</td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('admin.phieu.san.xuat.detail', $data->id) }}"
                                           class="btn btn-primary btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('admin.phieu.san.xuat.delete', $data->id) }}"
                                              method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{ $datas->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </section>
@endsection
