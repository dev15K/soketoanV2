@extends('admin.layouts.master')
@section('title')
    Sổ quỹ
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Sổ quỹ</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Sổ quỹ</li>
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
                    <h5 class="card-title"><label for="inlineFormInputGroup">Tìm kiếm</label></h5>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-start align-items-center gap-4 w-100">
                            <div class="col-md-4 form-group">
                                <div class="d-flex justify-content-start align-items-center gap-2">
                                    <label for="start_date">Từ ngày: </label>
                                    <input type="date" class="form-control" id="start_date"
                                           value="{{ $start_date }}" name="start_date">
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <div class="d-flex justify-content-start align-items-center gap-2">
                                    <label for="end_date">Đến ngày: </label>
                                    <input type="date" class="form-control" id="end_date"
                                           value="{{ $end_date }}" name="end_date">
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <div class="d-flex justify-content-start align-items-center gap-2">
                                    <label for="loai_quy_search">Loại quỹ: </label>
                                    <select name="loai_quy_search" id="loai_quy_search" class="form-control">
                                        <option value="">Tất cả</option>
                                        @foreach($loai_quies as $loai_quy)
                                            <option {{ $loai_quy->id == $loai_quy_search ? 'selected' : '' }}
                                                    value="{{ $loai_quy->id }}">{{ $loai_quy->ten_loai_quy }}</option>
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
                const loai_quy_search = $('#loai_quy_search').val();
                window.location.href = "{{ route('admin.so.quy.index') }}?start_date=" + start_date + "&end_date=" + end_date + "&loai_quy_search=" + loai_quy_search;
            }
        </script>

        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Thêm mới sổ quỹ</h5>
                        <button class="btn btn-sm btn-primary btnShowOrHide" type="button">Mở rộng</button>
                    </div>
                    <form method="post" action="{{ route('admin.so.quy.store') }}" class="d-none">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="ngay">Ngày</label>
                                <input type="date" class="form-control" id="ngay" name="ngay"
                                       value="{{ old('ngay', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="ma_phieu">Mã phiếu</label>
                                <input type="text" class="form-control bg-secondary bg-opacity-10" id="ma_phieu"
                                       name="ma_phieu" readonly
                                       value="{{ old('ma_phieu', $ma_phieu) }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="loai">Loại phiếu</label>
                                <select class="form-control" name="loai" id="loai" required>
                                    <option value="0" {{ old('loai') === '0' ? 'selected' : '' }}>Phiếu Chi</option>
                                    <option value="1" {{ old('loai') === '1' ? 'selected' : '' }}>Phiếu Thu</option>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="loai_quy_id">Tên quỹ</label>
                                <select class="form-control" name="loai_quy_id" id="loai_quy_id" required>
                                    @foreach($loai_quies as $loai_quy)
                                        <option
                                            value="{{ $loai_quy->id }}" {{ old('loai_quy_id') == $loai_quy->id ? 'selected' : '' }}>
                                            {{ $loai_quy->ten_loai_quy }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="so_tien">Số tiền</label>
                                <input type="text" class="form-control onlyNumber" id="so_tien" name="so_tien"
                                       value="{{ old('so_tien') }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="noi_dung">Nội dung</label>
                            <textarea name="noi_dung" id="noi_dung" class="form-control" rows="5"
                                      required>{{ old('noi_dung') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Thêm mới</button>
                    </form>

                </div>

            </div>
        </div>

        @php
            $thu = 0;
            $chi = 0;
            foreach ($datas as $so_quy) {
                 if ($so_quy->loai == 0)   {
                     $chi += $so_quy->so_tien;
                 } else {
                     $thu += $so_quy->so_tien;
                 }
            }
        @endphp

        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <div class="mt-4 mb-3">
                        <div class="d-flex gap-5">
                            <h4> Quỹ đầu kỳ: <span class="text-danger">{{ parseNumber($ton_dau) }} VND</span></h4>
                            <h4> Quỹ cuối kỳ: <span class="text-danger">{{ parseNumber($ton_cuoi) }} VND</span></h4>
                        </div>

                        <div class="d-flex gap-5 mt-2">
                            <h5>Tổng thu: <span class="text-danger">{{ parseNumber($thu) }} VND</span></h5>
                            <h5>Tổng chi: <span class="text-danger">{{ parseNumber($chi) }} VND</span></h5>
                        </div>
                    </div>
                    <table class="table table-hover">
                        <colgroup>
                            <col width="5%">
                            <col width="120px">
                            <col width="10%">
                            <col width="15%">
                            <col width="30%">
                            <col width="x">
                        </colgroup>
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Ngày</th>
                            <th scope="col">Loại</th>
                            <th scope="col">Tên quỹ</th>
                            <th scope="col">Số tiền</th>
                            <th scope="col">Nội dung</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($datas as $data)
                            <tr>
                                <th scope="row">{{ $loop->index + 1 }}</th>
                                <td>{{ \Carbon\Carbon::parse($data->ngay)->format('d-m-Y') }}</td>
                                <td>
                                    @if($data->loai == 0)
                                        Phiếu Chi
                                    @else
                                        Phiếu Thu
                                    @endif
                                </td>
                                <td>{{ $data->loaiQuy->ten_loai_quy }}</td>
                                <td>{{ parseNumber($data->so_tien) }} VND</td>
                                <td>{{ $data->noi_dung }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th scope="col" colspan="4">Tổng:</th>
                            <th scope="col" colspan="2">{{ parseNumber($datas->sum('so_tien')) }} VND</th>
                        </tr>
                        </tfoot>
                    </table>

                </div>

            </div>
            {{ $datas->links('pagination::bootstrap-5') }}
        </div>
    </section>
@endsection
