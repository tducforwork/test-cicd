<?php

namespace App\Observers;

use App\Models\Key;
use App\Models\Category;
use App\Models\Product;
use App\Models\News;
use App\Models\NewsCategory;

class KeyObserver
{
    public function saved($model)
    {
        if ($model->isDirty('slug') || !$model->slug) {
            $this->updateKey($model);
        }
    }

    public function created($model)
    {
        $this->updateKey($model);
    }

    public function deleted($model)
    {
        Key::where('slug', $model->slug)
            ->where('type', $this->getType($model))
            ->where('key_id', $model->id)
            ->delete();
    }

    protected function updateKey($model)
    {
        if (!$model->slug) return;

        $type = $this->getType($model);
        
        // Remove old key if slug changed
        if ($model->isDirty('slug')) {
            Key::where('type', $type)->where('key_id', $model->id)->delete();
        }

        Key::updateOrCreate(
            ['type' => $type, 'key_id' => $model->id],
            ['slug' => $model->slug]
        );
    }

    protected function getType($model)
    {
        if ($model instanceof Category) return Key::TYPE_CATEGORY;
        if ($model instanceof Product) return Key::TYPE_PRODUCT;
        if ($model instanceof News) return Key::TYPE_NEWS;
        if ($model instanceof NewsCategory) return Key::TYPE_NEWS_CATEGORY;
        return 'unknown';
    }
}
