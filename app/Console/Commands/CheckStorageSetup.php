<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CheckStorageSetup extends Command
{
    protected $signature = 'storage:check';
    protected $description = 'Check storage configuration and symlink status';

    public function handle()
    {
        $this->info('=== Storage Configuration Check ===');
        $this->newLine();
        
        // Check if storage link exists
        $publicStorage = public_path('storage');
        $storagePublic = storage_path('app/public');
        
        $this->line('Storage Paths:');
        $this->line("  Public storage: {$publicStorage}");
        $this->line("  Storage public: {$storagePublic}");
        $this->newLine();
        
        if (file_exists($publicStorage)) {
            if (is_link($publicStorage)) {
                $target = readlink($publicStorage);
                $this->info("✓ Symlink exists");
                $this->line("  Target: {$target}");
                
                // Check if symlink is valid
                if (realpath($publicStorage) === realpath($storagePublic)) {
                    $this->info("✓ Symlink points to correct location");
                } else {
                    $this->warn("⚠ Symlink may not point to correct location");
                    $this->line("  Expected: {$storagePublic}");
                    $this->line("  Actual: " . realpath($publicStorage));
                }
            } else {
                $this->warn("⚠ 'public/storage' exists but is not a symlink");
            }
        } else {
            $this->error("✗ Symlink does not exist");
            $this->warn("  Run: php artisan storage:link");
        }
        
        $this->newLine();
        
        // Check storage disk
        $this->line('Storage Disk Check:');
        try {
            $files = Storage::disk('public')->files('avatars');
            $this->info("✓ Storage disk 'public' is accessible");
            $this->line("  Avatar files found: " . count($files));
            
            if (count($files) > 0) {
                $this->line("  Sample files:");
                foreach (array_slice($files, 0, 3) as $file) {
                    $exists = file_exists(public_path('storage/' . $file));
                    $icon = $exists ? '✓' : '✗';
                    $this->line("    {$icon} {$file}");
                }
            }
        } catch (\Exception $e) {
            $this->error("✗ Cannot access storage disk: " . $e->getMessage());
        }
        
        $this->newLine();
        
        // Check permissions
        $this->line('Permissions:');
        if (is_writable($storagePublic)) {
            $this->info("✓ storage/app/public is writable");
        } else {
            $this->error("✗ storage/app/public is not writable");
        }
        
        if (file_exists($publicStorage) && is_readable($publicStorage)) {
            $this->info("✓ public/storage is readable");
        } else {
            $this->error("✗ public/storage is not readable");
        }
        
        $this->newLine();
        $this->info('=== Check Complete ===');
        
        return 0;
    }
}
