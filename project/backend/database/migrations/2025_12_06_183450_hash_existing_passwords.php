<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HashExistingPasswords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      // Hasher tous les mots de passe en clair existants
        $users = DB::table('users')->get();

        foreach ($users as $user) {
            // Vérifier si le mot de passe n'est pas déjà hashé (les hash bcrypt commencent par $2y$)
            if (!str_starts_with($user->password, '$2y$')) {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'password' => Hash::make($user->password),
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      // Impossible de retrouver les mots de passe en clair d'où la nécessité de backup !
        throw new \Exception('Impossible de réverser cette migration - les mots de passe hashés ne peuvent pas être retrouvés en clair');
    }
}
