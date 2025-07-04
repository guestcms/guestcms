<?php

namespace Guestcms\Blog\Http\Controllers\API;

use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Blog\Http\Resources\ListPostResource;
use Guestcms\Blog\Http\Resources\PostResource;
use Guestcms\Blog\Models\Post;
use Guestcms\Blog\Repositories\Interfaces\PostInterface;
use Guestcms\Blog\Supports\FilterPost;
use Guestcms\Slug\Facades\SlugHelper;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    public function __construct(protected PostInterface $postRepository)
    {
    }

    /**
     * List posts
     *
     * @group Blog
     */
    public function index(Request $request)
    {
        $data = $this->postRepository
            ->advancedGet([
                'with' => ['tags', 'categories', 'author', 'slugable'],
                'condition' => ['status' => BaseStatusEnum::PUBLISHED],
                'paginate' => [
                    'per_page' => $request->integer('per_page', 10),
                    'current_paged' => $request->integer('page', 1),
                ],
            ]);

        return $this
            ->httpResponse()
            ->setData(ListPostResource::collection($data))
            ->toApiResponse();
    }

    /**
     * Search post
     *
     * @bodyParam q string required The search keyword.
     *
     * @group Blog
     */
    public function getSearch(Request $request, PostInterface $postRepository)
    {
        $query = BaseHelper::stringify($request->input('q'));
        $posts = $postRepository->getSearch($query);

        $data = [
            'items' => $posts,
            'query' => $query,
            'count' => $posts->count(),
        ];

        if ($data['count'] > 0) {
            return $this
                ->httpResponse()
                ->setData(apply_filters(BASE_FILTER_SET_DATA_SEARCH, $data));
        }

        return $this
            ->httpResponse()
            ->setError()
            ->setMessage(trans('core/base::layouts.no_search_result'));
    }

    /**
     * Filters posts
     *
     * @group Blog
     * @queryParam page                 Current page of the collection. Default: 1
     * @queryParam per_page             Maximum number of items to be returned in result set.Default: 10
     * @queryParam search               Limit results to those matching a string.
     * @queryParam after                Limit response to posts published after a given ISO8601 compliant date.
     * @queryParam author               Limit result set to posts assigned to specific authors.
     * @queryParam author_exclude       Ensure result set excludes posts assigned to specific authors.
     * @queryParam before               Limit response to posts published before a given ISO8601 compliant date.
     * @queryParam exclude              Ensure result set excludes specific IDs.
     * @queryParam include              Limit result set to specific IDs.
     * @queryParam order                Order sort attribute ascending or descending. Default: desc .One of: asc, desc
     * @queryParam order_by             Sort collection by object attribute. Default: updated_at. One of: author, created_at, updated_at, id,  slug, title
     * @queryParam categories           Limit result set to all items that have the specified term assigned in the categories taxonomy.
     * @queryParam categories_exclude   Limit result set to all items except those that have the specified term assigned in the categories taxonomy.
     * @queryParam tags                 Limit result set to all items that have the specified term assigned in the tags taxonomy.
     * @queryParam tags_exclude         Limit result set to all items except those that have the specified term assigned in the tags taxonomy.
     * @queryParam featured             Limit result set to items that are sticky.
     */
    public function getFilters(Request $request)
    {
        $filters = FilterPost::setFilters($request->input());

        $data = $this->postRepository->getFilters($filters);

        return $this
            ->httpResponse()
            ->setData(ListPostResource::collection($data))
            ->toApiResponse();
    }

    /**
     * Get post by slug
     *
     * @group Blog
     * @queryParam slug Find by slug of post.
     */
    public function findBySlug(string $slug)
    {
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Post::class));

        if (! $slug) {
            return $this
                ->httpResponse()
                ->setError()
                ->setCode(404)
                ->setMessage('Not found');
        }

        $post = Post::query()
            ->where([
                'id' => $slug->reference_id,
                'status' => BaseStatusEnum::PUBLISHED,
            ])
            ->first();

        if (! $post) {
            return $this
                ->httpResponse()
                ->setError()
                ->setCode(404)
                ->setMessage('Not found');
        }

        return $this
            ->httpResponse()
            ->setData(new PostResource($post))
            ->toApiResponse();
    }
}
