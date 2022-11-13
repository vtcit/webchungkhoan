<?php

namespace common\models;

use Yii;
// use yii\behaviors\SluggableBehavior;
use common\components\VusSluggableBehavior;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\db\Expression;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property int $id
 * @property string $title
 * @property string $type
 * @property int|null $parent_id
 * @property string $slug
 * @property string|null $description
 * @property string|null $meta
 *
 * @property Category $parent
 * @property Category[] $categories
 * @property CategoryMeta[] $categoryMetas
 * @property PostCategory[] $postCategories
 * @property Post[] $posts
 */
class Category extends \yii\db\ActiveRecord
{
    public $meta;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    public static function getTypes()
    {
        static $types;
        if($types) return $types;

        $types = [
            'category' => [
                'name' => 'category',
                'label' => 'Category',
                'post' => ['post'],
            ],
        ];
        if(isset(Yii::$app->params['category_type']))
        {
            $types = ArrayHelper::merge($types, Yii::$app->params['category_type']);
        }
        return $types;
    }

    public function getTypeObj()
    {
        if($this->type && isset(self::getTypes()[$this->type]))
            return (object) self::getTypes()[$this->type];

        return false;
    }

    public function getPostTypes($type = '')
    {
        if(!$type) $type = $this->type;
        $postTypeAll = Post::getTypes();
        $types = self::getTypes();
        $postTypes = [];
        if(in_array($type, array_keys($types))
            && (isset($types[$type]['post']) && $types[$type]['post']))
        {
            foreach($types[$type]['post'] as $postType) {
                if(in_array($postType, array_keys($postTypeAll)))
                $postTypes[] = (object) $postTypeAll[$postType];
            }
        }
        return $postTypes;
    }

    public function behaviors()
    {
        return [
            [
                'class' => VusSluggableBehavior::className(),
                'attribute' => 'title',
                // 'slugAttribute' => 'slug',
                'immutable' => true,
                'ensureUnique' => true,
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['excerpt', 'description'], 'string'],
            [['title', 'slug', 'type'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['parent_id' => 'id']],
            ['meta', 'each', 'rule' => ['safe']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'type' => Yii::t('app', 'Type'),
            'parent_id' => Yii::t('app', 'Parent'),
            'slug' => Yii::t('app', 'Slug'),
            'excerpt' => Yii::t('app', 'Excerpt'),
            'description' => Yii::t('app', 'Description'),
            'meta' => Yii::t('app', 'Meta Data'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Category::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['parent_id' => 'id']);
    }

    /**
     * Gets query for [[CategoryMetas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryMetas()
    {
        return $this->hasMany(CategoryMeta::className(), ['cat_id' => 'id']);
    }

    /**
     * Gets query for [[PostCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPostCategories()
    {
        return $this->hasMany(PostCategory::className(), ['cat_id' => 'id']);
    }

    /**
     * Gets query for [[Posts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['id' => 'post_id'])->viaTable('{{%post_category}}', ['cat_id' => 'id'])->orderBy(['id'=>SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAvailableCats()
    {
        return Category::find()->orderBy(['parent_id' => SORT_ASC, 'title' => SORT_ASC]);
    }

    public function getHashid()
    {
        return Yii::$app->Hashids->encode(intval($this->id));
    }

    public function getUrl()
    {
        return Url::to(['post/category', 'slug' => $this->slug, 'type' => $this->type]);
    }
}
