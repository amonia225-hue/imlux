<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

/**
 * Crée (ou met à jour) un compte SUPER ADMINISTRATEUR protégé.
 *
 * Le mot de passe est passé au lancement (jamais commité dans le dépôt).
 *   php artisan admin:super arsnleriche@gmail.com 'MotDePasse' --name="Arsène"
 */
class CreateSuperAdmin extends Command
{
    protected $signature = 'admin:super {email : E-mail du compte} {password : Mot de passe} {--name=Super Admin : Nom affiché}';

    protected $description = 'Crée/met à jour un super administrateur protégé (is_admin + is_super_admin).';

    public function handle(): int
    {
        $email = trim($this->argument('email'));
        $password = $this->argument('password');

        $user = User::withoutEvents(fn () => User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $this->option('name'),
                'password' => Hash::make($password),
                'is_admin' => true,
                'is_super_admin' => true,
            ]
        ));

        $this->info("Super administrateur prêt : {$user->email} (protégé contre la suppression/rétrogradation).");

        return self::SUCCESS;
    }
}
