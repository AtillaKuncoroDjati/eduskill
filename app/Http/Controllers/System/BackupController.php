<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    public function index()
    {
        return view('system.backup.index');
    }

    public function download()
    {
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST');
        $port = env('DB_PORT', 3306);

        $filename = 'db_backup-' . date('d-m-Y_H-i-s') . '.sql';
        $path = public_path("system/backups/{$filename}");

        $command = "mysqldump --user={$username} --password={$password} --host={$host} --port={$port} {$database} > {$path}";

        if (!file_exists(public_path('system/backups'))) {
            mkdir(public_path('system/backups'), 0755, true);
        }

        $result = null;
        $output = null;
        exec($command, $output, $result);

        if ($result !== 0) {
            session()->flash('failed_message', 'Gagal membuat backup database. Silakan coba lagi.');
            return redirect()->back();
        }

        return response()->download($path)->deleteFileAfterSend(true);
    }
}
