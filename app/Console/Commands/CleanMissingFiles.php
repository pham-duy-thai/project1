<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Room;
use App\Models\MaintenanceRequest;
use App\Models\Violation;

class CleanMissingFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'files:clean-missing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove references to missing files from database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to clean missing files...');
        
        $totalCleaned = 0;
        
        // Clean Room images
        $this->info('Checking rooms...');
        $rooms = Room::whereNotNull('images')->get();
        foreach ($rooms as $room) {
            $images = $room->images;
            $validImages = [];
            
            foreach ($images as $image) {
                if (Storage::disk('public')->exists($image)) {
                    $validImages[] = $image;
                } else {
                    $this->warn("Missing file in Room #{$room->id}: {$image}");
                    $totalCleaned++;
                }
            }
            
            if (count($validImages) !== count($images)) {
                $room->update(['images' => $validImages]);
                $this->info("Updated Room #{$room->id}");
            }
        }
        
        // Clean Maintenance Request images
        $this->info('Checking maintenance requests...');
        $maintenanceRequests = MaintenanceRequest::whereNotNull('images')->get();
        foreach ($maintenanceRequests as $request) {
            $images = $request->images;
            $validImages = [];
            
            foreach ($images as $image) {
                if (Storage::disk('public')->exists($image)) {
                    $validImages[] = $image;
                } else {
                    $this->warn("Missing file in Maintenance Request #{$request->id}: {$image}");
                    $totalCleaned++;
                }
            }
            
            if (count($validImages) !== count($images)) {
                $request->update(['images' => $validImages]);
                $this->info("Updated Maintenance Request #{$request->id}");
            }
        }
        
        // Clean Violation evidence
        $this->info('Checking violations...');
        $violations = Violation::whereNotNull('evidence')->get();
        foreach ($violations as $violation) {
            $evidence = $violation->evidence;
            $validEvidence = [];
            
            foreach ($evidence as $item) {
                if (isset($item['path']) && Storage::disk('public')->exists($item['path'])) {
                    $validEvidence[] = $item;
                } else {
                    $path = $item['path'] ?? 'unknown';
                    $this->warn("Missing file in Violation #{$violation->id}: {$path}");
                    $totalCleaned++;
                }
            }
            
            if (count($validEvidence) !== count($evidence)) {
                $violation->update(['evidence' => $validEvidence]);
                $this->info("Updated Violation #{$violation->id}");
            }
        }
        
        $this->info("Finished! Total missing files cleaned: {$totalCleaned}");
        
        return Command::SUCCESS;
    }
}
