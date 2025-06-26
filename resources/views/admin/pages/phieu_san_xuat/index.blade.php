@php use App\Enums\TrangThaiPhieuSanXuat;use Carbon\Carbon; @endphp
@php @endphp
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
                    <h5 class="card-title"><label for="inlineFormInputGroup">Tìm kiếm</label>
                    </h5>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-start align-items-center gap-4 w-100">
                            <div class="col-md-4 form-group">
                                <div class="d-flex justify-content-start align-items-center gap-2">
                                    <label for="ngay">Ngày: </label>
                                    <input type="date" class="form-control" id="ngay_search"
                                           value="{{ $ngay }}" name="ngay">
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="keyword" name="keyword"
                                           placeholder="Số LÔ SX, Mã Phiếu" value="{{ $keyword }}">

                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex justify-content-end align-items-center">
                            <button class="btn btn-primary" onclick="searchTable()" type="button">Tìm kiếm</button>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <script>
            function searchTable() {
                const ngay_search = $('#ngay_search').val();
                const keyword = $('#keyword').val();
                window.location.href = "{{ route('admin.phieu.san.xuat.index') }}?ngay=" + ngay_search + "&keyword=" + keyword;
            }
        </script>

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
                            <div class="form-group col-md-4">
                                <label for="code">Mã Phiếu</label>
                                <input type="text" class="form-control bg-secondary bg-opacity-10" id="code" name="code"
                                       value="{{ old('code', $code) }}" readonly required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="ten_phieu">Tên nguyên liệu</label>
                                <input type="text" class="form-control" id="ten_phieu"
                                       name="ten_phieu" value="{{ old('ten_phieu') }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="so_lo_san_xuat">Số LÔ SX</label>
                                <input type="text" class="form-control bg-secondary bg-opacity-10" id="so_lo_san_xuat"
                                       name="so_lo_san_xuat" value="{{ old('so_lo_san_xuat', $so_lo_san_xuat) }}"
                                       readonly required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="tong_khoi_luong">KL</label>
                                <input type="text" class="form-control onlyNumber bg-secondary bg-opacity-10"
                                       id="tong_khoi_luong" name="tong_khoi_luong"
                                       value="{{ old('tong_khoi_luong', 0) }}" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="nhan_su_xu_li">Nhân sự xử lý</label>
                                <select id="nhan_su_xu_li" name="nhan_su_xu_li" class="form-control selectCustom">
                                    @foreach($nsus as $nsu)
                                        <option value="{{ $nsu->id }}"
                                            {{ old('nhan_su_xu_li') == $nsu->id ? 'selected' : '' }}>
                                            {{ $nsu->full_name }}/{{ $nsu->email }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="ngay">Ngày</label>
                                <input type="date" class="form-control" id="ngay" name="ngay"
                                       value="{{ old('ngay', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="trang_thai">Trạng thái</label>
                                <select id="trang_thai" name="trang_thai" class="form-control">
                                    <option value="{{ \App\Enums\TrangThaiPhieuSanXuat::ACTIVE() }}"
                                        {{ old('trang_thai') == \App\Enums\TrangThaiPhieuSanXuat::ACTIVE() ? 'selected' : '' }}>
                                        {{ \App\Enums\TrangThaiPhieuSanXuat::ACTIVE() }}
                                    </option>
                                    <option value="{{ \App\Enums\TrangThaiPhieuSanXuat::INACTIVE() }}"
                                        {{ old('trang_thai') == \App\Enums\TrangThaiPhieuSanXuat::INACTIVE() ? 'selected' : '' }}>
                                        {{ \App\Enums\TrangThaiPhieuSanXuat::INACTIVE() }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <label for="thoi_gian_hoan_thanh_san_xuat">Thời gian dự kiến hoàn thành SX</label>
                            <input type="date" class="form-control" id="thoi_gian_hoan_thanh_san_xuat"
                                   name="thoi_gian_hoan_thanh_san_xuat"
                                   value="{{ old('thoi_gian_hoan_thanh_san_xuat', \Carbon\Carbon::now()->format('Y-m-d')) }}"
                                   required>
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
                                    <col width="40%">
                                    <col width="40%">
                                    <col width="15%">
                                    <col width="x">
                                </colgroup>
                                <thead>
                                <tr class="text-center">
                                    <th scope="col">THÀNH PHẦN TRỘN TỪ MÃ LÔ HÀNG</th>
                                    <th scope="col">Tên NVL</th>
                                    <th scope="col">TỔNG KL(kg)</th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody id="tbodyListNL" class="text-center">
                                <tr>
                                    <td>
                                        <select class="form-control selectCustom"
                                                name="nguyen_lieu_ids[]">
                                            @foreach($nltinhs as $nltinh)
                                                <option value="{{ $nltinh->id }}">
                                                    {{ $nltinh->code }} - {{ $nltinh->ten_nguyen_lieu }}
                                                    - {{ $nltinh->tong_khoi_luong - $nltinh->so_luong_da_dung }} kg
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="ten_nguyen_lieus[]" class="form-control" required>
                                    </td>
                                    <td>
                                        <input type="text" name="khoi_luongs[]" class="form-control onlyNumber"
                                               required>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm disabled">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Thêm mới</button>
                    </form>

                </div>

            </div>
        </div>

        <script>
            const baseHtml = `<tr><td>
                                        <select class="form-control selectCustom"
                                                name="nguyen_lieu_ids[]">
                                            @foreach($nltinhs as $nltinh)
            <option value="{{ $nltinh->id }}">{{ $nltinh->code }} - {{ $nltinh->ten_nguyen_lieu }}
            - {{ $nltinh->tong_khoi_luong - $nltinh->so_luong_da_dung }} kg
            </option>
@endforeach
            </select>
        </td>
        <td>
            <input type="text" name="ten_nguyen_lieus[]" class="form-control" required>
        </td>
        <td>
            <input type="text" min="0" name="khoi_luongs[]" class="form-control" required>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeItems(this)">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    </tr>`;

            function plusItem() {
                $('#tbodyListNL').append(baseHtml);
                appendSelect2();
            }

            function appendSelect2() {
                $('.selectCustom').select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Lựa chọn...',
                    allowClear: true,
                    width: '100%',
                    minimumResultsForSearch: 0
                });
            }

            function removeItems(el) {
                $(el).parent().closest('tr').remove();
            }
        </script>

        <div class="col-12">
            <div class="d-flex mb-4 mt-3 justify-content-end">
                <button class="btn btn-sm btn-danger" type="button" onclick="confirmDelete('phieu_san_xuat')">
                    Xoá tất cả
                </button>
            </div>
            <div class="card recent-sales overflow-auto">
                <div class="card-body">
                    <table class="table table-hover table-sm">
                        <colgroup>
                            <col width="5%">
                            <col width="60px">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                            <col width="x">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th scope="col">
                                <input type="checkbox" name="check_all" id="check_all">
                            </th>
                            <th scope="col">Hành động</th>
                            <th scope="col">Ngày</th>
                            <th scope="col">Mã phiếu</th>
                            <th scope="col">Tên nguyên liệu</th>
                            <th scope="col">Số LÔ SX</th>
                            <th scope="col">Tổng KL(kg)</th>
                            <th scope="col">KL đã dùng(kg)</th>
                            <th scope="col">KL tồn(kg)</th>
                            <th scope="col">Nhân sự xử lý</th>
                            <th scope="col">Thời gian dự kiến hoàn thành SX</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($datas as $data)
                            <tr>
                                <th scope="row">
                                    @if($data->khoi_luong_da_dung > 0)
                                        <input type="checkbox" disabled>
                                    @else
                                        <input type="checkbox" name="check_item[]"
                                               id="check_item{{ $data->id }}"
                                               value="{{ $data->id }}">
                                    @endif
                                </th>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('admin.phieu.san.xuat.detail', $data->id) }}"
                                           class="btn btn-primary btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        @if($data->khoi_luong_da_dung > 0)
                                            <button type="button" class="btn btn-danger btn-sm" disabled>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @else
                                            <form action="{{ route('admin.phieu.san.xuat.delete', $data->id) }}"
                                                  method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm btnDelete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ Carbon::parse($data->ngay)->format('d/m/Y') }}</td>
                                <td>{{ $data->code }}</td>
                                <td>{{ $data->ten_phieu }}</td>
                                <td>{{ $data->so_lo_san_xuat }}</td>
                                <td>{{ parseNumber($data->tong_khoi_luong) }} kg</td>
                                <td>{{ parseNumber($data->khoi_luong_da_dung) }} kg</td>
                                <td>{{ parseNumber($data->tong_khoi_luong - $data->khoi_luong_da_dung) }} kg</td>
                                <td>{{ $data->nhan_su_xu_li?->full_name }}</td>
                                <td>{{ Carbon::parse($data->thoi_gian_hoan_thanh_san_xuat)->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>

            </div>
            {{ $datas->links('pagination::bootstrap-5') }}
        </div>
    </section>
@endsection
