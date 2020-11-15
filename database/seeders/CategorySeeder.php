<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Depth - how many nested layers dataset will have
        $depth = 4;
        // Amount of root categories
        $rootCatsCount = 10;
        Category::factory($rootCatsCount)->create()->each(function ($category) use ($depth) {
            $this->nextLevel($category, $depth);
        });
    }

    /**
     * Recursively add new level of data based on $depth parameter
     *
     * @param Category $category
     * @param $depth
     */
    protected function nextLevel(Category $category, &$depth)
    {
        --$depth;
        if ($depth) {
            // Randomly add children
            $category->children()->saveMany(Category::factory(rand(0,15))->create())->each(function ($category) use ($depth) {
                $this->nextLevel($category, $depth);
            });
        }
    }
}
