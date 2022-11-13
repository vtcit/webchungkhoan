<?php

namespace common\models;


use Yii;
//use yii\behaviors\SluggableBehavior;
use common\components\VusSluggableBehavior;
use yii\helpers\Url;
use Yii\helpers\ArrayHelper;
use yii\db\Expression;

/**
 * This is the model class for table "{{%page}}".
 *
 * @property int $id
 * @property string $type
 * @property int $parent_id
 * @property string $title
 * @property string $slug
 * @property string $excerpt
 * @property string $description
 *
 * @property Page $parent
 * @property Page[] $pages
 * @property Url $url
 */
class Page extends \yii\db\ActiveRecord
{
    public $media_ids; //defining virtual attribute
    public $meta; //defining virtual attribute
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%page}}';
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
            'page' => [
                'name' => 'page',
                'label' => 'Page',
            ],
        ];
        if(isset(Yii::$app->params['page_type']))
        {
            $types = ArrayHelper::merge($types, Yii::$app->params['page_type']);
        }
        return $types;
    }

    public function getTypeObj()
    {
        if($this->type && isset(self::getTypes()[$this->type]))
            return (object) self::getTypes()[$this->type];

        return false;
    }

    public function behaviors()
    {
        return [
            [
                'class' => VusSluggableBehavior::className(),
                'attribute' => 'title',
                'slugAttribute' => 'slug',
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
            [['parent_id'], 'integer'],
            [['title'], 'required'],
            [['excerpt', 'description'], 'string'],
            [['title', 'slug'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 100],
            [['slug'], 'unique'],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Page::className(), 'targetAttribute' => ['parent_id' => 'id']],
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
            'parent_id' => Yii::t('app', 'Parent'),
            'type' => Yii::t('app', 'Type'),
            'title' => Yii::t('app', 'Title'),
            'slug' => Yii::t('app', 'Slug'),
            'excerpt' => Yii::t('app', 'Excerpt'),
            'description' => Yii::t('app', 'Description'),
            'media_ids' => Yii::t('app', 'Image'),
            'meta' => Yii::t('app', 'Meta'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Page::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAvailablePages()
    {
        return Page::find()->orderBy(['parent_id' => SORT_ASC, 'title' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageMedia()
    {
        return $this->hasMany(PageMedia::className(), ['page_id' => 'id'])
                    ->orderBy(['type' => SORT_ASC, 'is_featured' => SORT_DESC, 'order' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasMany(Media::className(), ['id' => 'media_id'])->viaTable('{{%page_media}}', ['page_id' => 'id'])
            ->joinWith('pageMedia')->orderBy(['type' => SORT_ASC, 'is_featured' => SORT_DESC, 'order' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImageFeatured()
    {
        return $this->hasOne(Media::className(), ['id' => 'media_id'])->viaTable('{{%page_media}}', ['page_id' => 'id'], 
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
    public function getPageMetas()
    {
        return $this->hasMany(PageMeta::className(), ['page_id' => 'id']);
    }

    public function getHashid()
    {
        return Yii::$app->Hashids->encode(intval($this->id));
    }

    public function getUrl()
    {
        return Url::to(['page/view', 'slug' => $this->slug, 'type' => $this->type]);
    }
}
