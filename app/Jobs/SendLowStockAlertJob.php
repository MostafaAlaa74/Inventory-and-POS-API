<?php

namespace App\Jobs;

use App\Mail\LowStockEmail;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendLowStockAlertJob implements ShouldQueue
{
    use Queueable;

    public $products;

    /**
     * Create a new job instance.
     */
    public function __construct(Collection $products)
    {
        $this->products = $products;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to('admin@pos.com')->send(new LowStockEmail($this->products));
    }
}
