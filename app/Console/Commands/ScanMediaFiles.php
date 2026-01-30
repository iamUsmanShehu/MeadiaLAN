<?php

namespace App\Console\Commands;

use App\Models\Media;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ScanMediaFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:scan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan storage/app/import for new video files and add them to the database for review';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $disk = Storage::disk('local');
        $importPath = 'import';
        $mediaPath = 'media';

        // Ensure directories exist
        if (!$disk->exists($importPath)) {
            $disk->makeDirectory($importPath);
            $this->info("Created $importPath directory. Place video files there for automatic detection.");
        }
        
        if (!$disk->exists($mediaPath)) {
            $disk->makeDirectory($mediaPath);
        }

        $files = $disk->files($importPath);
        $extensions = ['mp4', 'mkv', 'avi', 'mov', 'flv', 'webm', 'm4v'];
        $count = 0;

        // Get a default category for initial insertion
        $defaultCategory = Category::first();
        if (!$defaultCategory) {
            $this->error("No categories found. Please create at least one category in the admin panel first.");
            return 1;
        }

        foreach ($files as $file) {
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if (!in_array(strtolower($extension), $extensions)) {
                continue;
            }

            $originalFilename = basename($file);
            
            // Check if this filename is already in the database
            if (Media::where('filename', $originalFilename)->exists()) {
                $this->warn("Skipping $originalFilename: Already exists in database.");
                continue;
            }

            // Move the file from import to media folder
            $newLocation = $mediaPath . '/' . $originalFilename;
            
            try {
                if ($disk->exists($newLocation)) {
                    // If file exists in destination but not in DB, we just use the existing one
                    $disk->delete($file);
                } else {
                    $disk->move($file, $newLocation);
                }

                // Metadata extraction
                $size = $disk->size($newLocation);
                $title = str_replace(['_', '-'], ' ', pathinfo($originalFilename, PATHINFO_FILENAME));
                $title = ucwords($title);

                // Insert into database as unprocessed
                Media::create([
                    'title' => $title,
                    'filename' => $originalFilename,
                    'size' => $size,
                    'type' => 'movie', // Default, to be updated by admin
                    'category_id' => $defaultCategory->id,
                    'is_processed' => false,
                ]);

                $this->info("✅ Detected and added: $originalFilename");
                $count++;
            } catch (\Exception $e) {
                $this->error("❌ Error processing $originalFilename: " . $e->getMessage());
            }
        }

        $this->info("Scan finished. $count files were imported for review.");
        return 0;
    }
}
