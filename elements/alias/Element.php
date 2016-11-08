<?php

namespace worstinme\zoo\elements\alias;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;

class Element extends \worstinme\zoo\elements\BaseElementBehavior
{
	public function rules($attributes)
	{
		return [
			['alias','string'],
			['alias', 'match', 'pattern' => '#^[\w_-]+$#i'],
		];
	}

	public function events()
	{
		return [
			ActiveRecord::EVENT_BEFORE_VALIDATE => 'createSlug',
		];
	}

    public function getValue($attribute = name) {
        return $this->owner->alias;
    }

    public function setValue($attribute,$value) {
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
		$condition = $this->owner->tablename().'.alias = :out_attribute AND '.$this->owner->tablename().'.lang = :lang';
		$params = [':out_attribute' => $slug, ':lang' => $this->owner->lang];
		if ( !$this->owner->isNewRecord ) {
			$condition .= ' AND '.$this->owner->tablename().'.id != :pk';
			$params[':pk'] = $this->owner->id;
		}

		return !$this->owner->find()
			->where( $condition, $params )
			->one();
	}

}