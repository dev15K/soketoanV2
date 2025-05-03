@extends('admin.layouts.master')
@section('title')
    Kho nguyên liệu phân loại
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Kho nguyên liệu phân loại</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active"> Kho nguyên liệu phân loại</li>
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
                    <h5 class="card-title"><label for="inlineFormInputGroup">Tìm kiếm theo tên nguyên liệu phân loại</label>
                    </h5>
                    <div class="col-md-4">
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" id="inlineFormInputGroup"
                                   placeholder="Tìm kiếm theo tên nguyên liệu phân loại">
                            <div class="input-group-prepend">
                                <button type="button" class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <h5 class="card-title">Thêm mới Kho nguyên liệu phân loại</h5>
                    <form method="post" action="{{ route('admin.nguyen.lieu.tho.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="ten_nguyen_lieu">Tên nguyên liệu</label>
                            <input type="text" class="form-control" id="ten_nguyen_lieu" name="ten_nguyen_lieu"
                                   required>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="ngay">Ngày</label>
                                <input type="date" class="form-control" id="ngay" name="ngay" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="nha_cung_cap_id">Nhà cung cấp</label>
                                <select name="nha_cung_cap_id" id="nha_cung_cap_id" class="form-control">
                                    @foreach($nlthos as $nltho)
                                        <option value="{{ $nltho->id }}">{{ $nltho->ten_nguyen_lieu }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="loai">Loại</label>
                                <input type="text" class="form-control" id="loai" name="loai" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="dieu_kien_luu_tru">Điều kiện lưu trữ</label>
                                <input type="text" class="form-control" id="dieu_kien_luu_tru" name="dieu_kien_luu_tru"
                                       required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="nguon_goc">Nguồn gốc</label>
                                <input type="text" class="form-control" id="nguon_goc" name="nguon_goc" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="khoi_luong">Khối lượng(kg)</label>
                                <input type="number" min="0" class="form-control" id="khoi_luong" name="khoi_luong"
                                       required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="kich_thuoc">Kích thước</label>
                                <input type="text" class="form-control" id="kich_thuoc" name="kich_thuoc"
                                       required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="do_kho">Độ khô</label>
                                <input type="number" max="100" min="0" class="form-control" id="do_kho" name="do_kho"
                                       required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="chi_phi_mua">Chi phí mua</label>
                                <input type="number" class="form-control" id="chi_phi_mua" name="chi_phi_mua"
                                       required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="phuong_thuc_thanh_toan">Phương thức thanh toán</label>
                                <input type="text" class="form-control" id="phuong_thuc_thanh_toan"
                                       name="phuong_thuc_thanh_toan"
                                       required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="cong_no">Công nợ</label>
                                <input type="number" class="form-control" id="cong_no" name="cong_no" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="nhan_su_xu_li">Nhân sự xử lý</label>
                                <input type="text" class="form-control" id="nhan_su_xu_li" name="nhan_su_xu_li"
                                       required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="thoi_gian_phan_loai">Thời gian phân loại</label>
                                <input type="date" class="form-control" id="thoi_gian_phan_loai"
                                       name="thoi_gian_phan_loai" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="trang_thai">Trạng thái</label>
                                <select id="trang_thai" name="trang_thai" class="form-control">
                                    <option
                                            value="{{ \App\Enums\TrangThaiNguyenLieuTho::ACTIVE() }}">{{ \App\Enums\TrangThaiNguyenLieuTho::ACTIVE() }}</option>
                                    <option
                                            value="{{ \App\Enums\TrangThaiNguyenLieuTho::INACTIVE() }}">{{ \App\Enums\TrangThaiNguyenLieuTho::INACTIVE() }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ghi_chu">Ghi chú</label>
                            <textarea name="ghi_chu" id="ghi_chu" class="form-control" rows="5"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Thêm mới</button>
                    </form>

                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">

                    <table class="table table-hover">
                        <colgroup>
                            <col width="5%">
                            <col width="x">
                            <col width="8%">
                            <col width="8%">
                            <col width="8%">
                            <col width="8%">
                            <col width="10%">
                            <col width="10%">
                            <col width="8%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tên nguyên liệu</th>
                            <th scope="col">Nguồn gốc</th>
                            <th scope="col">Khối lượng</th>
                            <th scope="col">Kích thước</th>
                            <th scope="col">Công nợ</th>
                            <th scope="col">Chi phí mua</th>
                            <th scope="col">Phương thức thanh toán</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($datas as $data)
                            <tr>
                                <th scope="row">{{ $loop->index + 1 }}</th>
                                <td>{{ $data->ten_nguyen_lieu }}</td>
                                <td>{{ $data->nguon_goc }}</td>
                                <td>{{ number_format($data->khoi_luong) }} kg</td>
                                <td>{{ $data->kich_thuoc }}</td>
                                <td>{{ number_format($data->cong_no) }} VND</td>
                                <td>{{ number_format($data->chi_phi_mua) }} VND</td>
                                <td>{{ $data->phuong_thuc_thanh_toan }}</td>
                                <td>{{ $data->trang_thai }}</td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('admin.nguyen.lieu.tho.detail', $data->id) }}"
                                           class="btn btn-primary btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('admin.nguyen.lieu.tho.delete', $data->id) }}"
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
