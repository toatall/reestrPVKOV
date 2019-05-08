<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Reestr;

/**
 * ReestrSearch represents the model behind the search form about `app\models\Reestr`.
 */
class ReestrSearch extends Reestr
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['code_no', 'org_inspection', 'period_inspection', 'theme_inspection', 'question_inspection', 'violations', 'answers_no', 
                'mark_elimination_violation', 'measures', 'description', 'date_create', 'date_edit', 'log_change'], 'safe'],
            [['year_inspection'], 'number'],
            [['sort1', 'sort2', 'sort3', 'sort4', 'sort5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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

        $query = Reestr::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        // order        
        if ($this->sort1!=null)
            $query->orderBy[$this->sort1] = SORT_ASC; 
        if ($this->sort2!=null)
            $query->orderBy[$this->sort2] = SORT_ASC;
        if ($this->sort3!=null)
            $query->orderBy[$this->sort3] = SORT_ASC; 
        if ($this->sort4!=null)
            $query->orderBy[$this->sort4] = SORT_ASC; 
        if ($this->sort5!=null)
            $query->orderBy[$this->sort5] = SORT_ASC; 

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'year_inspection' => $this->year_inspection,
            'date_create' => $this->date_create,
            'date_edit' => $this->date_edit,
        ]);

        $query->andFilterWhere(['like', 'code_no', $this->code_no])
            ->andFilterWhere(['like', 'org_inspection', $this->org_inspection])
            ->andFilterWhere(['like', 'period_inspection', $this->period_inspection])
            ->andFilterWhere(['like', 'theme_inspection', $this->theme_inspection])
            ->andFilterWhere(['like', 'question_inspection', $this->question_inspection])
            ->andFilterWhere(['like', 'violations', $this->violations])
            ->andFilterWhere(['like', 'answers_no', $this->answers_no])
            ->andFilterWhere(['like', 'mark_elimination_violation', $this->mark_elimination_violation])
            ->andFilterWhere(['like', 'measures', $this->measures])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'log_change', $this->log_change]);

        return $dataProvider;
    }
}
