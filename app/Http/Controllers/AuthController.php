<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register', 'login']]);
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required',
            ], [
            'name.required' => 'Nama tidak boleh kosong',
            'email.required' => 'Email tidak boleh kosong',
            'password.required' => 'Password Urut tidak boleh kosong',
            'role.required' => 'Role tidak boleh kosong',
            ]);


            // dd( Auth::user()->id);
        try {
            $register = new User();
            $register->name = $request->name;
            $register->email = $request->email;
            $register->password = bcrypt($request->password);
            $register->role = $request->role;
            $register->save();


            $data = $register;

            return $this->successResponse('Data berhasil Ditambahkan', $data);
        } catch (\Throwable $th) {
            return $this->failedResponse('Data gagal Ditambahkan');
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required',
                'password' => 'required',
                'role' => 'required'
            ]);

            $credentials = $request->only(['email', 'password', 'role']);

            // dd($request->all());

            if (!$token = Auth::attempt($credentials)) {
              return response()->json(['message' => 'Login Gagal'], 401);
            }

            // $login = new User();
            // dd($this->respondWithToken($token));
            // $login->access_token = $this->respondWithToken($token);
            // $login->save();

            $data = $this->respondWithToken($token);

            return response()->json(['message' => 'Login Berhasil', 'data' => $data], 200);
          } catch (\Throwable $th) {
            throw $th;
          }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
  {
    try {
      auth()->logout();

      return response()->json(['message' => 'Successfully logged out'], 200);
    } catch (\Throwable $th) {
      throw $th;
    }
  }
}
