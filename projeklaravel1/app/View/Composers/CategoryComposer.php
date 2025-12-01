<?php

namespace App\View\Composers;

use App\Models\Category;
use Illuminate\View\View;

class CategoryComposer
{
    public function compose(View $view)
    {
        $categories = Category::withCount('articles')->get();
        $view->with('categories', $categories);
    }
}
