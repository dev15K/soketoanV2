<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ExportDataController extends Controller
{
    public function export_to_excel(Request $request)
    {
        $type = $request->input('type');

        switch ($type) {
            case 'so_quy':
                $this->export_so_quy($request);
                break;
            case 'nhom_quy':
                $this->export_nhom_quy($request);
                break;
            case 'loai_quy':
                $this->export_loai_quy($request);
                break;
            case 'nguyen_lieu_tho':
                $this->export_nguyen_lieu_tho($request);
                break;
            case 'nguyen_lieu_phan_loai':
                $this->export_nguyen_lieu_phan_loai($request);
                break;
            case 'nguyen_lieu_tinh':
                $this->export_nguyen_lieu_tinh($request);
                break;
            case 'phieu_san_xuat':
                $this->export_phieu_san_xuat($request);
                break;
            case 'nguyen_lieu_san_xuat':
                $this->export_nguyen_lieu_san_xuat($request);
                break;
            case 'nguyen_lieu_thanh_pham':
                $this->export_nguyen_lieu_thanh_pham($request);
                break;
            case 'san_pham':
                $this->export_san_pham($request);
                break;
            case 'ban_hang':
                $this->export_ban_hang($request);
                break;
            case 'nha_cung_cap':
                $this->export_nha_cung_cap($request);
                break;
            case 'khach_hang':
                $this->export_khach_hang($request);
                break;
            case 'nhom_khach_hang':
                $this->export_nhom_khach_hang($request);
                break;
            default:
                break;
        }
    }

    private function export_so_quy(Request $request)
    {
        try {
            $spreadsheet = new Spreadsheet();
        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function export_nhom_quy(Request $request)
    {
        try {

        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function export_loai_quy(Request $request)
    {
        try {

        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function export_nguyen_lieu_tho(Request $request)
    {
        try {

        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function export_nguyen_lieu_phan_loai(Request $request)
    {
        try {

        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function export_nguyen_lieu_tinh(Request $request)
    {
        try {

        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function export_phieu_san_xuat(Request $request)
    {
        try {

        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function export_nguyen_lieu_san_xuat(Request $request)
    {
        try {

        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function export_nguyen_lieu_thanh_pham(Request $request)
    {
        try {

        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function export_san_pham(Request $request)
    {
        try {

        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function export_ban_hang(Request $request)
    {
        try {

        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function export_nha_cung_cap(Request $request)
    {
        try {

        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function export_khach_hang(Request $request)
    {
        try {

        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function export_nhom_khach_hang(Request $request)
    {
        try {

        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }
}
