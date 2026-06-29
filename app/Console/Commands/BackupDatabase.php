<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup {--keep=14 : Nombre de sauvegardes à conserver}';

    protected $description = 'Sauvegarde la base MySQL (mysqldump) dans storage/app/backups';

    public function handle(): int
    {
        $connection = config('database.default');
        $db = config("database.connections.$connection");

        if (($db['driver'] ?? null) !== 'mysql') {
            $this->error("Sauvegarde supportée uniquement pour MySQL (connexion actuelle : {$db['driver']}).");
            return self::FAILURE;
        }

        $dir = storage_path('app/backups');
        File::ensureDirectoryExists($dir);

        $file = $dir . DIRECTORY_SEPARATOR . config('app.name') . '_' . now()->format('Y-m-d_His') . '.sql';
        $file = preg_replace('/[^A-Za-z0-9._-]/', '_', basename($file));
        $path = $dir . DIRECTORY_SEPARATOR . $file;

        $process = new Process([
            env('MYSQLDUMP_PATH', 'mysqldump'),
            '--host=' . $db['host'],
            '--port=' . $db['port'],
            '--user=' . $db['username'],
            '--password=' . $db['password'],
            '--single-transaction',
            '--routines',
            $db['database'],
        ]);
        $process->setTimeout(600);

        $handle = fopen($path, 'w');
        $process->run(function ($type, $buffer) use ($handle) {
            if ($type === Process::OUT) {
                fwrite($handle, $buffer);
            }
        });
        fclose($handle);

        if (! $process->isSuccessful()) {
            @unlink($path);
            $this->error('Échec de la sauvegarde : ' . $process->getErrorOutput());
            return self::FAILURE;
        }

        $this->prune($dir, (int) $this->option('keep'));

        $this->info('Sauvegarde créée : ' . $path . ' (' . round(filesize($path) / 1024, 1) . ' Ko)');
        return self::SUCCESS;
    }

    private function prune(string $dir, int $keep): void
    {
        $files = collect(File::files($dir))
            ->filter(fn ($f) => str_ends_with($f->getFilename(), '.sql'))
            ->sortByDesc(fn ($f) => $f->getMTime())
            ->values();

        $files->slice($keep)->each(fn ($f) => File::delete($f->getPathname()));
    }
}
