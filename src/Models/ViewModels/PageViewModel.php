<?php

namespace Lemaur\Cms\Models\ViewModels;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Lemaur\Cms\Models\Page;
use Spatie\ViewModels\ViewModel;

class PageViewModel extends ViewModel
{
    public function __construct(private Page $page)
    {
        $this->view = sprintf('cms::%s', $this->page->layout);
    }

    public function meta(): array
    {
        // TODO
        return [
            'title' => $this->page->getExtraAttribute('title', $this->page->title),
            'description' => $this->page->getExtraAttribute('description', Str::limit($this->page->excerpt ?? $this->page->content, 150, '')),
            'opengraph' => [],
            'twitter' => [],
            'schema' => [],
        ];
    }

    public function fullUrl(): string
    {
        $collection = collect([]);

        return secure_url($collection->filter()->implode('/'));
    }

    public function excerptHtml(): string | null
    {
        return Str::markdown($this->page->excerpt, config('cms.markdown', []));
    }

    public function contentHtml(): string | null
    {
        return Str::markdown($this->page->content, config('cms.markdown', []));
    }

    public function articles(): EloquentCollection | null
    {
        // @TODO
    }

    public function category(): Model | null
    {
        // @TODO
    }

    public function tags(): EloquentCollection | null
    {
        // @TODO
    }

    public function topic(): Model | null
    {
        // @TODO
    }

    public function media(): array | null
    {
        // @TODO
        return [
            [
                'src' => '',
                'alt' => '',
                'caption' => '',
            ],
        ];
    }
}
