<?php

namespace Modules\Subscriber\app\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\User\app\Models\User;
use App\Http\Controllers\Controller;
use Modules\User\Enums\UserRoleEnum;
use App\Services\JsonResponseService;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Services\PaginationMetaService;
use Modules\Subscriber\app\Models\Subscriber;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Subscriber\app\Resources\SubscribersListResource;
use Modules\Subscriber\app\Resources\SubscriberDetailsResource;
use Modules\Subscriber\app\Http\Requests\StoreSubscriberRequest;
use Modules\Subscriber\app\Http\Requests\UpdateSubscriberRequest;

class SubscriberController extends Controller
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
        $subscribersQuery = QueryBuilder::for(Subscriber::class)
            ->with('user:id,name,username')
            ->select(['id', 'status', 'user_id', 'created_at', 'deleted_at'])
            ->allowedFilters([
                AllowedFilter::callback('name', function ($query, $value) {
                    $query->whereHas('user', function ($query) use ($value) {
                        $query->where('name', $value);
                    });
                }),
                AllowedFilter::callback('username', function ($query, $value) {
                    $query->whereHas('user', function ($query) use ($value) {
                        $query->where('username', $value);
                    });
                }),

                'status'
            ])
            ->latest();

        $subscribers = request()->page === null ? $subscribersQuery->get() : $subscribersQuery->paginate(request()->input('limit'));

        // Use PaginationMetaService to get pagination meta data
        $paginationMeta = $subscribers instanceof LengthAwarePaginator ?
            $this->paginationMetaService->generatePaginationMeta($subscribers) : null;

        // Generate JSON response using JsonResponseService
        $jsonResponse = $this->jsonResponseService->generateJsonResponse(
            metaData: $paginationMeta,
            data: SubscribersListResource::collection($subscribers),
        );

        return $jsonResponse;
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubscriberRequest $request): JsonResponse
    {
        // dd($request->validated()['userData']);
        $user = User::create($request->validated()['userData']);
        $user->subscriber()->create($request->validated()['subscriberData']);
        $user->assignRole(UserRoleEnum::subscriber);

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
    public function show(Subscriber $subscriber): JsonResponse
    {
        $subscriber->load('user');

        // Generate JSON response using JsonResponseService
        $jsonResponse = $this->jsonResponseService->generateJsonResponse(
            data: new SubscriberDetailsResource($subscriber),
        );

        return $jsonResponse;
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubscriberRequest $request, Subscriber $subscriber): JsonResponse
    {
        $subscriber->user->update($request->validated()['userData']);
        $subscriber->update($request->validated()['subscriberData']);

        // Generate JSON response using JsonResponseService
        $jsonResponse = $this->jsonResponseService->generateJsonResponse(
            message: trans('messages.edit'),
        );

        return $jsonResponse;
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscriber $subscriber): JsonResponse
    {
        $subscriber->user->delete();

        // Generate JSON response using JsonResponseService
        $jsonResponse = $this->jsonResponseService->generateJsonResponse(
            message: trans('messages.delete'),
        );

        return $jsonResponse;
    }
}
