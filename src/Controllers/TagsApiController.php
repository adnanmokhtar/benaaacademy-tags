<?php

namespace Benaaacademy\Tags\Controllers;

use Benaaacademy\Platform\APIController;
use Benaaacademy\Tags\Models\Tag;
use Illuminate\Http\Request;

/**
 * Class TagsApiController
 */
class TagsApiController extends APIController
{

    /**
     * TagsApiController constructor.
     */
    function __construct(Request $request)
    {
        parent::__construct($request);
        $this->middleware("permission:tags.manage");
    }

    /**
     * List tags
     * @param int $id (optional) The object identifier.
     * @param string $q (optional) The search query string.
     * @param int $limit (default: 10) The number of retrieved records.
     * @param int $page (default: 1) The page number.
     * @param string $order_by (default: id) The column you wish to sort by.
     * @param string $order_direction (default: DESC) The sort direction ASC or DESC.
     * @return \Illuminate\Http\JsonResponse
     */
    function show(Request $request)
    {

        $id = $request->get("id");
        $limit = $request->get("limit", 10);
        $sort_by = $request->get("order_by", "id");
        $sort_direction = $request->get("order_direction", "DESC");

        $query = Tag::orderBy($sort_by, $sort_direction);

        if ($request->filled("q")) {
            $query->search($request->get("q"));
        }

        if ($id) {
            $tags = $query->where("id", $id)->first();
        } else {
            $tags = $query->paginate($limit)->appends($request->all());
        }

        return $this->response($tags);

    }


    /**
     * Create a new tag
     * @param string $name (required) The tag name.
     * @return \Illuminate\Http\JsonResponse
     */
    function create(Request $request)
    {

        $tag = new Tag();

        $tag->name = $request->name;
        $tag->slug = $request->slug;

        // Validate and save requested user
        if (!$tag->validate()) {

            // return validation error
            return $this->response($tag->errors(), "validation error");

        }

        if ($tag->save()) {
            return $this->response($tag);
        }

    }

    /**
     * Update tag by id
     * @param int $id (required) The user id.
     * @param string $name (required) The tag name.
     * @return \Illuminate\Http\JsonResponse
     */
    function update(Request $request)
    {

        if (!$request->id) {
            return $this->error("Missing tag id");
        }

        $tag = Tag::find($request->id);

        if (!$tag) {
            return $this->error("Post #" . $request->id . " is not exists");
        }

        $tag->name = $request->get('name', $tag->name);
        $tag->slug = $request->get('slug', $tag->slug);

        if ($tag->save()) {
            return $this->response($tag);
        }

    }

    /**
     * Delete tag by id
     * @param int $id (required) The tag id.
     * @return \Illuminate\Http\JsonResponse
     */
    function destroy(Request $request)
    {

        if (!$request->id) {
            return $this->error("Missing tag id");
        }

        $tag = Tag::find($request->id);

        if (!$tag) {
            return $this->error("Tag #" . $request->id . " is not exists");
        }

        // Destroy requested post
        $tag->delete();

        return $this->response($tag);

    }


}
