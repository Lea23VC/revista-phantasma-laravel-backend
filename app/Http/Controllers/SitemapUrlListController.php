<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;

class SitemapUrlListController extends Controller
{
    //
    public function index()
    {

        $baseUrls = [
            env('FRONTEND_URL'),
            env('FRONTEND_URL') . 'contacto',
            env('FRONTEND_URL') . 'editorial',
        ];

        $categories = Category::with(['posts', 'posts.author'])->get();

        $categoryUrls = $categories->pluck('slug');

        for ($i = 0; $i < count($categoryUrls); $i++) {
            $categoryUrls[$i] = env('FRONTEND_URL') . 'phantasma/' . $categoryUrls[$i];
        }

        $postUrls = $categories->map(
            function ($category) {
                return $category->posts->pluck('slug');
            }
        )->flatten();

        $authors = $categories->map(
            function ($category) {
                return $category->posts->pluck('author.id');
            }
        )->flatten();


        for ($i = 0; $i < count($authors); $i++) {
            $authors[$i] = env('FRONTEND_URL') . 'author/' . $authors[$i];
        }

        for ($i = 0; $i < count($postUrls); $i++) {
            $postUrls[$i] = env('FRONTEND_URL') . 'post/' . $postUrls[$i];
            //
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Add each URL to the XML
        foreach (array_merge($baseUrls, $categoryUrls->toArray(), $postUrls->toArray()) as $url) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars($url) . '</loc>';
            $xml .= '</url>';
        }

        // Close the urlset element
        $xml .= '</urlset>';

        // Return response with XML header
        return response($xml)->header('Content-Type', 'application/xml');
    }
}
