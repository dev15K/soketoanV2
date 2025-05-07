@extends('admin.layouts.master')
@section('title')
    Chỉnh sửa Kho nguyên liệu phân loại
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Chỉnh sửa Kho nguyên liệu phân loại</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa Kho nguyên liệu phân loại</li>
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
                    <h5 class="card-title">Chỉnh sửa Kho nguyên liệu phân loại</h5>
                    <form method="post"
                          action="{{ route('admin.nguyen.lieu.phan.loai.update', $nguyen_lieu_phan_loai->id) }}">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="ten_nguyen_lieu">Tên nguyên liệu</label>
                            <input type="text" class="form-control" id="ten_nguyen_lieu" name="ten_nguyen_lieu"
                                   value="{{ $nguyen_lieu_phan_loai->ten_nguyen_lieu }}" required>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="ngay">Ngày</label>
                                <input type="date" class="form-control" id="ngay" name="ngay"
                                       value="{{ \Carbon\Carbon::parse($nguyen_lieu_phan_loai->ngay)->format('Y-m-d') }}"
                                       required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="nguyen_lieu_tho_id">Nguyên liệu phân loại</label>
                                <select name="nguyen_lieu_tho_id" id="nguyen_lieu_tho_id" class="form-control">
                                    @foreach($nlthos as $nltho)
                                        <option
                                            {{ $nltho->id == $nguyen_lieu_phan_loai->nguyen_lieu_tho_id ? 'selected' : '' }}
                                            value="{{ $nltho->id }}">{{ $nltho->ten_nguyen_lieu }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="nu_cao_cap">Nụ cao cấp NCC</label>
                                <input type="number" min="0" class="form-control" id="nu_cao_cap" name="nu_cao_cap"
                                       value="{{ $nguyen_lieu_phan_loai->nu_cao_cap }}" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="nu_vip">Nụ VIP(NVIP)</label>
                                <input type="number" min="0" class="form-control" id="nu_vip" name="nu_vip"
                                       value="{{ $nguyen_lieu_phan_loai->nu_vip }}" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="nhang">Nhang(NLN)</label>
                                <input type="number" min="0" class="form-control" id="nhang" name="nhang"
                                       value="{{ $nguyen_lieu_phan_loai->nhang }}" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="vong">Vòng(NLV)</label>
                                <input type="number" min="0" class="form-control" id="vong" name="vong"
                                       value="{{ $nguyen_lieu_phan_loai->vong }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="tam_tre">Tăm tre</label>
                                <input type="number" min="0" class="form-control" id="tam_tre" name="tam_tre"
                                       value="{{ $nguyen_lieu_phan_loai->tam_tre }}" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="keo">Keo</label>
                                <input type="number" min="0" class="form-control" id="keo" name="keo"
                                       value="{{ $nguyen_lieu_phan_loai->keo }}" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="nau_dau">Nấu dầu</label>
                                <input type="number" min="0" class="form-control" id="nau_dau" name="nau_dau"
                                       value="{{ $nguyen_lieu_phan_loai->nau_dau }}" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="trang_thai">Trạng thái</label>
                                <select id="trang_thai" name="trang_thai" class="form-control">
                                    <option
                                        {{ $nguyen_lieu_phan_loai->trang_thai == \App\Enums\TrangThaiNguyenLieuPhanLoai::ACTIVE() ? 'selected' : '' }}
                                        value="{{ \App\Enums\TrangThaiNguyenLieuPhanLoai::ACTIVE() }}">{{ \App\Enums\TrangThaiNguyenLieuPhanLoai::ACTIVE() }}</option>
                                    <option
                                        {{ $nguyen_lieu_phan_loai->trang_thai == \App\Enums\TrangThaiNguyenLieuPhanLoai::INACTIVE() ? 'selected' : '' }}
                                        value="{{ \App\Enums\TrangThaiNguyenLieuPhanLoai::INACTIVE() }}">{{ \App\Enums\TrangThaiNguyenLieuPhanLoai::INACTIVE() }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ghi_chu">Ghi chú</label>
                            <textarea name="ghi_chu" id="ghi_chu" class="form-control"
                                      rows="5">{{ $nguyen_lieu_phan_loai->ghi_chu }}</textarea>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="tong_khoi_luong">Tổng khối lượng</label>
                                <input type="number" min="0" class="form-control" id="tong_khoi_luong" readonly disabled
                                       value="{{ $nguyen_lieu_phan_loai->tong_khoi_luong }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="khoi_luong_ban_dau">Khối lượng ban đầu</label>
                                <input type="number" min="0" class="form-control" id="khoi_luong_ban_dau" readonly
                                       disabled value="{{ $nguyen_lieu_phan_loai->khoi_luong_ban_dau }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="khoi_luong_hao_hut">Khối lượng hao hụt</label>
                                <input type="number" min="0" class="form-control" id="khoi_luong_hao_hut" readonly
                                       disabled value="{{ $nguyen_lieu_phan_loai->khoi_luong_hao_hut }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="chi_phi_mua">Chi phí mua</label>
                                <input type="number" min="0" class="form-control" id="chi_phi_mua" readonly disabled
                                       value="{{ $nguyen_lieu_phan_loai->chi_phi_mua }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="gia_sau_phan_loai">Giá sau phân loại</label>
                                <input type="number" min="0" class="form-control" id="gia_sau_phan_loai" readonly
                                       disabled value="{{ $nguyen_lieu_phan_loai->gia_sau_phan_loai }}" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Lưu thay đổi</button>
                    </form>

                </div>

            </div>
        </div>
    </section>
@endsection
