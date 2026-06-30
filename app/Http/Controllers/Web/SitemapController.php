<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [
            ['route' => 'home', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['route' => 'how-it-works', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['route' => 'success-stories', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['route' => 'features', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['route' => 'pricing', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['route' => 'about', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['route' => 'contact', 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['route' => 'help', 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['route' => 'guidelines', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['route' => 'api-docs', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['route' => 'documentation', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['route' => 'blog', 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['route' => 'security', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['route' => 'integrations', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['route' => 'changelog', 'priority' => '0.5', 'changefreq' => 'weekly'],
            ['route' => 'careers', 'priority' => '0.4', 'changefreq' => 'monthly'],
            ['route' => 'press', 'priority' => '0.4', 'changefreq' => 'monthly'],
            ['route' => 'partners', 'priority' => '0.4', 'changefreq' => 'monthly'],
            ['route' => 'leaderboard', 'priority' => '0.6', 'changefreq' => 'daily'],
            ['route' => 'privacy', 'priority' => '0.3', 'changefreq' => 'yearly'],
            ['route' => 'terms', 'priority' => '0.3', 'changefreq' => 'yearly'],
            ['route' => 'login', 'priority' => '0.5', 'changefreq' => 'yearly'],
            ['route' => 'register', 'priority' => '0.6', 'changefreq' => 'yearly'],
        ];

        foreach (['conveyor-belt-optimization', 'supply-chain-visibility', 'hr-onboarding-automation'] as $slug) {
            $urls[] = ['route' => 'success-stories.show', 'params' => [$slug], 'priority' => '0.6', 'changefreq' => 'monthly'];
        }

        $xml = view('sitemap', compact('urls'))->render();

        return response($xml, 200)->header('Content-Type', 'text/xml');
    }
}
