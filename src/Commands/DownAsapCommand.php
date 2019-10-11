<?php

namespace MrWeb\DownAsap\Commands;

use Illuminate\Console\Command;

class RoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'down:asap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assegna ruoli ad utenti';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $role = $this->argument('role');
        if (Role::where('name', $role)->count() == 0) {
            Role::create(['name' => $role]);
        }

        $user = User::find($this->argument('user'));
        $user->assignRole($role);
    }
}
