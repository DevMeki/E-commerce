<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
// Note: We would typically use Intervention Image here, but let's assume basic GD for now if not installed.
// Or just move files and log.

class ProcessProductImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $product;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Resize logic placeholder
        // In a real app: Image::make($path)->resize(800, 800)->save();
        
        \Log::info("Processing images for product ID: " . $this->product->id);
        
        // Simulating work
        sleep(2);
        
        \Log::info("Finished processing images for product ID: " . $this->product->id);
    }
}
