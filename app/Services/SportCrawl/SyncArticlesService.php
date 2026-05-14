<?php

namespace App\Services\SportCrawl;

use App\Models\Sport\SportArticle;

class SyncArticlesService extends SportDataService
{
    protected string $crawler = 'sync-articles';

    public function run(): void
    {
        $start = microtime(true);
        $this->resetCounters();

        foreach (config('sport-crawl.rss_feeds') as $feedUrl) {
            $xml = $this->httpGet($feedUrl);
            if (!$xml) continue;

            $this->processRssFeed($xml, $feedUrl);
        }

        $duration = (int) ((microtime(true) - $start) * 1000);
        $this->log($this->fetched > 0 ? 'success' : 'partial', null, $duration);
    }

    protected function processRssFeed(string $xml, string $source): void
    {
        $rss = @simplexml_load_string($xml);
        if (!$rss || !isset($rss->channel->item)) return;

        $keywords = $this->getFilterKeywords();

        foreach ($rss->channel->item as $item) {
            $title = (string) $item->title;
            $link = (string) $item->link;
            $this->fetched++;

            // Filter: only sport-related articles
            if (!$this->matchesKeywords($title, $keywords)) {
                $this->skipped++;
                continue;
            }

            // Dedup
            if (SportArticle::where('source_url', $link)->exists()) {
                $this->skipped++;
                continue;
            }

            SportArticle::create([
                'title' => $title,
                'summary' => strip_tags((string) ($item->description ?? '')),
                'image_url' => $this->extractImage($item),
                'type' => 'recap',
                'sport_id' => 'football',
                'source_url' => $link,
                'source' => parse_url($source, PHP_URL_HOST),
            ]);

            $this->created++;
        }
    }

    protected function getFilterKeywords(): array
    {
        $teams = \App\Models\Sport\Team::pluck('name')->toArray();
        $leagues = \App\Models\Sport\League::pluck('name')->toArray();
        return array_merge($teams, $leagues, ['World Cup', 'Champions League', 'V-League', 'Premier League', 'La Liga', 'Serie A', 'Bundesliga']);
    }

    protected function matchesKeywords(string $text, array $keywords): bool
    {
        foreach ($keywords as $kw) {
            if (stripos($text, $kw) !== false) return true;
        }
        return false;
    }

    protected function extractImage(\SimpleXMLElement $item): ?string
    {
        // Try enclosure tag
        if (isset($item->enclosure['url'])) {
            return (string) $item->enclosure['url'];
        }
        // Try media:content
        $namespaces = $item->getNamespaces(true);
        if (isset($namespaces['media'])) {
            $media = $item->children($namespaces['media']);
            if (isset($media->content['url'])) {
                return (string) $media->content['url'];
            }
        }
        return null;
    }
}
