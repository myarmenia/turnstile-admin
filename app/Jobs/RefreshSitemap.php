<?php
// app/Jobs/RefreshSitemap.php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RefreshSitemap implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 30;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly ?string $triggeredBy = null  // только для понимания в логах
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $frontendUrl = config('services.nextjs.url');
        $revalidateToken = config('services.nextjs.revalidate_token');

        if (!$frontendUrl || !$revalidateToken) {
            Log::warning('Sitemap refresh skipped: Next.js configuration missing');
            $this->delete();
            return;
        }

        try {
            $response = Http::timeout(5)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $revalidateToken
                ])
                ->post($frontendUrl . '/api/revalidate-sitemap');

            if ($response->successful()) {
                Log::info('Sitemap revalidated successfully', [
                    'triggered_by' => $this->triggeredBy
                ]);
            } else {
                Log::error('Sitemap revalidation failed', [
                    'status' => $response->status(),
                    'triggered_by' => $this->triggeredBy
                ]);

                if ($response->status() >= 500) {
                    $this->release(60); // пробуем через минуту
                }
            }
        } catch (\Exception $e) {
            Log::error('Sitemap revalidation exception', [
                'error' => $e->getMessage(),
                'triggered_by' => $this->triggeredBy
            ]);
            $this->release(30);
        }
    }
}
