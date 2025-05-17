@extends('admin.layouts.master')
@section('title')
    Kho nguyên liệu sản xuất
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Kho nguyên liệu sản xuất</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active"> Kho nguyên liệu sản xuất</li>
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
                    <h5 class="card-title"><label for="inlineFormInputGroup">Tìm kiếm theo tên nguyên liệu sản
                            xuất</label>
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
                                    <div class="input-group-prepend">
                                        <button type="button" class="input-group-text">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <div class="form-group">
                                    <select name="phieu_san_xuat_id" id="phieu_san_xuat_id" class="form-control">
                                        <option value="">Lựa chọn</option>
                                        @foreach($phieu_san_xuats as $phieu_san_xuat)
                                            <option {{ $phieu_san_xuat->id == $phieu_san_xuat_id ? 'selected' : '' }}
                                                    value="{{ $phieu_san_xuat->id }}">{{ $phieu_san_xuat->so_lo_san_xuat }}</option>
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
                        <h5 class="card-title">Thêm mới Kho nguyên liệu sản xuất</h5>
                        <button class="btn btn-sm btn-primary btnShowOrHide" type="button">Mở rộng</button>
                    </div>
                    <form method="post" action="{{ route('admin.nguyen.lieu.san.xuat.store') }}" class="d-none">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="ngay">Ngày</label>
                                <input type="date" class="form-control" id="ngay" name="ngay" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="phieu_san_xuat_id">Lô Sản Xuất</label>
                                <select id="phieu_san_xuat_id" name="phieu_san_xuat_id" class="form-control">
                                    @foreach($phieu_san_xuats as $phieu_san_xuat)
                                        <option
                                            value="{{ $phieu_san_xuat->id }}">{{ $phieu_san_xuat->so_lo_san_xuat }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ten_nguyen_lieu">Tên nguyên liệu</label>
                            <input type="text" class="form-control" id="ten_nguyen_lieu" name="ten_nguyen_lieu"
                                   required>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="khoi_luong">Khối lượng(kg)</label>
                                <input type="text" class="form-control onlyNumber" id="khoi_luong" name="khoi_luong"
                                       required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="don_vi_tinh">Đơn vị tính</label>
                                <input type="text" class="form-control" id="don_vi_tinh" name="don_vi_tinh" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="mau_sac">Màu sắc</label>
                                <input type="text" class="form-control" id="mau_sac" name="mau_sac">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="mui_thom">Mùi thơm</label>
                                <input type="text" class="form-control" id="mui_thom"
                                       name="mui_thom">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="trang_thai">Trạng thái</label>
                                <select id="trang_thai" name="trang_thai" class="form-control">
                                    <option
                                        value="{{ \App\Enums\TrangThaiNguyenLieuSanXuat::ACTIVE() }}">{{ \App\Enums\TrangThaiNguyenLieuSanXuat::ACTIVE() }}</option>
                                    <option
                                        value="{{ \App\Enums\TrangThaiNguyenLieuSanXuat::INACTIVE() }}">{{ \App\Enums\TrangThaiNguyenLieuSanXuat::INACTIVE() }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="bao_quan">Bảo quản</label>
                            <input type="text" class="form-control" id="bao_quan" name="bao_quan">
                        </div>
                        <div class="form-group">
                            <label for="chi_tiet_khac">Chi tiết khác</label>
                            <textarea name="chi_tiet_khac" id="chi_tiet_khac" class="form-control" rows="5"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Thêm mới</button>
                    </form>

                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">

                    <table class="table table-hover vw-100">
                        <colgroup>
                            <col width="5%">
                            <col width="10%">
                            <col width="10%">
                            <col width="x">
                            <col width="8%">
                            <col width="8%">
                            <col width="8%">
                            <col width="8%">
                            <col width="8%">
                            <col width="8%">
                            <col width="8%">
                            <col width="8%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Ngày</th>
                            <th scope="col">Lô Sản Xuất</th>
                            <th scope="col">Tên nguyên liệu</th>
                            <th scope="col">Khối lượng</th>
                            <th scope="col">Đơn vị tính</th>
                            <th scope="col">Màu sắc</th>
                            <th scope="col">Mùi thơm</th>
                            <th scope="col">Chi tiết khác</th>
                            <th scope="col">Bảo quản</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($datas as $data)
                            <tr>
                                <th scope="row">{{ $loop->index + 1 }}</th>
                                <td>{{ \Carbon\Carbon::parse($data->ngay)->format('d-m-Y') }}</td>
                                <td>{{ $data->PhieuSanXuat->so_lo_san_xuat }}</td>
                                <td>{{ $data->ten_nguyen_lieu }}</td>
                                <td>{{ number_format($data->khoi_luong, 0) }} kg</td>
                                <td>{{ $data->don_vi_tinh }}</td>
                                <td>{{ $data->mau_sac }}</td>
                                <td>{{ $data->mui_thom }}</td>
                                <td>{{ $data->chi_tiet_khac }}</td>
                                <td>{{ $data->bao_quan }}</td>
                                <td>{{ $data->trang_thai }}</td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('admin.nguyen.lieu.san.xuat.detail', $data->id) }}"
                                           class="btn btn-primary btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('admin.nguyen.lieu.san.xuat.delete', $data->id) }}"
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
