<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['name' => 'Alex Bagratishvili', 'username' => 'abagratishvili'],
            ['name' => 'Boris Bagashvili', 'username' => 'bbagashvili'],
            ['name' => 'George Vachnadze', 'username' => 'Gvachnadze'],
            ['name' => 'Mariam Nutsubidze', 'username' => 'mnutsubidze'],
            ['name' => 'Nikoloz budagashvili', 'username' => 'nbudagashvili'],
            ['name' => 'Nikoloz ghuzarauli', 'username' => 'nkhokhobashvili'],
            ['name' => 'Nina Eibunova', 'username' => 'neibunova'],
            ['name' => 'Nodar tsirskvaia', 'username' => 'ntsitskvaia'],
            ['name' => 'Revaz kalandadze', 'username' => 'rkalandadze'],
            ['name' => 'Nata Aspanidze', 'username' => 'Stella A'],
            ['name' => 'Imeda Kobakhidze', 'username' => 'Imeda K'],
            ['name' => 'David Kavlashvili', 'username' => 'David K'],
            ['name' => 'Elene Kalandadze', 'username' => 'Lily K'],
            ['name' => 'Adam Maged Reda', 'username' => 'Adam M'],
            ['name' => 'TB', 'username' => 'TB']
        ];


        foreach ($users as $user)
        {
            $random_password = str_random(8);
            $hashed_random_password = Hash::make($random_password);
            $txt = $user['username'] . '  ' . $random_password .'  '. PHP_EOL;
            $current = file_get_contents("passwords.txt");
            $current .= $txt;
            file_put_contents("passwords.txt", $current);

            DB::table('users')->insert(
                [
                    'name' => $user['name'],
                    'username' =>  $user['username'],
                    'password' => $hashed_random_password
                ]
            );
        }

    }
}
