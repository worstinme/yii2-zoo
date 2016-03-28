<?php

namespace worstinme\zoo\elements\slug;

use Yii;
use ArrayObject;
use yii\db\ActiveRecord;
use yii\validators\RequiredValidator;
use yii\validators\Validator;
use yii\helpers\Inflector;
use dosamigos\helpers\TransliteratorHelper;

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
		$d = [];
        $str = explode(" ",$slug);
        if (count($str)) {
            foreach ($str as $s) {
                $d[] = $this->transliteration($s);
            }
        }
        return implode('-',$d);
	}
	
	private function checkUniqueSlug( $slug )
	{
		$pk = $this->owner->primaryKey();
		$pk = $pk[0];

		$condition = 'alias = :out_attribute';
		$params = [ ':out_attribute' => $slug ];
		if ( !$this->owner->isNewRecord ) {
			$condition .= ' and ' . $pk . ' != :pk';
			$params[':pk'] = $this->owner->{$pk};
		}

		return !$this->owner->find()
			->where( $condition, $params )
			->one();
	}

	public static function transliteration($str)
    {
        // ГОСТ 7.79B
        $transliteration = array(
            'А' => 'A', 'а' => 'a',
            'Б' => 'B', 'б' => 'b',
            'В' => 'V', 'в' => 'v',
            'Г' => 'G', 'г' => 'g',
            'Д' => 'D', 'д' => 'd',
            'Е' => 'E', 'е' => 'e',
            'Ё' => 'Yo', 'ё' => 'yo',
            'Ж' => 'Zh', 'ж' => 'zh',
            'З' => 'Z', 'з' => 'z',
            'И' => 'I', 'и' => 'i',
            'Й' => 'J', 'й' => 'j',
            'К' => 'K', 'к' => 'k',
            'Л' => 'L', 'л' => 'l',
            'М' => 'M', 'м' => 'm',
            'Н' => "N", 'н' => 'n',
            'О' => 'O', 'о' => 'o',
            'П' => 'P', 'п' => 'p',
            'Р' => 'R', 'р' => 'r',
            'С' => 'S', 'с' => 's',
            'Т' => 'T', 'т' => 't',
            'У' => 'U', 'у' => 'u',
            'Ф' => 'F', 'ф' => 'f',
            'Х' => 'H', 'х' => 'h',
            'Ц' => 'Cz', 'ц' => 'cz',
            'Ч' => 'Ch', 'ч' => 'ch',
            'Ш' => 'Sh', 'ш' => 'sh',
            'Щ' => 'Shh', 'щ' => 'shh',
            'Ъ' => 'ʺ', 'ъ' => 'ʺ',
            'Ы' => 'Y`', 'ы' => 'y`',
            'Ь' => '', 'ь' => '',
            'Э' => 'E`', 'э' => 'e`',
            'Ю' => 'Yu', 'ю' => 'yu',
            'Я' => 'Ya', 'я' => 'ya',
            '№' => '#', 'Ӏ' => '‡',
            '’' => '`', 'ˮ' => '¨',
        );

        $str = strtr($str, $transliteration);
        $str = mb_strtolower($str, 'UTF-8');
        $str = preg_replace('/[^0-9a-z\-]/', '', $str);
        $str = preg_replace('|([-]+)|s', '-', $str);
        $str = trim($str, '-');

        return $str;
    }

}