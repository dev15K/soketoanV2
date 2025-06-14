@php use App\Enums\TrangThaiNguyenLieuTinh;use Carbon\Carbon; @endphp
@php @endphp
@extends('admin.layouts.master')
@section('title')
    Chỉnh sửa Kho nguyên liệu tinh
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Chỉnh sửa Kho nguyên liệu tinh</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa Kho nguyên liệu tinh</li>
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
                    <h5 class="card-title">Chỉnh sửa Kho nguyên liệu tinh</h5>
                    <form method="post" action="{{ route('admin.nguyen.lieu.tinh.update', $nguyen_lieu_tinh) }}">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="ma_phieu">Mã phiếu</label>
                                <input type="text" class="form-control bg-secondary bg-opacity-10" id="ma_phieu"
                                       name="ma_phieu" value="{{ old('ma_phieu', $ma_phieu) }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="ten_nguyen_lieu">Tên nguyên liệu</label>
                                <input type="text" class="form-control" id="ten_nguyen_lieu"
                                       name="ten_nguyen_lieu"
                                       value="{{ old('ten_nguyen_lieu', $nguyen_lieu_tinh->ten_nguyen_lieu) }}"
                                       required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="code">Mã lô hàng</label>
                                <input type="text" class="form-control bg-secondary bg-opacity-10" id="code"
                                       name="code" value="{{ old('code', $code) }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="ngay">Ngày</label>
                                <input type="date" class="form-control" id="ngay" name="ngay"
                                       value="{{ Carbon::parse($nguyen_lieu_tinh->ngay)->format('Y-m-d') }}"
                                       required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="trang_thai">Trạng thái</label>
                                <select id="trang_thai" name="trang_thai" class="form-control">
                                    <option
                                        {{ $nguyen_lieu_tinh->trang_thai == TrangThaiNguyenLieuTinh::ACTIVE() ? 'selected' : '' }}
                                        value="{{ TrangThaiNguyenLieuTinh::ACTIVE() }}">{{ TrangThaiNguyenLieuTinh::ACTIVE() }}</option>
                                    <option
                                        {{ $nguyen_lieu_tinh->trang_thai == TrangThaiNguyenLieuTinh::INACTIVE() ? 'selected' : '' }}
                                        value="{{ TrangThaiNguyenLieuTinh::INACTIVE() }}">{{ TrangThaiNguyenLieuTinh::INACTIVE() }}</option>
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
                                @foreach($dsNLTChiTiet as $nltct)
                                    <tr>
                                        <td>
                                            <select class="form-control nguyen_lieu_phan_loai_ids"
                                                    name="nguyen_lieu_phan_loai_ids[]"
                                                    onchange="selectNLPhanLoai(this)">
                                                @foreach($nlphanloais as $nlphanloai)
                                                    <option
                                                        {{ $nlphanloai->id == $nltct->nguyen_lieu_phan_loai_id ? 'selected' : '' }}
                                                        value="{{ $nlphanloai->id }}">
                                                        {{ $nlphanloai->nguyenLieuTho->code }}
                                                        - {{ $nlphanloai->nguyenLieuTho->ten_nguyen_lieu }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control" name="ten_nguyen_lieus[]">
                                                <option
                                                    {{ $nltct->ten_nguyen_lieu == 'Nguyên liệu nụ cao cấp (NCC)' ? 'selected' : '' }}
                                                    value="Nguyên liệu nụ cao cấp (NCC)">
                                                    Nguyên liệu nụ cao cấp (NCC)
                                                </option>
                                                <option
                                                    {{ $nltct->ten_nguyen_lieu == 'Nguyên liệu nụ VIP (NVIP)' ? 'selected' : '' }}
                                                    value="Nguyên liệu nụ VIP (NVIP)">
                                                    Nguyên liệu nụ VIP (NVIP)
                                                </option>
                                                <option
                                                    {{ $nltct->ten_nguyen_lieu == 'Nguyên liệu nhang (NLN)' ? 'selected' : '' }}
                                                    value="Nguyên liệu nhang (NLN)">
                                                    Nguyên liệu nhang (NLN)
                                                </option>
                                                <option
                                                    {{  $nltct->ten_nguyen_lieu == 'Nguyên liệu vòng (NLV)' ? 'selected' : '' }}
                                                    value="Nguyên liệu vòng (NLV)">
                                                    Nguyên liệu vòng (NLV)
                                                </option>
                                                <option
                                                    {{ $nltct->ten_nguyen_lieu == 'Tăm dài' ? 'selected' : '' }}
                                                    value="Tăm dài">
                                                    Tăm dài
                                                </option>
                                                <option
                                                    {{ $nltct->ten_nguyen_lieu == 'Tăm ngắn' ? 'selected' : '' }}
                                                    value="Tăm ngắn">
                                                    Tăm ngắn
                                                </option>
                                                <option
                                                    {{ $nltct->ten_nguyen_lieu == 'Nước cất' ? 'selected' : '' }}
                                                    value="Nước cất">
                                                    Nước cất
                                                </option>
                                                <option
                                                    {{ $nltct->ten_nguyen_lieu == 'Keo' ? 'selected' : '' }}
                                                    value="Keo">
                                                    Keo
                                                </option>
                                                <option
                                                    {{ $nltct->ten_nguyen_lieu == 'Nấu dầu' ? 'selected' : '' }}
                                                    value="Nấu dầu">
                                                    Nấu dầu
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="khoi_luongs[]" class="form-control onlyNumber"
                                                   value="{{ $nltct->khoi_luong }}" required>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="removeItems(this)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Lưu thay đổi</button>
                    </form>

                </div>

            </div>
        </div>

        <script>
            const baseHtml = `<tr>
                                    <td>
                                        <select class="form-control" name="nguyen_lieu_phan_loai_ids[]" onchange="selectNLPhanLoai(this)">
                                            @foreach($nlphanloais as $nlphanloai)
            <option  value="{{ $nlphanloai->id }}">
            {{ $nlphanloai->nguyenLieuTho->code }} - {{ $nlphanloai->nguyenLieuTho->ten_nguyen_lieu }}
            </option>
@endforeach
            </select>
        </td>
        <td>
            <select class="form-control" name="ten_nguyen_lieus[]" >

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

            function selectNLPhanLoai(elm) {
                const id = $(elm).val();
                const url = `{{ route('api.chi.tiet.nguyen.lieu') }}?id=${id}&type={{ \App\Enums\LoaiSanPham::NGUYEN_LIEU_PHAN_LOAI() }}`;

                $.ajax({
                    url: url,
                    type: 'GET',
                    async: false,
                    success: function (data, textStatus) {
                        renderChiTietSanPham(data.data, elm);
                    },
                    error: function (request, status, error) {
                        let data = JSON.parse(request.responseText);
                        alert(data.message);
                    }
                });
            }

            const nlPhanLoai = $('.nguyen_lieu_phan_loai_ids');
            selectNLPhanLoai(nlPhanLoai);

            function renderChiTietSanPham(data, elm) {
                const html = `
                <option value="Nguyên liệu nụ cao cấp (NCC)">Nguyên liệu nụ cao cấp (NCC) - ${data.nu_cao_cap} kg</option>
                                            <option value="Nguyên liệu nụ VIP (NVIP)">Nguyên liệu nụ VIP (NVIP) - ${data.nu_vip} kg</option>
                                            <option value="Nguyên liệu nhang (NLN)">Nguyên liệu nhang (NLN) - ${data.nhang} kg</option>
                                            <option value="Nguyên liệu vòng (NLV)">Nguyên liệu vòng (NLV) - ${data.vong} kg</option>
                                            <option value="Tăm dài">Tăm dài - ${data.tam_dai} kg</option>
                                            <option value="Tăm ngắn">Tăm ngắn - ${data.tam_ngan} kg</option>
                                            <option value="Nước cất">Nước cất - ${data.nuoc_cat} kg</option>
                                            <option value="Keo">Keo - ${data.keo} kg</option>
                                            <option value="Nấu dầu">Nấu dầu - ${data.nau_dau} kg</option>
                `;

                $(elm).parent().next().find('select').html(html);
            }
        </script>
    </section>
@endsection
