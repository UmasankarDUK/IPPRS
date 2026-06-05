<?php

namespace App\Http\Controllers;

use App\Models\PlanSection;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Display search results.
     */
    public function index(Request $request)
    {
        $query = $request->input('q', '');
        $results = collect();

        if (strlen(trim($query)) >= 2) {
            // Retrieve matching plan sections
            $sections = PlanSection::where('title', 'like', "%{$query}%")
                ->orWhere('content', 'like', "%{$query}%")
                ->get();

            // Transform results to add highlights and snippets
            $results = $sections->map(function ($section) use ($query) {
                $entity = $section->planable;
                if (!$entity) return null;

                $type = strtolower(class_basename($entity));
                if ($type === 'healthinstitution') {
                    $type = 'institution';
                }
                $titleSnippet = $this->highlightKeyword($section->title, $query);
                
                // Extract clean text from HTML content for excerpt
                $cleanText = strip_tags($section->content);
                $contentSnippet = $this->createExcerpt($cleanText, $query);

                return [
                    'id' => $section->id,
                    'section_title' => $titleSnippet,
                    'raw_title' => $section->title,
                    'entity_name' => $entity->name,
                    'entity_type' => $type,
                    'entity_id' => $entity->id,
                    'snippet' => $contentSnippet,
                    'updated_at' => $section->updated_at,
                ];
            })->filter()->values();
        }

        return view('search.index', compact('query', 'results'));
    }

    /**
     * Create a text excerpt around the matched keyword.
     */
    protected function createExcerpt(string $text, string $query, int $length = 180): string
    {
        $pos = stripos($text, $query);
        if ($pos === false) {
            // If query is in title but not content, return the start of the content
            return e(mb_substr($text, 0, $length)) . (mb_strlen($text) > $length ? '...' : '');
        }

        // Calculate start and end indices
        $start = max(0, $pos - ($length / 2));
        $excerpt = mb_substr($text, $start, $length);
        
        // Add ellipses if truncated
        $prefix = $start > 0 ? '...' : '';
        $suffix = ($start + $length) < mb_strlen($text) ? '...' : '';

        // Escape text and highlight keyword
        $escaped = e($prefix . $excerpt . $suffix);
        return $this->highlightKeyword($escaped, $query);
    }

    /**
     * Highlight a keyword in a string.
     */
    protected function highlightKeyword(string $text, string $query): string
    {
        if (empty($query)) {
            return $text;
        }

        // Use regex for case-insensitive matching
        $pattern = '/' . preg_quote(e($query), '/') . '/i';
        return preg_replace($pattern, '<mark class="bg-amber-100 dark:bg-amber-900/60 text-amber-900 dark:text-amber-200 font-bold px-1 rounded">$0</mark>', $text);
    }
}
