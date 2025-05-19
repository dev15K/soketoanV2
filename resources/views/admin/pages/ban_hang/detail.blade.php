@extends('admin.layouts.master')
@section('title')
    Chỉnh sửa bán hàng
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Chỉnh sửa khách hàng</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa bán hàng</li>
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
                    <h5 class="card-title">Chỉnh sửa bán hàng</h5>
                    <form method="post" action="{{ route('admin.ban.hang.update', $banhang) }}">
                        @method('PUT')
                        @csrf

                        <button type="submit" class="btn btn-primary mt-2">Lưu thay đổi</button>
                    </form>

                </div>

            </div>
        </div>
    </section>
@endsection
