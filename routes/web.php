<?php

use App\Http\Controllers\PactStateController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/pact-state', PactStateController::class);

// Routes to simulate integration with other micro services
Route::prefix('fake')->group(function () {

    Route::get('/service/{id}', function (string $id) {

        $faker = Faker\Factory::create();

        return response()->json([
            'id' => $id,
            'code' => $faker->randomNumber(5),
            'name' => $faker->sentence(4),
            'unit' => $faker->word(),
            'description' => $faker->sentence(10),
        ]);
    });
});
