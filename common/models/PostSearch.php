<?php

namespace common\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use common\models\Post;

/**
 * PostSearch represents the model behind the search form of `common\models\Post`.
 */
class PostSearch extends Post
{
    /* your calculated attribute */
    public $category;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'status'], 'integer'],
            [['title', 'slug', 'excerpt', 'description', 'created_at', 'updated_at', 'published_at', 'author'], 'safe'],
            ['category', 'each', 'rule' => ['integer']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Post::find()->with(['imageFeatured', 'user', 'postCategories']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            // 'pagination' => [
                // 'pageSize' => 10,
            // ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                    'id' => SORT_DESC, 
                ]
            ],
        ]);

        /**
         * Setup your sorting attributes
         * Note: This is setup before the $this->load($params) 
         * statement below
         */

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');

            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'published_at' => $this->published_at,
            'user_id' => $this->user_id,
            'status' => $this->status,
        ]);

        if(isset($params['category']) && !$this->category)
        {
            $this->category = $params['category'];
        }

        if($this->category)
        {
            $this->category = array_filter($this->category);
            if($this->category)
                $query->joinWith('postCategories pc')->andFilterWhere(['in', 'pc.cat_id', $this->category]);
        }

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'excerpt', $this->excerpt])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'author', $this->author]);


        return $dataProvider;
    }
}
