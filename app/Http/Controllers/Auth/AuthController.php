<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Auth;
use App\Mail\WelcomeMail;
use App\Mail\VerificationEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validate =  Validator::make($request->all(),
        [
            'name'=>'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validate->fails())
        {
            return response()->json(["errors"=>$validate->errors(),
            "msg"=>"Errores de validación"],422);
        }

        $user=User::create([
            'name'=> $request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);

        $signed = URL::signedRoute(
            'activate',
            ['user'=>$user->id]
        );
        
        Mail::to($user->email)->send(new WelcomeMail($signed));

        return $user;
    }

    public function activate(Request $request)
    {
        if (!$request->hasValidSignature()) {
            return response()->json(['msg'=>"Link no válido!"],401);
        }
        
        $user = User::find($request->user);

        if (!$user) {
            return response()->json(['msg'=>"Usuario no encontrado"],404);
        }

    $user->update(['is_active' => true]);

    
        return response()->json(['msg'=>"¡Se ha activado tu cuenta!"],200);
    }

    public function login(Request $request)
    {
        $validate= Validator::make($request->all(),
        [
             'email' => 'required|email',
             'password' => 'required',
        ]
        );
 
        if($validate->fails()){
         return response()->json(['error' => 'Invalid Data'], 401);
        }
 
        $credentials = request(['email', 'password']);

        if (! $token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'Invalid Credentials'], 401);
        }
     
         $user=User::where('email', $request->email)->first();
        
         if(!$user->is_active)
         {
            return response()->json(['error' => 'Activa tu cuenta primero'], 401);
         }
         $code = mt_rand(1111,9999);
         $hashedcode= Hash::make($code);
        
         $user->verified_token = $hashedcode;
         $user->save();

         Mail::to($user->email)->send(new VerificationEmail($code));
            
         return response()->json(['token'=>$token,
         'id'=>$user->id,
         'verified_token'=>$user->verified_token,
         'email'=>$user->email,
        'password'=>$request->password],200);
    }

    public function verify(Request $request)
    {
        $validate = Validator::make($request->all(),
        [
            'email'=>'required',
            'password'=>'required',
            'code'=>'required'
        ]);

        if($validate->fails())
        {
            return response()->json(["errors"=>$validate->errors(),
            "msg"=>"Errores de validación"],422);
        }

        $email = $request->email;
        $password= $request->password;
        $code = $request->code;
        $user = User::where('email', $email)->first();
        if ($user) {
            $verified_token = $user->verified_token;
            $credentials = ['email' => $email, 'password' => $password];
             if (!Auth::guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
            }
            if(Hash::check($code, $verified_token))
            {
                $user->update(['verified'=>true]);
    
                return response()->json(['usuario'=>$user],201);
            }
            return response()->json(['msg'=>"El código no coincide!"],401);
        }
        
        return response()->json(['message' => 'Usuario no encontrado'], 404);
    }
    
    public function logout(Request $request)
    {
        Auth::guard('api')->logout();

        $user = $request->user();

        if ($user) {
            $user->update(['is_verified' => false]);
        }

        return response()->json(['message' => 'Logout exitoso'], 200);

    }

    public function me()
    {
        $user = Auth::guard('api')->user();
        if ($user) {
            return response()->json($user, 200);
        }
        return response()->json([], 401);
            
    }

}
