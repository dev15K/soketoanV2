@php use App\Enums\TrangThaiNguyenLieuTho;use Carbon\Carbon; @endphp
@php @endphp
@extends('admin.layouts.master')
@section('title')
    Kho nguyên liệu tinh
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Kho nguyên liệu tinh</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active"> Kho nguyên liệu tinh</li>
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
                                    <input type="text" class="form-control" id="code_search" name="code"
                                           placeholder="Tìm kiếm theo mã lô hàng" value="{{ $code_search }}">
                                    <div class="input-group-prepend">
                                        <button type="button" class="input-group-text">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
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
                const code_search = $('#code_search').val();

                window.location.href = "{{ route('admin.nguyen.lieu.tinh.index') }}?ngay=" + ngay_search + "&code=" + code_search;
            }
        </script>

        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Thêm mới Kho nguyên liệu tinh</h5>
                        <button class="btn btn-sm btn-primary btnShowOrHide" type="button">Mở rộng</button>
                    </div>
                    <form method="post" action="{{ route('admin.nguyen.lieu.tinh.store') }}" class="d-none">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="ma_phieu">Mã phiếu</label>
                                <input type="text" class="form-control bg-secondary bg-opacity-10" id="ma_phieu"
                                       name="ma_phieu" value="{{ $ma_phieu }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="code">Mã lô hàng</label>
                                <input type="text" class="form-control bg-secondary bg-opacity-10" id="code" name="code"
                                       value="{{ $code }}" required>
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
                                        value="{{ TrangThaiNguyenLieuTho::ACTIVE() }}">{{ TrangThaiNguyenLieuTho::ACTIVE() }}</option>
                                    <option
                                        value="{{ TrangThaiNguyenLieuTho::INACTIVE() }}">{{ TrangThaiNguyenLieuTho::INACTIVE() }}</option>
                                </select>
                            </div>
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
                                    <th scope="col">THÀNH PHẦN TRỘN TỪ MÃ ĐƠN HÀNG</th>
                                    <th scope="col">Tên NVL</th>
                                    <th scope="col">TỔNG KL</th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody id="tbodyListNL" class="text-center">
                                <tr>
                                    <td>
                                        <select class="form-control" name="nguyen_lieu_phan_loai_ids[]">
                                            @foreach($nlphanloais as $nlphanloai)
                                                <option value="{{ $nlphanloai->id }}">
                                                    {{ $nlphanloai->nguyenLieuTho->code }}
                                                    - {{ $nlphanloai->nguyenLieuTho->ten_nguyen_lieu }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control" name="ten_nguyen_lieus[]">
                                            <option value="Nguyên liệu nụ cao cấp (NCC)">Nguyên liệu nụ cao cấp (NCC)
                                            </option>
                                            <option value="Nguyên liệu nụ VIP (NVIP)">Nguyên liệu nụ VIP (NVIP)</option>
                                            <option value="Nguyên liệu nhang (NLN)">Nguyên liệu nhang (NLN)</option>
                                            <option value="Nguyên liệu vòng (NLV)">Nguyên liệu vòng (NLV)</option>
                                            <option value="Tăm tre">Tăm tre</option>
                                            <option value="Keo">Keo</option>
                                            <option value="Nấu dầu">Nấu dầu</option>
                                        </select>
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
            const baseHtml = `<tr>
                                    <td>
                                        <select class="form-control" name="nguyen_lieu_phan_loai_ids[]">
                                            @foreach($nlphanloais as $nlphanloai)
            <option  value="{{ $nlphanloai->id }}">
            {{ $nlphanloai->nguyenLieuTho->code }} - {{ $nlphanloai->nguyenLieuTho->ten_nguyen_lieu }}
            </option>
@endforeach
            </select>
        </td>
        <td>
            <select class="form-control" name="ten_nguyen_lieus[]">
                                            <option value="Nguyên liệu nụ cao cấp (NCC)">Nguyên liệu nụ cao cấp (NCC)</option>
                                            <option value="Nguyên liệu nụ VIP (NVIP)">Nguyên liệu nụ VIP (NVIP)</option>
                                            <option value="Nguyên liệu nhang (NLN)">Nguyên liệu nhang (NLN)</option>
                                            <option value="Nguyên liệu vòng (NLV)">Nguyên liệu vòng (NLV)</option>
                                            <option value="Tăm tre">Tăm tre</option>
                                            <option value="Keo">Keo</option>
                                            <option value="Nấu dầu">Nấu dầu</option>
                                        </select>
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

            $(document).ready(function () {

            })

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
                            <col width="25%">
                            <col width="10%">
                            <col width="10%">
                            <col width="15%">
                            <col width="x">
                            <col width="10%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Ngày</th>
                            <th scope="col">Mã phiếu</th>
                            <th scope="col">Mã lô hàng</th>
                            <th scope="col">Tổng khối lượng</th>
                            <th scope="col">Đơn giá</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($datas as $data)
                            <tr>
                                <th scope="row">{{ $loop->index + 1 }}</th>
                                <td>{{ Carbon::parse($data->ngay)->format('d-m-Y') }}</td>
                                <td>{{ $data->ma_phieu }}</td>
                                <td>{{ $data->code }}</td>
                                <td>{{ parseNumber($data->tong_khoi_luong, 0) }} kg</td>
                                <td>{{ parseNumber($data->gia_tien, 0) }} VND</td>
                                <td>{{ $data->trang_thai }}</td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('admin.nguyen.lieu.tinh.detail', $data->id) }}"
                                           class="btn btn-primary btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('admin.nguyen.lieu.tinh.delete', $data->id) }}"
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
