<?php


namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;



class ReportController extends Controller
{
	
	

	private $_codeIfns;
	
	
	
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
                	[
						'actions' => ['login', 'error'],
                		'allow' => true,
                		'roles' => ['?'],
                	],
                	
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    
    
    
    
    



	/**
	 *
	 *
	 * @author Трусов Олег Алексеевич <trusov@r86.nalog.ru>
	 * */
	public function actionIndex()
	{
		
	}


	


}

?>