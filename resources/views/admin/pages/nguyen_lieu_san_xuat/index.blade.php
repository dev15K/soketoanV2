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
                            <div class="col-md-4 form-group">
                                <div class="d-flex justify-content-start align-items-center gap-2">
                                    <label for="ngay">Ngày: </label>
                                    <input type="date" class="form-control" id="ngay_search"
                                           value="{{ $ngay_search }}" name="ngay">
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="keyword" name="keyword"
                                           placeholder="Tên nguyên liệu" value="{{ $keyword }}">

                                </div>
                            </div>
                            <div class="col-md-4 form-group">
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
                const ngay_search = $('#ngay_search').val();
                const keyword = $('#keyword').val();
                const so_lo_san_xuat = $('#phieu_san_xuat_id').val();
                window.location.href = "{{ route('admin.nguyen.lieu.san.xuat.index') }}?ngay=" + ngay_search + "&keyword=" + keyword + "&phieu_san_xuat_id=" + so_lo_san_xuat;
            }
        </script>

        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Thêm mới Kho Thành phẩm sản xuất</h5>
                        <button class="btn btn-sm btn-primary btnShowOrHide" type="button">Mở rộng</button>
                    </div>
                    <form method="post" action="{{ route('admin.nguyen.lieu.san.xuat.store') }}" class="d-none">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="ngay">Ngày</label>
                                <input type="date" class="form-control" id="ngay" name="ngay"
                                       value="{{ old('ngay', Carbon::now()->format('Y-m-d')) }}" required>
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

                        <div class="form-group">
                            <label for="ten_nguyen_lieu">Tên nguyên liệu</label>
                            <input type="text" class="form-control" id="ten_nguyen_lieu" name="ten_nguyen_lieu"
                                   value="{{ old('ten_nguyen_lieu') }}" required>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="khoi_luong">Khối lượng(kg)</label>
                                <input type="text" class="form-control onlyNumber" id="khoi_luong" name="khoi_luong"
                                       value="{{ old('khoi_luong') }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="don_gia">Đơn giá (Bỏ trống nếu tự tính)</label>
                                <input type="text" class="form-control onlyNumber" id="don_gia" name="don_gia"
                                       value="{{ old('don_gia') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="mau_sac">Màu sắc</label>
                                <input type="text" class="form-control" id="mau_sac" name="mau_sac"
                                       value="{{ old('mau_sac') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="mui_thom">Mùi thơm</label>
                                <input type="text" class="form-control" id="mui_thom" name="mui_thom"
                                       value="{{ old('mui_thom') }}">
                            </div>

                            <div class="form-group d-none">
                                <label for="trang_thai">Trạng thái</label>
                                <select id="trang_thai" name="trang_thai" class="form-control">
                                    <option value="{{ TrangThaiNguyenLieuSanXuat::ACTIVE() }}"
                                        {{ old('trang_thai') == TrangThaiNguyenLieuSanXuat::ACTIVE() ? 'selected' : '' }}>
                                        {{ TrangThaiNguyenLieuSanXuat::ACTIVE() }}
                                    </option>
                                    <option value="{{ TrangThaiNguyenLieuSanXuat::INACTIVE() }}"
                                        {{ old('trang_thai') == TrangThaiNguyenLieuSanXuat::INACTIVE() ? 'selected' : '' }}>
                                        {{ TrangThaiNguyenLieuSanXuat::INACTIVE() }}
                                    </option>
                                </select>
                            </div>

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
                        </div>

                        <div class="form-group">
                            <label for="bao_quan">Bảo quản</label>
                            <input type="text" class="form-control" id="bao_quan" name="bao_quan"
                                   value="{{ old('bao_quan') }}">
                        </div>

                        <div class="form-group">
                            <label for="chi_tiet_khac">Chi tiết khác</label>
                            <textarea name="chi_tiet_khac" id="chi_tiet_khac" class="form-control"
                                      rows="5">{{ old('chi_tiet_khac') }}</textarea>
                        </div>

                        <input type="hidden" name="gia_lo_san_xuat" id="gia_lo_san_xuat"
                               value="{{ old('gia_lo_san_xuat') }}">
                        <button type="submit" class="btn btn-primary mt-2">Thêm mới</button>
                    </form>

                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="d-flex mb-4 mt-3 justify-content-end">
                <button class="btn btn-sm btn-danger" type="button" onclick="confirmDelete('thanh_pham')">Xoá
                    tất cả
                </button>
            </div>
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <table class="table table-hover " style="min-width: 2000px">
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
                                            <form action="{{ route('admin.nguyen.lieu.san.xuat.delete', $data->id) }}"
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
                                <td>{{ $data->mau_sac }}</td>
                                <td>{{ $data->mui_thom }}</td>
                                <td>{{ $data->chi_tiet_khac }}</td>
                                <td>{{ $data->bao_quan }}</td>
                                <td>{{ $data->NhanVien->full_name }}</td>
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
