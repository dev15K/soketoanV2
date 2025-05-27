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
                                <select name="nguyen_lieu_tho_id" id="nguyen_lieu_tho_id_search" class="form-control">
                                    <option value="">Tìm kiếm theo Mã đơn hàng</option>
                                    @foreach($nlthos as $nltho)
                                        <option {{ $nltho->id == $nguyen_lieu_tho_id ? 'selected' : '' }}
                                                value="{{ $nltho->id }}">{{ $nltho->code }}
                                            - {{ $nltho->ten_nguyen_lieu }}</option>
                                    @endforeach
                                </select>
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
                const nguyen_lieu_tho_id_search = $('#nguyen_lieu_tho_id_search').val();
                window.location.href = "{{ route('admin.nguyen.lieu.phan.loai.index') }}?ngay=" + ngay_search + "&nguyen_lieu_tho_id=" + nguyen_lieu_tho_id_search;
            }
        </script>

        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Thêm mới Kho nguyên liệu phân loại</h5>
                        <button class="btn btn-sm btn-primary btnShowOrHide" type="button">Mở rộng</button>
                    </div>
                    <form method="post" action="{{ route('admin.nguyen.lieu.phan.loai.store') }}" class="d-none">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="nguyen_lieu_tho_id">Mã đơn hàng</label>
                                <select name="nguyen_lieu_tho_id" id="nguyen_lieu_tho_id" class="form-control">
                                    @foreach($nlthos as $nltho)
                                        <option value="{{ $nltho->id }}">{{ $nltho->code }}
                                            : {{ $nltho->ten_nguyen_lieu }} : {{ parseNumber($nltho->khoi_luong - $nltho->khoi_luong_da_phan_loai) }} kg</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="ngay">Ngày</label>
                                <input type="date" class="form-control" id="ngay" name="ngay"
                                       value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="nu_cao_cap">NL nụ cao cấp (NCC)</label>
                                <input type="text" class="onlyNumber form-control" id="nu_cao_cap" name="nu_cao_cap">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="nu_vip">NL nụ VIP (NVIP)</label>
                                <input type="text" class="onlyNumber form-control" id="nu_vip" name="nu_vip">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="nhang">NL nhang (NLN)</label>
                                <input type="text" class="onlyNumber form-control" id="nhang" name="nhang">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="vong">NL vòng (NLV)</label>
                                <input type="text" class="onlyNumber form-control" id="vong" name="vong">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="tam_tre">Tăm tre</label>
                                <input type="text" class="onlyNumber form-control" id="tam_tre" name="tam_tre">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="keo">Keo</label>
                                <input type="text" class="onlyNumber form-control" id="keo" name="keo">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="nau_dau">Nấu dầu</label>
                                <input type="text" class="onlyNumber form-control" id="nau_dau" name="nau_dau">
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

                    <table class="table table-hover small" style="min-width: 2500px">
                        <colgroup>
                            <col width="50px">
                            <col width="150px">
                            <col width="150px">
                            <col width="120px">
                            <col width="120px">
                            <col width="120px">
                            <col width="180px">
                            <col width="180px">
                            <col width="180px">
                            <col width="180px">
                            <col width="250px">
                            <col width="200px">
                            <col width="200px">
                            <col width="200px">
                            <col width="200px">
                            <col width="200px">
                            <col width="250px">
                            <col width="100px">
                            <col width="100px">
                        </colgroup>
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">MÃ ĐH</th>
                            <th scope="col">Ngày phân loại</th>
                            <th scope="col">NL nụ cao cấp (NCC)</th>
                            <th scope="col">NL nụ VIP (NVIP)</th>
                            <th scope="col">NL nhang (NLN)</th>
                            <th scope="col">NL vòng (NLV)</th>
                            <th scope="col">Tăm tre</th>
                            <th scope="col">Keo</th>
                            <th scope="col">Nấu dầu</th>
                            <th scope="col">Chi phí mua</th>
                            <th scope="col">Tổng khối lượng</th>
                            <th scope="col">Khối lượng ban đầu</th>
                            <th scope="col">Khối lượng hao hụt</th>
                            <th scope="col">Khối lượng đã dùng</th>
                            <th scope="col">Khối lượng tồn</th>
                            <th scope="col">Giá trước phân loại</th>
                            <th scope="col">Giá sau phân loại</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($datas as $data)
                            <tr>
                                <th scope="row">{{ $loop->index + 1 }}</th>
                                <td>{{ $data->nguyenLieuTho->code }}</td>
                                <td>{{ \Carbon\Carbon::parse($data->ngay)->format('d-m-Y') }}</td>
                                <td>{{ parseNumber($data->nu_cao_cap, 0) }} kg</td>
                                <td>{{ parseNumber($data->nu_vip, 0) }} kg</td>
                                <td>{{ parseNumber($data->nhang, 0) }} kg</td>
                                <td>{{ parseNumber($data->vong, 0) }} kg</td>
                                <td>{{ parseNumber($data->tam_tre, 0) }} kg</td>
                                <td>{{ parseNumber($data->keo, 0) }} kg</td>
                                <td>{{ parseNumber($data->nau_dau, 0) }} kg</td>
                                <td>{{ parseNumber($data->chi_phi_mua, 0) }} VND</td>
                                <td>{{ parseNumber($data->tong_khoi_luong, 0) }} kg</td>
                                <td>{{ parseNumber($data->khoi_luong_ban_dau, 0) }} kg</td>
                                <td>{{ parseNumber($data->khoi_luong_hao_hut, 0) }} kg</td>
                                <td>{{ parseNumber($data->khoi_luong_da_phan_loai, 0) }} kg</td>
                                <td>{{ parseNumber($data->tong_khoi_luong - $data->khoi_luong_da_phan_loai, 0) }} kg</td>
                                <td>{{ parseNumber($data->gia_truoc_phan_loai, 0) }} VND</td>
                                <td>{{ parseNumber($data->gia_sau_phan_loai, 0) }} VND</td>
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
                                            <button type="button" class="btn btn-danger btn-sm btnDelete">
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
                            <th scope="col">{{ parseNumber($datas->sum('nu_cao_cap'), 0) }} kg</th>
                            <th scope="col">{{ parseNumber($datas->sum('nu_vip'), 0) }} kg</th>
                            <th scope="col">{{ parseNumber($datas->sum('nhang'), 0) }} kg</th>
                            <th scope="col">{{ parseNumber($datas->sum('vong'), 0) }} kg</th>
                            <th scope="col">{{ parseNumber($datas->sum('tam_tre'), 0) }} kg</th>
                            <th scope="col">{{ parseNumber($datas->sum('keo'), 0) }} kg</th>
                            <th scope="col">{{ parseNumber($datas->sum('nau_dau'), 0) }} kg</th>
                            <th scope="col"></th>
                            <th scope="col">{{ parseNumber($datas->sum('tong_khoi_luong'), 0) }} kg</th>
                            <th scope="col">{{ parseNumber($datas->sum('khoi_luong_ban_dau'), 0) }} kg</th>
                            <th scope="col">{{ parseNumber($datas->sum('khoi_luong_hao_hut'), 0) }} kg</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col"></th>
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
