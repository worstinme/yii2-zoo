<?php

namespace worstinme\zoo\elements\system\alias;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;

class Behavior extends \worstinme\zoo\elements\BaseElementBehavior
{
	public function rules()
	{
		return [
			[$this->attribute,'string'],
			[$this->attribute, 'match', 'pattern' => '#^[\w_-]+$#i'],
		];
	}

	public function events()
	{
		return [
			ActiveRecord::EVENT_BEFORE_VALIDATE => 'createSlug',
		];
	}

    public function setValue($value) {
        return $this->owner->alias = $value;
    }

    public function createSlug()
    {
       	if (empty($this->owner->alias)) {
        	$this->owner->alias = $this->generateSlug($this->owner->name);
        } else {
			$this->owner->alias = $this->generateSlug($this->owner->alias);
		}
    }

    private function generateSlug( $slug )
	{
		$slug = $this->slugify( $slug );
		if ( $this->checkUniqueSlug( $slug ) ) {
			return $slug;
		} else {
			for ( $suffix = 2; !$this->checkUniqueSlug( $new_slug = $slug . '-' . $suffix ); $suffix++ ) {}
			return $new_slug;
		}
	}

    private function slugify( $slug )
	{
        return Inflector::slug($slug);
	}

    private function checkUniqueSlug( $slug )
    {
        $condition = $this->owner->tablename().'.alias = :out_attribute AND '.$this->owner->tablename().'.lang = :lang AND '.$this->owner->tablename().'.app_id = :app';
        $params = [':out_attribute' => $slug, ':lang' => $this->owner->lang, ':app' => $this->owner->app_id];
        if ( !$this->owner->isNewRecord ) {
            $condition .= ' AND '.$this->owner->tablename().'.id != :pk';
            $params[':pk'] = $this->owner->id;
        }

        return !$this->owner->find()
            ->where( $condition, $params )
            ->one();
    }

}
