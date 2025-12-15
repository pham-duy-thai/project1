<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Student;

class CleanOrphanedAvatars extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'avatars:clean-orphaned
                            {--dry-run : Run without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up orphaned avatar references in database where files no longer exist';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('Running in DRY RUN mode - no changes will be made.');
        }
        
        $this->info('Checking for orphaned avatar references...');
        
        // Clean User avatars
        $usersCleaned = $this->cleanUserAvatars($dryRun);
        
        // Clean Student avatars
        $studentsCleaned = $this->cleanStudentAvatars($dryRun);
        
        $this->newLine();
        $this->info('Summary:');
        $this->line("  Users cleaned: {$usersCleaned}");
        $this->line("  Students cleaned: {$studentsCleaned}");
        
        if ($dryRun) {
            $this->warn('This was a DRY RUN. Run without --dry-run to apply changes.');
        } else {
            $this->success('Orphaned avatar references have been cleaned!');
        }
        
        return 0;
    }
    
    protected function cleanUserAvatars(bool $dryRun): int
    {
        $this->line('Checking User avatars...');
        $cleaned = 0;
        
        $users = User::whereNotNull('avatar')->get();
        
        foreach ($users as $user) {
            $exists = false;
            
            // Check if file exists in storage
            if (strpos($user->avatar, 'storage/') === 0) {
                $actualPath = str_replace('storage/', '', $user->avatar);
                $exists = Storage::disk('public')->exists($actualPath);
            } elseif (strpos($user->avatar, 'uploads/') === 0) {
                $uploadsPath = str_replace('uploads/', '', $user->avatar);
                $exists = file_exists(public_path('uploads/' . $uploadsPath));
            } elseif (strpos($user->avatar, 'http://') !== 0 && strpos($user->avatar, 'https://') !== 0) {
                $exists = Storage::disk('public')->exists($user->avatar);
            } else {
                // It's a URL, assume it exists
                $exists = true;
            }
            
            if (!$exists) {
                $this->warn("  User #{$user->id} ({$user->name}): Avatar not found - {$user->avatar}");
                
                if (!$dryRun) {
                    $user->avatar = null;
                    $user->save();
                }
                
                $cleaned++;
            }
        }
        
        if ($cleaned === 0) {
            $this->info('  No orphaned User avatars found.');
        }
        
        return $cleaned;
    }
    
    protected function cleanStudentAvatars(bool $dryRun): int
    {
        $this->line('Checking Student avatars...');
        $cleaned = 0;
        
        $students = Student::whereNotNull('avatar')->get();
        
        foreach ($students as $student) {
            $exists = false;
            
            // Check if file exists in storage
            if (strpos($student->avatar, 'storage/') === 0) {
                $actualPath = str_replace('storage/', '', $student->avatar);
                $exists = Storage::disk('public')->exists($actualPath);
            } elseif (strpos($student->avatar, 'uploads/') === 0) {
                $uploadsPath = str_replace('uploads/', '', $student->avatar);
                $exists = file_exists(public_path('uploads/' . $uploadsPath));
            } elseif (strpos($student->avatar, 'http://') !== 0 && strpos($student->avatar, 'https://') !== 0) {
                $exists = Storage::disk('public')->exists($student->avatar);
            } else {
                // It's a URL, assume it exists
                $exists = true;
            }
            
            if (!$exists) {
                $this->warn("  Student #{$student->id} ({$student->name}): Avatar not found - {$student->avatar}");
                
                if (!$dryRun) {
                    $student->avatar = null;
                    $student->save();
                }
                
                $cleaned++;
            }
        }
        
        if ($cleaned === 0) {
            $this->info('  No orphaned Student avatars found.');
        }
        
        return $cleaned;
    }
}
