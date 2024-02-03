<?php

namespace Benaaacademy\Tags\Models;

use Benaaacademy\Platform\Model;

/**
 * Class Tag
 * @package Benaaacademy\Tags\Models
 */
class Tag extends Model
{

    /**
     * @var string
     */
    protected $table = "tags";

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $searchable = ['name'];

    /**
     * @var int
     */
    protected $perPage = 20;

    /**
     * @var array
     */
    protected $sluggable = [
        'slug' => 'name',
    ];

    /**
     * @var array
     */
    protected $creatingRules = [
        "name" => "required|unique:tags,name,[id],id"
    ];

    /**
     * @var array
     */
    protected $updatingRules = [
        "name" => "required|unique:tags,name"
    ];

    /**
     * Save multiple tag names
     * @param array $names
     * @return array of tag ids
     */
    public static function saveNames($names = [])
    {

        $tag_ids = [];

        $names = array_unique($names);

        foreach ($names as $name) {
            $tag = self::select("id")->where("name", $name)->first();

            if ($tag) {

                // Tag exists

                $tag_ids[] = $tag->id;

            } else {

                // Create new tag

                $tag = new Tag();
                $tag->name = $name;
                if ($tag->validate()) {
                    $tag->save();
                    $tag_ids[] = $tag->id;
                }
            }
        }

        return $tag_ids;
    }

    /**
     * @param $v
     * @return mixed
     */
    function setValidation($v)
    {
        $v->setCustomMessages((array)trans('tags::validation'));
        $v->setAttributeNames((array)trans("tags::tags.attributes"));
        return $v;
    }

    /**
     * Count setters
     * @param $value
     */
    function setCountAttribute($value)
    {
        $this->attributes["count"] = 0;
    }

}
