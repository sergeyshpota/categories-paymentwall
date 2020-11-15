<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Kalnoy\Nestedset\QueryBuilder;

class CategoryController extends Controller
{
    /**
     * Per page setting for collection's responses
     *
     * @var int
     */
    protected $perPage = 10;

    /**
     * Fields used for sorting
     *
     * @var string[]
     */
    protected $sortByFields = ['name'];

    /**
     * Get all categories, with the ability of sorting, searching, changing pagination and hide/show category relations
     *
     * @param Request $request
     * @return CategoryCollection
     */
    public function index(Request $request): CategoryCollection
    {
        $categories = Category::query()->withDepth();
        if ($request->has('only_root')) {
            $categories->whereIsRoot();
        }

        $categories = $this->filterQuery($categories);

        return new CategoryCollection($categories->paginate($request->get('per_page', $this->perPage)));
    }

    /**
     * Get all categories in tree view
     *
     * @return \Illuminate\Http\Response
     */
    public function getCategoriesTree(): Response
    {
        return response(['data' => Category::get()->toTree()]);
    }

    /**
     * Get child categories for given category. Available query parameters from the All categories request
     *
     * @param Request $request
     * @param $categoryId
     * @return CategoryCollection
     */
    public function getSubCategories(Request $request, $categoryId)
    {
        $category = Category::withDepth()->findOrFail($categoryId);

        $categories = Category::withDepth()->whereDescendantOf($category)->having('depth', '=', $category->depth + 1);
        $categories = $this->filterQuery($categories);

        return new CategoryCollection($categories->paginate($request->get('per_page', 10)));
    }

    /**
     * Create sigle category
     *
     * @param CategoryRequest $request
     * @return CategoryResource|Response
     */
    public function store(CategoryRequest $request)
    {
        try {
            $category = Category::create([
                'name' => $request->name
            ]);
            if ($parentId = $request->input('parent_id')) {
                $category->parent_id = $parentId;
                $category->save();
            }
        } catch (\Throwable $exception) {
            return response([
                'message' => $exception->getMessage(),
                'errors'  => []
            ], 500);
        }

        //Have to find model again to get Depth correct value
        return new CategoryResource($category->withDepth()->find($category->id));
    }

    /**
     * Get single category
     *
     * @param $id
     * @return CategoryResource
     */
    public function show($id)
    {
        $category = Category::withDepth()->findOrFail($id);


        return new CategoryResource($category);
    }


    /**
     * Update existing category
     *
     * @param CategoryRequest $request
     * @param $id
     * @return CategoryResource|Response
     */
    public function update(CategoryRequest $request, $id)
    {
        $category = Category::withDepth()->findOrFail($id);
        try {
            $category->update($request->all());
            if ($parentId = $request->input('parent_id')) {
                $category->parent_id = $parentId;
                $category->save();
            }
        } catch (\Throwable $exception) {
            return response([
                'message' => $exception->getMessage(),
                'errors'  => []
            ], 500);
        }

        //Have to find model again to get Depth correct value
        return new CategoryResource($category->withDepth()->find($id));
    }

    /**
     * Remove single category
     *
     * @param $id
     * @return Response
     */
    public function destroy($id): Response
    {
        $category = Category::withDepth()->findOrFail($id);
        try {
            $category->delete();
        } catch (\Throwable $exception) {
            return response([
                'message' => $exception->getMessage(),
                'errors'  => []
            ], 500);
        }

        return response()->noContent();
    }

    /**
     * Filter query by request arguments
     *
     * @param QueryBuilder $categories
     * @return QueryBuilder
     */
    protected function filterQuery(QueryBuilder $categories): QueryBuilder
    {
        if (\request()->has('sort_by') && in_array(\request()->get('sort_by'), $this->sortByFields)) {
            $sortDirection = in_array(strtolower(\request()->get('sort_direction')), ['asc', 'desc']) ? \request()->get('sort_direction') : 'asc';
            $categories->orderBy(\request()->get('sort_by'), $sortDirection);
        }
        if (\request()->has('search')) {
            $categories->where('name', 'LIKE', '%' . \request()->get('search') . '%');
        }

        return $categories;
    }
}
