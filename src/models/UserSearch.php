<?php
namespace sergmoro1\user\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

use common\models\User;

class UserSearch extends User
{
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['id', 'status', 'group'], 'integer'],
            [['username', 'email'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => \Yii::$app->params['recordsPerPage'],
            ],
            'sort' => [
                'defaultOrder' => [
                    'username' => SORT_ASC, 
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);

        // load the search form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        
        // adjust the query by adding the filters
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['status' => $this->status])
            ->andFilterWhere(['group' => $this->group]);

        return $dataProvider;
    }
}
