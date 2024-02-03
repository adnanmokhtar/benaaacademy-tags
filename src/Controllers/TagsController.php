<?php

namespace Benaaacademy\Tags\Controllers;

use Action;
use Auth;
use Benaaacademy\Platform\Controller;
use Benaaacademy\Tags\Models\Tag;
use Redirect;
use Request;
use View;

/**
 * Class TagsController
 * @package Benaaacademy\Tags\Controllers
 */
class TagsController extends Controller
{

    /**
     * View payload
     * @var array
     */
    protected $data = [];


    /**
     * Show all tags
     * @return mixed
     */
    function index()
    {

        if (Request::isMethod("post")) {
            if (Request::filled("action")) {
                switch (Request::get("action")) {
                    case "delete":
                        return $this->delete();
                }
            }
        }

        $this->data["sort"] = $sort = (Request::filled("sort")) ? Request::get("sort") : "id";
        $this->data["order"] = $order = (Request::filled("order")) ? Request::get("order") : "DESC";
        $this->data['per_page'] = (Request::filled("per_page")) ? (int)Request::get("per_page") : 40;

        $query = Tag::orderBy($this->data["sort"], $this->data["order"]);

        if (Request::filled("q")) {
            $query->search(Request::get("q"));
        }

        $tags = $query->paginate($this->data['per_page']);

        $this->data["tags"] = $tags;

        return View::make("tags::show", $this->data);
    }

    /**
     * Delete tag by id
     * @return mixed
     */
    public function delete()
    {
        $ids = Request::get("id");

        $ids = is_array($ids) ? $ids : [$ids];

        foreach ($ids as $ID) {

            $tag = Tag::findOrFail((int)$ID);

            // Fire deleting action

            Action::fire("tag.deleting", $tag);

            $tag->delete();

            // Fire deleted action

            Action::fire("tag.deleted", $tag);
        }

        return Redirect::back()->with("message", trans("tags::tags.events.deleted"));
    }

    /**
     * Create a new tag
     * @return mixed
     */
    public function create()
    {

        if (Request::isMethod("post")) {

            $tag = new Tag();

            $tag->name = Request::get("name");

            // Fire saving action

            Action::fire("tag.saving", $tag);

            if (!$tag->validate()) {
                return Redirect::back()->withErrors($tag->errors())->withInput(Request::all());
            }

            $tag->save();

            // Fire saved action

            Action::fire("tag.saved", $tag);

            return Redirect::route("admin.tags.edit", array("id" => $tag->id))
                ->with("message", trans("tags::tags.events.created"));
        }

        $this->data["tag"] = false;

        return View::make("tags::edit", $this->data);
    }

    /**
     * Edit tag by id
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {

        $tag = Tag::findOrFail((int)$id);

        if (Request::isMethod("post")) {

            $tag->name = Request::get("name");

            // fire saving action

            Action::fire("tag.saving", $tag);

            if (!$tag->validate()) {
                return Redirect::back()->withErrors($tag->errors())->withInput(Request::all());
            }

            $tag->save();

            // fire saved action

            Action::fire("tag.saved", $tag);

            return Redirect::route("admin.tags.edit", array("id" => $id))->with("message", trans("tags::tags.events.updated"));
        }

        $this->data["tag"] = $tag;

        return View::make("tags::edit", $this->data);
    }

    /**
     * Rest service to search tags
     * @return string
     */
    function search()
    {

        $q = trim(urldecode(Request::get("q")));

        $tags = Tag::search($q)->get()->toArray();

        return json_encode($tags);
    }
}
