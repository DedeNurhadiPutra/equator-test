<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Http\Requests\StoreProductsRequest;
use App\Http\Requests\UpdateProductsRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    public function getProducts()
    {
        try {
        $data = Products::getProduct();

        return $data;
        } catch (\Throwable $th) {
        throw $th;
        }
    }

    public function detProduct($uuid)
    {
        try {
            $data = DB::table('products')->where('uuid', $uuid)->first();

        return $data;
        } catch (\Throwable $th) {
        throw $th;
        }
    }

    public function addProduct(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            ], [
            'name.required' => 'Nama produk tidak boleh kosong',
            'price.required' => 'Nama produk tidak boleh kosong',
            'quantity.required' => 'Pilih salah satu jenis Divisi'
        ]);

        try {
            $user = Auth::user();
            // dd($user->role);
            if($user->role == 2) {
                return $this->failedResponse('unauthorized');
            }

            $product = new Products();
            $product->name = $request->name;
            $product->price = $request->price;
            $product->quantity = $request->quantity;
            $product->save();

            $data = $product;

            return $this->successResponse('Data berhasil Ditambahkan', $data);
        } catch (\Throwable $th) {
            return $this->failedResponse('Data gagal Ditambahkan');
        }
    }

        public function editProduct(Request $request, $uuid)
        {
            $user = Auth::user();
            if($user->role == 2) {
                return $this->failedResponse('unauthorized');
            }
            $product = Products::find($uuid);
            if (!$product) {
                return response()->json(['message' => 'Data Tidak Ditemukan.'], 404);
            }

            $this->validate($request, [
                'name' => 'required',
                'price' => 'required',
                'quantity' => 'required',
                ], [
                'name.required' => 'Nama produk tidak boleh kosong',
                'price.required' => 'Nama produk tidak boleh kosong',
                'quantity.required' => 'Pilih salah satu jenis Divisi'
            ]);

            try {
                $user = DB::table('users')->get();
                if($user[0]->role == 2) {
                    return $this->failedResponse('unauthorized');
                }

                $product->name = $request->name;
                $product->price = $request->price;
                $product->quantity = $request->quantity;
                $product->save();

                $data = $product;

                return $this->successResponse('Data berhasil Diubah', $data);
            } catch (\Throwable $th) {
                return $this->failedResponse('Data gagal Diubah');
            }
        }

        public function delProduct($uuid)
        {
            try {
                $user = Auth::user();
                if($user->role == 2) {
                    return $this->failedResponse('unauthorized');
                }
                $product = Products::find($uuid);
                if (!$product) {
                    return response()->json(['message' => 'Data Tidak Ditemukan.'], 404);
                }

                $data = $product;

                if ($data != null) {
                    $data->delete();
                    return $this->successResponse('Data berhasil Dihapus', $data);
                }
            } catch (\Throwable $th) {
            return $this->failedResponse('Data gagal Dihapus');
            }
    }
}
