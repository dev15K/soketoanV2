<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>@yield('title')</title>

    @include('inc.head')

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('admin/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/simple-datatables/style.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">
    <script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 Bootstrap 5 Theme -->
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.2.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        .table-responsive {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
        }
    </style>
    <script>
        async function confirmDelete(type) {
            let countChecked = $('input[name="check_item[]"]:checked').length;

            if (countChecked === 0) {
                alert('Vui lòng chọn lựa chọn muuốn xóa!');
                return false;
            }

            if (!confirm('Bạn có chắc chắn muốn xóa các lựa chọn không?')) {
                return false;
            }

            await deleteAllItemSelected(type);
        }

        async function deleteAllItemSelected(type) {
            const list_id = [];
            $('input[name="check_item[]"]:checked').each(async function () {
                list_id.push($(this).val());
            })

            const url = `{{ route('api.admin.delete.items') }}`;

            $.ajax({
                url: url,
                method: 'DELETE',
                async: false,
                data: {
                    list_id: list_id,
                    type: type,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    console.log(response)
                    alert('Xóa thành công!');
                    window.location.reload();
                },
                error: function (exception) {
                    console.log(exception)
                }
            });
        }
    </script>
</head>

<body>

<!-- ======= Header ======= -->
@include('admin.layouts.header')
<!-- End Header -->

<!-- ======= Sidebar ======= -->
@include('admin.layouts.sidebar')
<!-- End Sidebar-->

@include('sweetalert::alert')

<!-- ======= Main ======= -->
<main id="main" class="main">

    @yield('content')

</main>
<!-- End #main -->

<!-- ======= Footer ======= -->
@include('admin.layouts.footer')
<!-- End Footer -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>

<script>
    $(document).ready(function () {
        $(document).on('click', '.btnShowOrHide', function (e) {
            e.preventDefault();
            const text = $(this).text();
            if (text === 'Mở rộng') {
                $(this).text('Thu gọn');
                $(this).parent().parent().find('form').removeClass('d-none');
            } else {
                $(this).text('Mở rộng');
                $(this).parent().parent().find('form').addClass('d-none');
            }
        });

        $('.onlyNumber').on('keypress', function (e) {
            const char = String.fromCharCode(e.which);
            if (!/[0-9.]/.test(char)) {
                e.preventDefault(); // Chặn ký tự không hợp lệ
            }
        }).on('input', function () {
            $(this).val(function (i, val) {
                return val.replace(/[^0-9.]/g, ''); // Xoá ký tự không hợp lệ
            });
        });

        $('.btnDelete').on('click', function () {
            if (confirm('Bạn có chắc chắn muốn xóa không?')) {
                $(this).closest('form').submit();
            }
        });

        $('#check_all').on('change', function () {
            if (this.checked) {
                $('input[name="check_item[]"]').each(function () {
                    this.checked = true;
                });
            } else {
                $('input[name="check_item[]"]').each(function () {
                    this.checked = false;
                });
            }
        })
    })
</script>
<script>
    $(document).ready(function () {
        $('.selectCustom').select2({
            theme: 'bootstrap-5',
            placeholder: 'Lựa chọn...',
            allowClear: true,
            width: '100%',
            minimumResultsForSearch: 0
        });
    });
</script>
<!-- Vendor JS Files -->
<script src="{{ asset('admin/vendor/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin/vendor/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('admin/vendor/echarts/echarts.min.js') }}"></script>
<script src="{{ asset('admin/vendor/quill/quill.min.js') }}"></script>
<script src="{{ asset('admin/vendor/simple-datatables/simple-datatables.js') }}"></script>
<script src="{{ asset('admin/vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('admin/vendor/php-email-form/validate.js') }}"></script>

<!-- Template Main JS File -->
<script src="{{ asset('admin/js/main.js') }}"></script>
</body>

</html>
