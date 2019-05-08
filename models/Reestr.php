<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Html;

/**
 * This is the model class for table "reestr".
 *
 * @property integer $id
 * @property string $code_no
 * @property string $org_inspection
 * @property string $period_inspection
 * @property string $year_inspection
 * @property string $data_akt_ref
 * @property string $theme_inspection
 * @property string $question_inspection
 * @property string $violations
 * @property string $answers_no
 * @property string $mark_elimination_violation
 * @property string $measures
 * @property string $description
 * @property string $date_create
 * @property string $date_edit
 * @property string $log_change
 * @property string $type_check_organization
 *
 * @property Files[] $files
 * @property Ifns $codeNo
 */
class Reestr extends \yii\db\ActiveRecord
{
	
	
	public $documentFiles;
	public $documentFilesDelete = array();
	public $errorsLoadFiles;
	
	
	// order field
	public $sort1 = null;
	public $sort2 = null;
	public $sort3 = null;
	public $sort4 = null;
	public $sort5 = null;
	
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reestr';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code_no', 'org_inspection', 'period_inspection', 'year_inspection'], 'required'],
            [['code_no', 'org_inspection', 'period_inspection', 'theme_inspection', 'question_inspection', 
            	'violations', 'answers_no', 'mark_elimination_violation', 'measures', 'description',             		
            	'log_change', 'year_inspection', 'data_akt_ref', 'type_check_organization'], 'string'],
            //[['year_inspection'], 'number'],
            [['date_create', 'date_edit', 'documentFilesDelete'], 'safe'],
            [['code_no'], 'exist', 'skipOnError' => true, 
            	'targetClass' => Ifns::className(), 'targetAttribute' => ['code_no' => 'code_no']],
        	[['documentFiles'], 'file', 'maxFiles' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'code_no' => 'Код проверяемого налогового органа',
            'org_inspection' => 'Наименование проверяющей организации',
            'period_inspection' => 'Период проведения проверки',
            'year_inspection' => 'Проверяемый период',
        	'data_akt_ref' => 'Данные о представлениях, предписаниях, актах, справках (дата и номер документа)',
            'theme_inspection' => 'Тема проверки',
            'question_inspection' => 'Перечень проверенных вопросов',
            'violations' => 'Выявленные нарушения и недостатки (либо признак "Нарушения не установлены")',
            'answers_no' => 'Ответы Инспекции в адрес проверяющих органов по устранению нарушений (дата и номер  документа)',
            'mark_elimination_violation' => 'Отметка об устранении нарушений (устранено, не устранено, частично устранено, неустранимое)',
            'measures' => 'Принятые меры к должностным лицам по итогам проведенной проверки',
            'description' => 'Примечание',
            'date_create' => 'Дата создания',
            'date_edit' => 'Дата изменения',
            'log_change' => 'Журнал изменений',
        	'documentFiles' => 'Файлы',
        	'documentFilesDelete' => 'Загруженные файлы',
            'type_check_organization' => 'Вид проверяющей организации',
            'listFiles' => 'Материалы проверки',
            'listFilesExcel' => 'Материалы проверки',
            
            'sort1'=>'Поле 1',
            'sort2'=>'Поле 2',
            'sort3'=>'Поле 3',
            'sort4'=>'Поле 4',
            'sort5'=>'Поле 5',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(Files::className(), ['id_reestr' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodeNo()
    {
        return $this->hasOne(Ifns::className(), ['code_no' => 'code_no']);
    }

    /**
     * @inheritdoc
     * @return ReestrQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ReestrQuery(get_called_class());
    }
    
    
    /**
     * Загрузка файлов на сервер
     * @return boolean
     * 
     * @author oleg
     */
    public function upload()
    {
    	
    	$this->errorsLoadFiles = [];
    	
    	if ($this->validate())
    	{    		
    		
    		$filePath = \Yii::$app->basePath . '\web\files\\';
    		
    		// проверка каталога/создание каталога
    		if (!file_exists($filePath . $this->id))
    		{
    			if (!mkdir($filePath . $this->id)) 
    			{
    				$this->errorsLoadFiles[] = 'Не удалось создать каталог "' . $filePath . '\\' . $this->id . '"!';
    				return false;
    			}
    		}
    		
    		if (!count($this->documentFiles)) 
    			return true;
    		
    		// обоработка всех файлов
    		foreach ($this->documentFiles as $file)
    		{
    			    			
    			$fileName = $filePath . $this->id . '\\' . $file->baseName . '.' . $file->extension;
    			
    			if (file_exists(iconv('utf-8', 'windows-1251', $fileName)))
    			{
    				$this->errorsLoadFiles[] = 'Файл ' . $file->baseName . '.' . $file->extension . ' уже существует!';
    				continue;
    			}
    			    			
    			if (!$file->saveAs($filePath . $this->id . '\\' . iconv('utf-8', 'windows-1251', $file->baseName) . '.' . $file->extension))
    			{
    				$this->errorsLoadFiles[] = 'Произошла ошибка "' . $file->error . '" при сохранении файла ' . $file->baseName . '.' . $file->extension;
    				continue;
    			}
    			
    			// сохранение файлов в каталоге
    			Yii::$app->db->createCommand()->insert('files', [
    				'id_reestr'=>$this->id,
    				'name_file' => $file->baseName . '.' . $file->extension,
    			])->execute();
    		}
    		
    		
    		return true;
    	}
    	
    	return false;    	
    }
    
    /**
     * Удаление файлов из реестра
     * Файл будет удален из каталога и базы данных
     *  
     * @param $allFiles boolean - Флаг указывающий о необходимости удалить все файлы (используется в контроллере reestr/delete)
     * @return boolean
     * 
     * 
     * @author oleg
     * 	
     */
    public function fileDelete($allFiles=false)
    {
    	
    	if ($allFiles)
    	{
    		$this->documentFilesDelete = ArrayHelper::map(Files::find()->where('id_reestr=:id_reestr', 
    			[':id_reestr'=>$this->id])->asArray()->all(), 'id', 'id');
    	}
    	
    	
    	$filePath = \Yii::$app->basePath . '\web\files\\';
    	    	
    	if (is_array($this->documentFilesDelete) && count($this->documentFilesDelete)>0)
    	{
    		foreach ($this->documentFilesDelete as $id)
    		{
    			$fileModel = Files::findOne($id);
    			if ($fileModel === null) continue;
    			    		
    			$flagError = true;
    			
    			// удалить файл
    			if (file_exists($filePath . $this->id . '\\' . iconv('utf-8', 'windows-1251', $fileModel->name_file)))
    				$flagError = @unlink($filePath . $this->id . '\\' . iconv('utf-8', 'windows-1251', $fileModel->name_file));
    			
    			// удалить запись из БД
    			if ($flagError)
    			{
    				$fileModel->delete();
    			}
    		}
    	}
    }
    
    
    /**
     * Событие перед сохранение в БД
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert)
    {	
    	$this->log_change = $this->log_change . $this->currentLogChange();
    	return parent::beforeSave($insert);
    }
    
    
    /**
     * Получение информации о дате, текущем пользователе 
     * 		и произведенных изменениях
     * @return string
     */
    private function currentLogChange()
    {
    	return date('d.m.Y h:i:s') . ' ' .
    		(isset(Yii::$app->user->identity->isGuest) ? 'Guest' : Yii::$app->user->identity->username) . ' ' .
    		($this->isNewRecord ? 'создание' : 'изменение') . '<br />'; 
    }
    
    
    /**
     * Список файлов приложенных к текущей записи
     * @return string
     */
    public function getListFiles()
    {       
        return Files::filesByReestrId($this->id);
    }
    
    
    public function getListFilesExcel()
    {
        return Files::filesByReestrId($this->id, false, false);
    }
    
}
