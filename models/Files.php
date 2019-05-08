<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "files".
 *
 * @property integer $id
 * @property integer $id_reestr
 * @property string $name_file
 * @property string $date_create
 * @property string $date_edit
 * @property string $log_change
 *
 * @property Reestr $idReestr
 */
class Files extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_reestr', 'name_file'], 'required'],
            [['id_reestr'], 'integer'],
            [['name_file', 'log_change'], 'string'],
            [['date_create', 'date_edit'], 'safe'],
            [['id_reestr'], 'exist', 'skipOnError' => true, 'targetClass' => Reestr::className(), 'targetAttribute' => ['id_reestr' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_reestr' => 'Id Reestr',
            'name_file' => 'Name File',
            'date_create' => 'Date Create',
            'date_edit' => 'Date Edit',
            'log_change' => 'Log Change',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdReestr()
    {
        return $this->hasOne(Reestr::className(), ['id' => 'id_reestr']);
    }

    /**
     * @inheritdoc
     * @return FilesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FilesQuery(get_called_class());
    }
    
    
    /**
     * Поиск файлов по id_reestr и передача в виде списка
     * @param int $id_reestr
     * @param string $useLinks
     * @return string
     * 
     * @author oleg
     */
    public static function filesByReestrId($id_reestr, $useLinks=true, $use_br = true)
    {
    	$resultString = '';
    	
    	$model = self::find()->where('id_reestr=:id_reestr', [':id_reestr'=>$id_reestr])->all();
    	foreach ($model as $m)
    	{
    		if ($useLinks)
    		{
    			$resultString .= Html::a($m->name_file, '/files/' . $id_reestr . '/' . $m->name_file, ['target'=>'_blank']) . ($use_br ? '<br />' : '');    			
    		}
    		else 
    		{
    		    $resultString .= $m->name_file . ($use_br ? '<br />' : '');	
    		}    		
    	}
    	return $resultString;
    }
}
