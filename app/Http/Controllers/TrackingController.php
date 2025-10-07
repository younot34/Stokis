<?php

namespace App\Http\Controllers;

use App\Services\BinderByteService;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index()
    {
        // Daftar kurir umum yang didukung BinderByte
        $courierList = [
            ['slug' => 'jne', 'name' => 'JNE'],
            ['slug' => 'pos', 'name' => 'POS Indonesia'],
            ['slug' => 'tiki', 'name' => 'TIKI'],
            ['slug' => 'anteraja', 'name' => 'AnterAja'],
            ['slug' => 'jnt', 'name' => 'J&T Express'],
            ['slug' => 'sicepat', 'name' => 'SiCepat'],
            ['slug' => 'wahana', 'name' => 'Wahana'],
            ['slug' => 'spx', 'name' => 'Shopee Express'],
            ['slug' => 'lex', 'name' => 'Lazada Express'],
        ];

        return view('admin.tracker.index', compact('courierList'));
    }
    public function indexwarehouse()
    {
        // Daftar kurir umum yang didukung BinderByte
        $courierList = [
            ['slug' => 'jne', 'name' => 'JNE'],
            ['slug' => 'pos', 'name' => 'POS Indonesia'],
            ['slug' => 'tiki', 'name' => 'TIKI'],
            ['slug' => 'anteraja', 'name' => 'AnterAja'],
            ['slug' => 'jnt', 'name' => 'J&T Express'],
            ['slug' => 'sicepat', 'name' => 'SiCepat'],
            ['slug' => 'wahana', 'name' => 'Wahana'],
            ['slug' => 'spx', 'name' => 'Shopee Express'],
            ['slug' => 'lex', 'name' => 'Lazada Express'],
        ];

        return view('warehouse.tracking.index', compact('courierList'));
    }

    public function track(Request $request)
    {
        $request->validate([
            'courier' => 'required',
            'waybill' => 'required',
        ]);

        $service = new BinderByteService();
        $data = $service->track($request->courier, $request->waybill);

        return view('admin.tracker.result', compact('data'));
    }
    public function tracking(Request $request)
    {
        $request->validate([
            'courier' => 'required',
            'waybill' => 'required',
        ]);

        $service = new BinderByteService();
        $data = $service->track($request->courier, $request->waybill);

        return view('warehouse.tracking.result', compact('data'));
    }
}
