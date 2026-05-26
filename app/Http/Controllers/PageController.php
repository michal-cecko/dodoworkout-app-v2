<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class PageController extends Controller
{
    public function homepage(): View
    {
        $posts = Post::with(['media', 'tags'])->orderBy("published_at", "desc")->get();
        return view('pages.home', ['posts' => $posts]);
    }

    public function postsArchive(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $posts = Post::with(['media', 'tags'])
            ->visible()
            ->when($q !== '', fn ($query) => $query->whereRaw('title->>? ilike ?', [App::currentLocale(), "%{$q}%"]))
            ->orderBy('published_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('pages.blog', ['posts' => $posts, 'q' => $q]);
    }

    public function eventsArchive(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $events = Event::with(['media', 'category'])
            ->visible()
            ->when($q !== '', fn ($query) => $query->whereRaw('title->>? ilike ?', [App::currentLocale(), "%{$q}%"]))
            ->orderBy('start_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('pages.events', ['events' => $events, 'q' => $q]);
    }

    public function post(string $slug): View
    {
        $post = Post::whereRaw("slug->> ? = ?", [App::currentLocale(), $slug])
            ->with(['media', 'tags'])
            ->visible()
            ->firstOrFail();

        // Get related posts by shared tags
        $relatedPosts = Post::withWhereHas('tags', function ($query) use ($post) {
            $query->whereIn('tags.id', $post->tags->pluck('id'));
        })
            ->where('id', '!=', $post->id) // Exclude the current post
            ->visible()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Fallback: If no related posts, get posts without tags
        if ($relatedPosts->isEmpty()) {
            $relatedPosts = Post::doesntHave('tags')
                ->where('id', '!=', $post->id)
                ->visible()
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }

        return view('pages.post', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
        ]);
    }


    public function event(string $slug): View
    {
        $event = Event::whereRaw("slug->> ? = ?", [App::currentLocale(), $slug])
            ->with(['media', 'category', 'form.formFields'])
            ->visible()
            ->firstOrFail();

        $relatedEvents = Event::where('category_id', $event->category_id)
            ->where('id', '!=', $event->id) // Exclude the current event
            ->where('start_at', '>=', now())
            ->orderBy('start_at', 'asc')
            ->limit(5)
            ->get();

        if ($relatedEvents->isEmpty()) {
            $relatedEvents = Event::whereNull('category_id')
                ->where('id', '!=', $event->id)
                ->where('start_at', '>=', now())
                ->orderBy('start_at', 'asc')
                ->limit(5)
                ->get();
        }

        return view('pages.event', ['event' => $event, 'relatedEvents' => $relatedEvents]);
    }
}
