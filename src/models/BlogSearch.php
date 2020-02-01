<?php

namespace itshkacomua\blog\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BlogSearch represents the model behind the search form of `common\models\Blog`.
 */
class BlogSearch extends Blog
{
    public $tag_name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status_id', 'sort'], 'integer'],
            [['title', 'text', 'alias', 'tag_name'], 'safe'],
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
        $query = Blog::find()->joinWith('tags');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
                /*'attributes' => [
                    'tag_name' => [
                        'asc' => ['first_name' => SORT_ASC],
                        'desc' => ['first_name' => SORT_DESC],
                        'default' => SORT_DESC,
                        'label' => 'Name',
                    ]
                ],*/
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status_id' => $this->status_id,
            'sort' => $this->sort,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'tag.name', $this->tag_name])
            ->andFilterWhere(['like', 'alias', $this->alias]);

        return $dataProvider;
    }
}
