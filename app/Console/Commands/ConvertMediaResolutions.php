<?php

namespace App\Console\Commands;

use App\Models\Media;
use App\Models\MediaVersion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ConvertMediaResolutions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:convert {media_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate multiple resolutions (720p, 480p, 360p) for a media file using FFmpeg';

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
        $mediaId = $this->argument('media_id');
        
        if ($mediaId) {
            $media = Media::find($mediaId);
            if (!$media) {
                $this->error("Media with ID $mediaId not found.");
                return 1;
            }
            $this->processMedia($media);
        } else {
            // Process any media that doesn't have versions yet
            $mediaList = Media::whereDoesntHave('versions')->get();
            if ($mediaList->isEmpty()) {
                $this->info("No media found that requires conversion.");
                return 0;
            }
            
            foreach ($mediaList as $media) {
                $this->processMedia($media);
            }
        }

        return 0;
    }

    protected function processMedia(Media $media)
    {
        $this->info("Processing media: {$media->title} (#{$media->id})");

        $inputPath = storage_path('app/media/' . $media->filename);
        if (!file_exists($inputPath)) {
            $this->error("Original file not found: $inputPath");
            return;
        }

        $resolutions = [
            '720p' => ['width' => 1280, 'height' => 720, 'bitrate' => '2500k'],
            '480p' => ['width' => 854, 'height' => 480, 'bitrate' => '1000k'],
            '360p' => ['width' => 640, 'height' => 360, 'bitrate' => '500k'],
        ];

        $ffmpeg = env('FFMPEG_PATH', 'ffmpeg');

        foreach ($resolutions as $label => $config) {
            $outputFilename = pathinfo($media->filename, PATHINFO_FILENAME) . "_{$label}.mp4";
            $outputPath = storage_path('app/media/' . $outputFilename);

            // Skip if already exists in DB
            if ($media->versions()->where('resolution', $label)->exists()) {
                $this->warn("Version $label already exists for this media. Skipping.");
                continue;
            }

            $this->info("Converting to $label...");

            // FFmpeg command
            // -i input -vf scale=w:h -b:v bitrate -c:a copy output
            $command = [
                $ffmpeg,
                '-i', $inputPath,
                '-vf', "scale={$config['width']}:{$config['height']}:force_original_aspect_ratio=decrease,pad={$config['width']}:{$config['height']}:(ow-iw)/2:(oh-ih)/2",
                '-b:v', $config['bitrate'],
                '-c:a', 'aac', // Use aac for audio to ensure compatibility
                '-strict', '-2',
                '-y', // Overwrite output files
                $outputPath
            ];

            $process = new Process($command);
            $process->setTimeout(3600); // 1 hour timeout for large files

            try {
                $process->mustRun();
                
                // Add to database
                MediaVersion::create([
                    'media_id' => $media->id,
                    'resolution' => $label,
                    'filename' => $outputFilename,
                    'size' => filesize($outputPath),
                ]);

                $this->info("✅ Generated $label: $outputFilename");
            } catch (ProcessFailedException $exception) {
                $this->error("❌ Failed to generate $label: " . $exception->getMessage());
            }
        }
    }
}
