<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Cookie;
// use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

   public function register(Request $request)
   {

      $request->validate([
         'name' => 'required|string',
         'email' => 'required|string|unique:users,email',
         'password' => 'required|string|confirmed'
      ]);


      $user = new User;

      $user->name = $request->input('name');
      $user->email = $request->input('email');
      $user->password = Hash::make($request->input('password'));

      $user->save();



      $token = $user->createToken('token')->plainTextToken;

      $response = [
         'user' => $user,
         'token' => $token,
      ];

      return response($response, 201);

      //Same
      //   return response()
      //    ->json(['user' => $user, 'jwt' => $token]);
   }

   public function login(Request $request)
   {

      //ova If nema da dozvoli da stigneme da proverka na passwordot (test e)
      if (!Auth::attempt($request->only('email', 'password'))) {
         return response()->json([
            'error' => "Nevalidni Podatoci"
         ], Response::HTTP_UNAUTHORIZED);
      }

      $request->validate([
         'email' => 'required|string',
         'password' => 'required|string'
      ]);

      //Check email and return that user - here we are not creating like in register function
      $user = User::where('email', $request->email)->first();

      //Check password
      if (!$user || !Hash::check($request->password, $user->password)) {
         return response(['error' => "Email or password are not matched"], 401);
      };

      $token = $user->createToken('token')->plainTextToken;

      $response = [
         'user' => $user,
         'token' => $token,
      ];

      //Cookie is not set in postman-??
      // $cookie = cookie('jwt', $token, 60 * 24);

      // return response([
      //    'message' => 'success'  // ->json(['jwt' => $token, 'cookie' => $cookie, 'user' => $user])
      // ])->withCookie($cookie);


      return response($response, 201);
   }




   public function logout(Request $request)
   {
      //Vaka ne sakase dava error kaj tokens metodot nesto e so sanctum helpers toa
      //auth()->user()->tokens()->delete();

      //ako ideme so request a ne preku auth() mora da odime prkeu id na userot logiran
      $user = request()->user();
      $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

      return response()
         ->json(['message' => "Uspesno"]);
   }



   //Od e_commerce_backend proektot e ovoj login za sporedba

   // public function login(Request $request)
   // {

   //    if (!Auth::attempt($request->only('email', 'password'))) {
   //       return response()->json([
   //          'error' => "Nevalidni Podatoci"
   //       ], Response::HTTP_UNAUTHORIZED);
   //    }

   //    // $user = Auth::user();

   //    // $token = $user->createToken('token')->plainTextToken;

   //    // $cookie =cookie('jwt', $token, 60 *24);

   //    //  return response()
   //    //  ->json(['jwt' => $token, 'cookie'=>$cookie])
   //    //  ->withCookie($cookie);

   //    //Posle cookie - ideme vo Authenticate.php i dodavame handle funkcija koja je kopirame od ke vidis tamu


   //    //Ova e po nacinot bez cookie i token so sanctum.
   //    //// I Vaka go dobivame userot, ne mora kako dole: $user = Auth::user();

   //    $user = User::where('email', $request->email)->first();

   //    if (!$user || !Hash::check($request->password, $user->password)) {
   //       return ['error' => "Email or password are not matched"];
   //    };

   //    return $user;
   // }

}
