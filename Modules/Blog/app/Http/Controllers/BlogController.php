<?php

namespace Modules\Blog\app\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Blog\app\Models\Blog;
use App\Http\Controllers\Controller;
use App\Services\JsonResponseService;
use Spatie\QueryBuilder\QueryBuilder;
use App\Services\PaginationMetaService;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Blog\app\Resources\BlogsListResource;
use Modules\Blog\app\Resources\BlogDetailsResource;
use Modules\Blog\app\Http\Requests\StoreBlogRequest;
use Modules\Blog\app\Http\Requests\UpdateBlogRequest;

class BlogController extends Controller
{
    protected $paginationMetaService;
    protected $jsonResponseService;

    public function __construct(PaginationMetaService $paginationMetaService, JsonResponseService $jsonResponseService)
    {
        $this->paginationMetaService = $paginationMetaService;
        $this->jsonResponseService = $jsonResponseService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $blogsQuery = QueryBuilder::for(Blog::class)
            ->with(['media'])
            ->select(['id', 'title', 'publish_date', 'status', 'created_at', 'deleted_at'])
            ->allowedFilters(['title', 'publish_date', 'status'])
            ->latest();

        $blogs = request()->page === null ? $blogsQuery->get() : $blogsQuery->paginate(request()->input('limit'));

        // Use PaginationMetaService to get pagination meta data
        $paginationMeta = $blogs instanceof LengthAwarePaginator ?
            $this->paginationMetaService->generatePaginationMeta($blogs) : null;

        // Generate JSON response using JsonResponseService
        $jsonResponse = $this->jsonResponseService->generateJsonResponse(
            metaData: $paginationMeta,
            data: BlogsListResource::collection($blogs),
        );

        return $jsonResponse;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBlogRequest $request): JsonResponse
    {
        $blog = Blog::create($request->validated());

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $blog->addMedia($photo)->toMediaCollection('photos');
        }

        // Generate JSON response using JsonResponseService
        $jsonResponse = $this->jsonResponseService->generateJsonResponse(
            message: trans('messages.add'),
            statusCode: Response::HTTP_CREATED
        );

        return $jsonResponse;
    }

    /**
     * Show the specified resource.
     */
    public function show(Blog $blog): JsonResponse
    {
        $blog->load(['media']);

        // Generate JSON response using JsonResponseService
        $jsonResponse = $this->jsonResponseService->generateJsonResponse(
            data: new BlogDetailsResource($blog),
        );

        return $jsonResponse;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlogRequest $request, Blog $blog): JsonResponse
    {
        $blog->update($request->validated());

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $blog->clearMediaCollection('photos');

            $photo = $request->file('photo');
            $blog->addMedia($photo)->toMediaCollection('photos');
        }

        // Generate JSON response using JsonResponseService
        $jsonResponse = $this->jsonResponseService->generateJsonResponse(
            message: trans('messages.edit'),
        );

        return $jsonResponse;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog): JsonResponse
    {
        $blog->delete();

        // Generate JSON response using JsonResponseService
        $jsonResponse = $this->jsonResponseService->generateJsonResponse(
            message: trans('messages.delete'),
        );

        return $jsonResponse;
    }
}
