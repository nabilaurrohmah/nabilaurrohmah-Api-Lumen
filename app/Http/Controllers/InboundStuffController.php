<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use App\Models\InboundStuff;
use Illuminate\Support\Str;

class InboundStuffController extends Controller
{
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'stuff_id' => 'required',
                'total' => 'required',
                'date' => 'required',
                'proff_file' => 'required|image',
            ]);

            // Str::random(jumlah_karakter) -> generate karakter random sebanyak jumlah yg diinginkan
            // getClientOriginalName() -> mengambil nama file asli file nya
            $imageName = Str::random(5) . "_" . $request->file('proff_file')->getClientOriginalName();
            // mengambil url yang dapat diakses secara public untuk menampilkan gambar
            $urlPathImage = url("upload-images/" . $imageName);
            // memindahkan/upload file ke folder public agar nnti dapat di akses user
            $request->file('proff_file')->move('upload-images', $imageName);

            $data = InboundStuff::create([
                'stuff_id' => $request->stuff_id,
                'total' => $request->total,
                'date' => $request->date,
                // yang disimpan di db data path url untuk mendapatkan/menampilkan gambar
                'proff_file' => $urlPathImage,
            ]);

            return ApiFormatter::sendResponse(200, 'success', $data);
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }
}
