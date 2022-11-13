<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
//use yii\behaviors\SluggableBehavior;
use common\components\VusSluggableBehavior;
use yii\helpers\Url;
use Yii\helpers\ArrayHelper;
use yii\db\Expression;

/**
 * This is the model class for table "{{%post}}".
 *
 * @property int $id
 * @property string|null $type
 * @property string $title
 * @property string $slug
 * @property string|null $excerpt
 * @property string|null $description
 * @property int $created_at
 * @property int|null $updated_at
 * @property int|null $published_at
 * @property int|null $user_id
 * @property string|null $author
 * @property int $status
 *
 * @property User $user
 * @property PostCategory[] $postCategories
 * @property Category[] $cats
 * @property PostMedia[] $postMedia
 * @property PostMeta[] $postMetas
 */
class Post extends \yii\db\ActiveRecord
{
    public $category_ids; //defining virtual attribute
    public $media_ids; //defining virtual attribute
    public $meta; //defining virtual attribute
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%post}}';
    }

    /**
     * get type page
     * @return string type
     */
    public static function getTypes()
    {
        static $types;
        if($types) return $types;

        $types = [
            'post' => [
                'name' => 'post',
                'label' => Yii::t('app', 'Post'),
                'category' => ['category'],
                'attributes' => [
                ],
            ],
        ];
        if(isset(Yii::$app->params['post_type']))
        {
            $types = ArrayHelper::merge($types, Yii::$app->params['post_type']);
        }
        return $types;
    }

    public function getTypeObj()
    {
        if($this->type && isset(self::getTypes()[$this->type]))
            return (object) self::getTypes()[$this->type];

        return false;
    }

    public function getCategoryTypes($type = '')
    {
        if(!$type) $type = $this->type;
        $catTypeAll = Category::getTypes();
        $types = self::getTypes();
        $categoryTypes = [];
        if(in_array($type, array_keys($types))
            && (isset($types[$type]['category']) && $types[$type]['category']))
        {
            foreach($types[$type]['category'] as $cat) {
                if(in_array($cat, array_keys($catTypeAll)))
                $categoryTypes[] = (object) $catTypeAll[$cat];
            }
        }
        return $categoryTypes;
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                /* Save with [integer] timestamp
                 * Remove comment for save datetime format
                 */
                // 'value' => new Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => 'user_id',
            ],
            [
                'class' => VusSluggableBehavior::className(),
                'attribute' => 'title',
                'slugAttribute' => 'slug',
                'immutable' => true,
                'ensureUnique' => true,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['user_id', 'status', 'created_at', 'updated_at', 'published_at'], 'integer'],
            [['excerpt', 'description'], 'string'],
            [['type'], 'string', 'max' => 100],
            [['title', 'slug', 'author'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            ['category_ids', 'each', 'rule' => [
                    'exist', 'targetClass' => Category::className(), 'targetAttribute' => 'id'
                ]
            ],
            ['media_ids', 'each', 'rule' => [
                    'exist', 'targetClass' => Media::className(), 'targetAttribute' => 'id'
                ]
            ],
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
            'type' => Yii::t('app', 'Type'),
            'title' => Yii::t('app', 'Title'),
            'slug' => Yii::t('app', 'Slug'),
            'excerpt' => Yii::t('app', 'Excerpt'),
            'description' => Yii::t('app', 'Description'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'published_at' => Yii::t('app', 'Published At'),
            'user_id' => Yii::t('app', 'User'),
            'author' => Yii::t('app', 'Author'),
            'status' => Yii::t('app', 'Status'),
            'category_ids' => Yii::t('app', 'Category'),
            'media_ids' => Yii::t('app', 'Image'),
            'meta' => Yii::t('app', 'Meta Data'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostCategories()
    {
        return $this->hasMany(PostCategory::className(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCats()
    {
        return $this->hasMany(Category::className(), ['id' => 'cat_id'])->viaTable('{{%post_category}}', ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostMedia()
    {
        return $this->hasMany(PostMedia::className(), ['post_id' => 'id'])
                    ->orderBy(['type' => SORT_ASC, 'is_featured' => SORT_DESC, 'order' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasMany(Media::className(), ['id' => 'media_id'])->viaTable('{{%post_media}}', ['post_id' => 'id'])
            ->joinWith('postMedia')->orderBy(['type' => SORT_ASC, 'is_featured' => SORT_DESC, 'order' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImageFeatured()
    {
        return $this->hasOne(Media::className(), ['id' => 'media_id'])->viaTable('{{%post_media}}', ['post_id' => 'id'], 
            function($query){
                /* @var $query \yii\db\ActiveQuery */
                $query->andWhere(['is_featured' => 1]);
            }
        );
    }

    /**
     * Gets query for [[PostMetas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPostMetas()
    {
        return $this->hasMany(PostMeta::className(), ['post_id' => 'id']);
    }

    public function getHashid()
    {
        return Yii::$app->Hashids->encode(intval($this->id));
    }

    public function getUrl()
    {
        return Url::to(['post/view', 'id' => $this->hashid, 'slug' => $this->slug, 'type' => $this->type]);
    }
}
