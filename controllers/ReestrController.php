<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\models\Reestr;
use app\models\ReestrSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;


/**
 * ReestrController implements the CRUD actions for Reestr model.
 */
class ReestrController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                	[                		
                		'allow' => true,
                		'roles' => ['@'],
                	],                	
                ],
            ],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
                	'delete' => ['POST'],
                ],
			],
        ];
    }

    /**
     * Lists all Reestr models.
     * @return mixed
     */
    public function actionIndex($report=null)
    {
        $searchModel = new ReestrSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
	
        if ($report!==null)
        {
        	ob_end_clean();
        	
        	header('Content-Type: application/vnd.ms-excel');
        	header('Content-Disposition: attachment;filename="DM_'.date('d.m.Y').'.xls"');
        	header('Cache-Control: max-age=0');
        	echo $this->renderPartial('index', [
	            'searchModel' => $searchModel,
	            'dataProvider' => $dataProvider,
	        ]);
        	
        	\Yii::$app->end();
        	
        }        
       
       return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Reestr model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Reestr model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Reestr();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	        	
        	$model->documentFiles = UploadedFile::getInstances($model, 'documentFiles');
        	
        	if (!$model->upload())
	        	return $this->render('create', [
	        		'model'=>$model,
	        	]);
        	
	        return $this->redirect(['view', 'id' => $model->id]);        	
	        
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Reestr model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	
        	$model->documentFiles = UploadedFile::getInstances($model, 'documentFiles');
        	
        	// отметка об удалении файлов
        	$model->fileDelete();
        	
        	if (!$model->upload())
        		return $this->render('update', [
        			'model'=>$model,
        		]);
        		
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Reestr model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
    	
    	$model = $this->findModel($id);
    	
    	// отметка об удалении файлов
    	$model->fileDelete(true);
    	
        $model->delete();

        return $this->redirect(['index']);
    }
    
    
    /**
     * Экспорт в Excel
     * @author oleg
     * @version 04.10.2017
     */
    public function actionExcel()
    {
        
        $model = new Reestr();
        
        require_once '../vendor/phpoffice/phpexcel/PHPExcel.php';
        require_once '../vendor/phpoffice/phpexcel/PHPExcel/IOFactory.php';
        
        $objPHPExcel = new \PHPExcel();
        
        
        // заголовки
        $columns = [
            '#',
            'code_no',
            'org_inspection',
            'period_inspection',
            'year_inspection',
            'data_akt_ref',
            'theme_inspection',
            'question_inspection',
            'violations',
            'answers_no',
            'mark_elimination_violation',
            'measures',
            'description',
            'listFilesExcel',
            //'date_create',
            //'date_edit',
        ];
        
        for ($i=0; $i<count($columns); $i++)
        {
            if ($i==0)
            {
                $objPHPExcel->setActiveSheetIndex()
                    ->setCellValueByColumnAndRow($i, 1, $columns[$i]);
            }
            else
            {
                $objPHPExcel->setActiveSheetIndex()
                    ->setCellValueByColumnAndRow($i, 1, $model->attributeLabels()[$columns[$i]]);
            }
        }
        
        
        // данные из модели
        $row = 2;
        foreach ($model->find()->all() as $data)
        {            
            for ($i=0; $i<count($columns); $i++)
            {        
                if ($i==0)
                {
                    $objPHPExcel->setActiveSheetIndex()
                        ->setCellValueByColumnAndRow($i, $row, $row-1);
                }
                else
                {
                    $objPHPExcel->setActiveSheetIndex()
                        ->setCellValueByColumnAndRow($i, $row, $data[$columns[$i]]);
                }
            }
            $row++;
        }
        
        
        $cell = 'A1:' . \PHPExcel_Cell::stringFromColumnIndex(count($columns)-1) . '1';
        
        $objPHPExcel->setActiveSheetIndex()->getStyle($cell)->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex()->getStyle($cell)->getAlignment()->setWrapText(true);
        $objPHPExcel->setActiveSheetIndex()->setAutoFilter($cell);
        $objPHPExcel->setActiveSheetIndex()->getColumnDimension('A')->setWidth(3);
        $objPHPExcel->setActiveSheetIndex()->getColumnDimension('C')->setWidth(50);
        $objPHPExcel->setActiveSheetIndex()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex()->getColumnDimension('F')->setWidth(40);
        $objPHPExcel->setActiveSheetIndex()->getColumnDimension('G')->setWidth(40);
        $objPHPExcel->setActiveSheetIndex()->getColumnDimension('H')->setWidth(40);
        $objPHPExcel->setActiveSheetIndex()->getColumnDimension('I')->setWidth(40);
        $objPHPExcel->setActiveSheetIndex()->getColumnDimension('J')->setWidth(40);
        $objPHPExcel->setActiveSheetIndex()->getColumnDimension('K')->setWidth(40);
        $objPHPExcel->setActiveSheetIndex()->getColumnDimension('L')->setWidth(40);
        $objPHPExcel->setActiveSheetIndex()->getColumnDimension('M')->setWidth(70);
        $objPHPExcel->setActiveSheetIndex()->getColumnDimension('N')->setWidth(70);
                
        
        // Rename worksheet
        //$objPHPExcel->getActiveSheet()->setTitle('Simple');
        
        
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        
        
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="export_reestr.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        
        // If you're serving to IE over SSL, then the following may be needed
        //header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
        
        
    }
    
    
    /**
     * Список тем
     * @return string
     */
    public function actionTheme()
    {
        return  $this->renderPartial('listDialog', [
            'list'=>[
                'налоговое законодательство',
                'административные правонарушения',
                'трудовое законодательство',
                'законодательство по госзакупкам',
                'внебюджетные фонды',
                'воинский учет',
                'государственная  и муниципальная собственность',
                'противодействие коррупции',
                'безопасность',
            ],
            'inputId'=>'#input-theme',
        ]);
    }
    
    /**
     * Список для поля "Вид проверяющей организации"
     * @return string
     */
    public function actionCheckorganization()
    {
        return $this->renderPartial('listDialog', [
            'list'=>[
                'Прокуратура' => 'Прокуратура',
                'МЧС России' => 'МЧС России',
                'Финансово-бюджетный надзор' => 'Финансово-бюджетный надзор',
                'Внебюджетные фонды' => 'Внебюджетные фонды',
                'Военный комиссариат' => 'Военный комиссариат',
                'Государственная инспекция труда' => 'Государственная инспекция труда',
                'Следственный комитет' => 'Следственный комитет',
                'Территориальное управление федерального агентства  по управлению государственным имуществом' => 'Территориальное управление федерального агентства  по управлению государственным имуществом',
                'Государственный автодорожный надзор' => 'Государственный автодорожный надзор',
                'Федеральная антимонопольная служба' => 'Федеральная антимонопольная служба',                
            ],
            'inputId'=>'#input-check-organization',
        ]);
    }
    

    /**
     * Finds the Reestr model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Reestr the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Reestr::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
