@php use App\Enums\TrangThaiNguyenLieuSanXuat;use Carbon\Carbon; @endphp
@extends('admin.layouts.master')
@section('title')
    Kho Thành phẩm sản xuất
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Kho Thành phẩm sản xuất</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active"> Kho Thành phẩm sản xuất</li>
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
                            <div class="col-md-3 form-group">
                                <div class="d-flex justify-content-start align-items-center gap-2">
                                    <label for="start_date">Từ ngày: </label>
                                    <input type="date" class="form-control" id="start_date"
                                           value="{{ $start_date }}" name="start_date">
                                </div>
                            </div>
                            <div class="col-md-3 form-group">
                                <div class="d-flex justify-content-start align-items-center gap-2">
                                    <label for="end_date">Đến ngày: </label>
                                    <input type="date" class="form-control" id="end_date"
                                           value="{{ $end_date }}" name="end_date">
                                </div>
                            </div>
                            <div class="col-md-3 form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="keyword" name="keyword"
                                           placeholder="Tên nguyên liệu" value="{{ $keyword }}">

                                </div>
                            </div>
                            <div class="col-md-3 form-group">
                                <div class="form-group">
                                    <select id="phieu_san_xuat_id" name="phieu_san_xuat_id"
                                            class="form-control selectCustom">
                                        <option value="">Lựa chọn</option>
                                        @foreach($phieu_san_xuats as $phieu_san_xuat)
                                            <option {{ $phieu_san_xuat->id == $phieu_san_xuat_id ? 'selected' : '' }}
                                                    value="{{ $phieu_san_xuat->id }}">{{ $phieu_san_xuat->so_lo_san_xuat }}
                                                - {{ parseNumber($phieu_san_xuat->tong_khoi_luong - $phieu_san_xuat->khoi_luong_da_dung) }}
                                                kg
                                            </option>
                                        @endforeach
                                    </select>
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
                const start_date = $('#start_date').val();
                const end_date = $('#end_date').val();
                const keyword = $('#keyword').val();
                const so_lo_san_xuat = $('#phieu_san_xuat_id').val();
                window.location.href = "{{ route('admin.nguyen.lieu.san.xuat.index') }}?start_date=" + start_date + "&end_date=" + end_date + "&keyword=" + keyword + "&phieu_san_xuat_id=" + so_lo_san_xuat;
            }
        </script>

        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Thêm mới Kho Thành phẩm sản xuất</h5>
                        <button class="btn btn-sm btn-primary btnShowOrHide" type="button">Mở rộng</button>
                    </div>
                    <form method="post" action="{{ route('admin.nguyen.lieu.san.xuat.store') }}" class="">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="nhan_vien_san_xuat">Nhân viên SX</label>
                                <select id="nhan_vien_san_xuat" name="nhan_vien_san_xuat"
                                        class="form-control selectCustom">
                                    @foreach($nsus as $nsu)
                                        <option value="{{ $nsu->id }}"
                                            {{ old('nhan_vien_san_xuat') == $nsu->id ? 'selected' : '' }}>
                                            {{ $nsu->full_name }}/{{ $nsu->email }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="phieu_san_xuat_id">Lô Sản Xuất</label>
                                <select id="phieu_san_xuat_id" name="phieu_san_xuat_id"
                                        class="form-control selectCustom">
                                    @foreach($phieu_san_xuats as $phieu_san_xuat)
                                        <option value="{{ $phieu_san_xuat->id }}"
                                            {{ old('phieu_san_xuat_id') == $phieu_san_xuat->id ? 'selected' : '' }}>
                                            {{ $phieu_san_xuat->so_lo_san_xuat }} :
                                            {{ parseNumber($phieu_san_xuat->tong_khoi_luong - $phieu_san_xuat->khoi_luong_da_dung) }}
                                            kg
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="w-100 d-flex mt-3 justify-content-end">
                            <button class="btn btn-primary btn-sm" type="button" onclick="init_form_product();">
                                Thêm
                            </button>
                        </div>

                        <div class="table-responsive pt-3">
                            <table class="table table-hover table-bordered table-sm">
                                <colgroup>
                                    <col width="50px">
                                    <col width="8%">
                                    <col width="x">
                                    <col width="14%">
                                    <col width="14%">
                                    <col width="9%">
                                    <col width="9%">
                                    <col width="9%">
                                    <col width="9%">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Ngày</th>
                                    <th scope="col">Tên nguyên liệu</th>
                                    <th scope="col">Khối lượng(kg)</th>
                                    <th scope="col">Tổng tiền lô SX</th>
                                    <th scope="col">Màu sắc</th>
                                    <th scope="col">Mùi thơm</th>
                                    <th scope="col">Bảo quản</th>
                                    <th scope="col">Chi tiết khác</th>
                                </tr>
                                </thead>
                                <tbody id="tbodyFormCreate">

                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Thêm mới</button>
                    </form>

                </div>

            </div>
        </div>

        <script>
            $(document).ready(function () {
                init_form_product();
            })

            function remove_items(elm) {
                $(elm).parent().closest('tr').remove();
            }

            function init_form_product() {
                let html = `<tr>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="remove_items(this)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <input type="date" class="form-control" name="ngay[]"
                                               value="{{ Carbon::now()->format('Y-m-d') }}" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control"
                                               name="ten_nguyen_lieu[]" value="" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control onlyNumber"
                                               name="khoi_luong[]" value="" required>

                                        <input type="hidden" name="gia_lo_san_xuat[]"
                                               value="">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control onlyNumber"
                                               name="tong_tien[]" value="">
                                    </td>
                                    <td>
                                        <textarea name="mau_sac[]" class="form-control"
                                                  rows="5"></textarea>
                                    </td>
                                    <td>
                                        <textarea name="mui_thom[]" class="form-control"
                                                  rows="5"></textarea>
                                    </td>
                                    <td>
                                        <textarea name="bao_quan[]" class="form-control"
                                                  rows="5"></textarea>
                                    </td>
                                    <td>
                                        <textarea name="chi_tiet_khac[]" class="form-control"
                                                  rows="5"></textarea>
                                    </td>
                                </tr>`;

                $('#tbodyFormCreate').append(html);
            }
        </script>

        <div class="col-12">
            <div class="d-flex mb-4 mt-3 justify-content-end">
                <button class="btn btn-sm btn-danger" type="button" onclick="confirmDelete('thanh_pham')">Xoá
                    tất cả
                </button>
            </div>
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <div class="table-responsive pt-3">
                        <table class="table datatable_wrapper table-hover " style="min-width: 2000px">
                            <colgroup>
                                <col width="50px">
                                <col width="100px">
                                <col width="6%">
                                <col width="8%">
                                <col width="x">
                                <col width="8%">
                                <col width="8%">
                                <col width="8%">
                                <col width="8%">
                                <col width="8%">
                                <col width="8%">
                                <col width="6%">
                                <col width="6%">
                                <col width="6%">
                                <col width="6%">
                                <col width="6%">
                            </colgroup>
                            <thead>
                            <tr>
                                <th scope="col">
                                    <input type="checkbox" name="check_all" id="check_all">
                                </th>
                                <th scope="col">Hành động</th>
                                <th scope="col">Ngày</th>
                                <th scope="col">Lô Sản Xuất</th>
                                <th scope="col">Tên nguyên liệu</th>
                                <th scope="col">Khối lượng(kg)</th>
                                <th scope="col">Khối lượng đã dùng</th>
                                <th scope="col">Khối lượng tồn</th>
                                <th scope="col">Đơn giá</th>
                                <th scope="col">Tổng tiền lô SX</th>
                                <th scope="col">Giá trị tồn kho</th>
                                <th scope="col">Màu sắc</th>
                                <th scope="col">Mùi thơm</th>
                                <th scope="col">Chi tiết khác</th>
                                <th scope="col">Bảo quản</th>
                                <th scope="col">Nhân viên SX</th>
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
                                            <a href="{{ route('admin.nguyen.lieu.san.xuat.detail', $data->id) }}"
                                               class="btn btn-primary btn-sm">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            @if($data->khoi_luong_da_dung > 0)
                                                <button type="button" class="btn btn-danger btn-sm" disabled>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @else
                                                <form
                                                    action="{{ route('admin.nguyen.lieu.san.xuat.delete', $data->id) }}"
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
                                    <td>{{ Carbon::parse($data->ngay)->format('d-m-Y') }}</td>
                                    <td>{{ $data->PhieuSanXuat->so_lo_san_xuat }}</td>
                                    <td>{{ $data->ten_nguyen_lieu }}</td>
                                    <td>{{ parseNumber($data->khoi_luong, 0) }} kg</td>
                                    <td>{{ parseNumber($data->khoi_luong_da_dung, 0) }} kg</td>
                                    <td>{{ parseNumber($data->khoi_luong - $data->khoi_luong_da_dung, 0) }} kg</td>
                                    <td>{{ parseNumber($data->don_gia, 0) }} VND</td>
                                    <td>{{ parseNumber($data->tong_tien, 0) }} VND</td>
                                    <td>
                                        {{ parseNumber($data->don_gia * ($data->khoi_luong - $data->khoi_luong_da_dung), 0) }}
                                        VND
                                    </td>
                                    <td>{{ $data->mau_sac }}</td>
                                    <td>{{ $data->mui_thom }}</td>
                                    <td>{{ $data->chi_tiet_khac }}</td>
                                    <td>{{ $data->bao_quan }}</td>
                                    <td>{{ $data->NhanVien->full_name }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot class="bg-primary bg-opacity-10">
                            <tr>
                                <th scope="col">Tổng:</th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col">{{ parseNumber($datas->sum('khoi_luong'), 0) }} kg</th>
                                <th scope="col">{{ parseNumber($datas->sum('khoi_luong_da_dung'), 0) }} kg</th>
                                <th scope="col">{{ parseNumber($datas->sum('khoi_luong') - $datas->sum('khoi_luong_da_dung'), 0) }}
                                    kg
                                </th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
