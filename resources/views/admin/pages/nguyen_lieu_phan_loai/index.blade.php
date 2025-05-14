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
                    <h5 class="card-title"><label for="inlineFormInputGroup">Tìm kiếm theo tên nguyên liệu phân
                            loại</label>
                    </h5>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-start align-items-center gap-4 w-100">
                            <div class="col-md-4 form-group">
                                <div class="d-flex justify-content-start align-items-end gap-2">
                                    <label for="ngay">Ngày: </label>
                                    <input type="date" class="form-control" id="ngay" name="ngay">
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="inlineFormInputGroup"
                                           placeholder="Tìm kiếm theo tên nguyên liệu thô">
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
                    <h5 class="card-title">Thêm mới Kho nguyên liệu phân loại</h5>
                    <form method="post" action="{{ route('admin.nguyen.lieu.phan.loai.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="ten_nguyen_lieu">Tên nguyên liệu</label>
                            <input type="text" class="form-control" id="ten_nguyen_lieu" name="ten_nguyen_lieu"
                                   value="" required>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="ngay">Ngày</label>
                                <input type="date" class="form-control" id="ngay" name="ngay" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="nguyen_lieu_tho_id">Nguyên liệu thô</label>
                                <select name="nguyen_lieu_tho_id" id="nguyen_lieu_tho_id" class="form-control">
                                    @foreach($nlthos as $nltho)
                                        <option value="{{ $nltho->id }}">{{ $nltho->ten_nguyen_lieu }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="nu_cao_cap">Nụ cao cấp NCC</label>
                                <input type="number" min="0" class="form-control" id="nu_cao_cap" name="nu_cao_cap"
                                       required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="nu_vip">Nụ VIP(NVIP)</label>
                                <input type="number" min="0" class="form-control" id="nu_vip" name="nu_vip"
                                       required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="nhang">Nhang(NLN)</label>
                                <input type="number" min="0" class="form-control" id="nhang" name="nhang" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="vong">Vòng(NLV)</label>
                                <input type="number" min="0" class="form-control" id="vong" name="vong"
                                       required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="tam_tre">Tăm tre</label>
                                <input type="number" min="0" class="form-control" id="tam_tre" name="tam_tre"
                                       required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="keo">Keo</label>
                                <input type="number" min="0" class="form-control" id="keo" name="keo"
                                       required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="nau_dau">Nấu dầu</label>
                                <input type="number" min="0" class="form-control" id="nau_dau" name="nau_dau"
                                       required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="trang_thai">Trạng thái</label>
                                <select id="trang_thai" name="trang_thai" class="form-control">
                                    <option
                                        value="{{ \App\Enums\TrangThaiNguyenLieuPhanLoai::ACTIVE() }}">{{ \App\Enums\TrangThaiNguyenLieuPhanLoai::ACTIVE() }}</option>
                                    <option
                                        value="{{ \App\Enums\TrangThaiNguyenLieuPhanLoai::INACTIVE() }}">{{ \App\Enums\TrangThaiNguyenLieuPhanLoai::INACTIVE() }}</option>
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
                @php
                    $total_nu_cao_cap = $total_nu_vip = $total_nhang = $total_vong = $total_tam_tre = $total_keo = $total_nau_dau = $total_ghi_chu = 0;
                @endphp
                <div class="card-body">

                    <table class="table table-hover vw-100">
                        <colgroup>
                            <col width="5%">
                            <col width="10%">
                            <col width="6%">
                            <col width="5%">
                            <col width="5%">
                            <col width="5%">
                            <col width="5%">
                            <col width="5%">
                            <col width="5%">
                            <col width="5%">
                            <col width="6%">
                            <col width="6%">
                            <col width="6%">
                            <col width="6%">
                            <col width="x">
                            <col width="6%">
                            <col width="6%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tên nguyên liệu</th>
                            <th scope="col">Nguyên liệu thô</th>
                            <th scope="col">Nụ cao cấp</th>
                            <th scope="col">Nụ VIP</th>
                            <th scope="col">Nhang</th>
                            <th scope="col">Vòng</th>
                            <th scope="col">Tăm tre</th>
                            <th scope="col">Keo</th>
                            <th scope="col">Nấu dầu</th>
                            <th scope="col">Chi phí mua</th>
                            <th scope="col">Tổng khối lượng</th>
                            <th scope="col">Khối lượng ban đầu</th>
                            <th scope="col">Khối lượng hao hụt</th>
                            <th scope="col">Giá sau phân loại</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($datas as $data)
                            <tr>
                                <th scope="row">{{ $loop->index + 1 }}</th>
                                <td>{{ $data->id }} - {{ $data->ten_nguyen_lieu }}</td>
                                <td>{{ $data->nguyenLieuTho->ten_nguyen_lieu }}</td>
                                <td>{{ number_format($data->nu_cao_cap) }} kg</td>
                                <td>{{ number_format($data->nu_vip) }} kg</td>
                                <td>{{ number_format($data->nhang) }} kg</td>
                                <td>{{ number_format($data->vong) }} kg</td>
                                <td>{{ number_format($data->tam_tre) }} kg</td>
                                <td>{{ number_format($data->keo) }} kg</td>
                                <td>{{ number_format($data->nau_dau) }} kg</td>
                                <td>{{ number_format($data->chi_phi_mua) }} VND</td>
                                <td>{{ number_format($data->tong_khoi_luong) }} kg</td>
                                <td>{{ number_format($data->khoi_luong_ban_dau) }} kg</td>
                                <td>{{ number_format($data->khoi_luong_hao_hut) }} kg</td>
                                <td>{{ number_format($data->gia_sau_phan_loai) }} VND</td>
                                <td>{{ $data->trang_thai }}</td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('admin.nguyen.lieu.phan.loai.detail', $data->id) }}"
                                           class="btn btn-primary btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('admin.nguyen.lieu.phan.loai.delete', $data->id) }}"
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
                        <tfoot class="bg-primary bg-opacity-10">
                        <tr>
                            <th scope="col">Tổng:</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col">{{ number_format($datas->sum('nu_cao_cap')) }} kg</th>
                            <th scope="col">{{ number_format($datas->sum('nu_vip')) }} kg</th>
                            <th scope="col">{{ number_format($datas->sum('nhang')) }} kg</th>
                            <th scope="col">{{ number_format($datas->sum('vong')) }} kg</th>
                            <th scope="col">{{ number_format($datas->sum('tam_tre')) }} kg</th>
                            <th scope="col">{{ number_format($datas->sum('keo')) }} kg</th>
                            <th scope="col">{{ number_format($datas->sum('nau_dau')) }} kg</th>
                            <th scope="col"></th>
                            <th scope="col">{{ number_format($datas->sum('tong_khoi_luong')) }} kg</th>
                            <th scope="col">{{ number_format($datas->sum('khoi_luong_ban_dau')) }} kg</th>
                            <th scope="col">{{ number_format($datas->sum('khoi_luong_hao_hut')) }} kg</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                        </tfoot>
                    </table>

                    {{ $datas->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </section>
@endsection
