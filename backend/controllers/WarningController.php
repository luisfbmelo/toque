<?php


namespace backend\controllers;

use Yii;
use common\models\Repair;
use common\models\Client;
use common\models\Brands;
use common\models\Equipaments;
use common\models\Models;
use common\models\Stores;
use common\models\RepairType;
use common\models\Inventory;
use common\models\EquipBrand;
use common\models\Accessories;
use common\models\RepairAccessory;
use common\models\User;
use common\models\Status;
use common\models\Parts;
use common\models\RepairParts;
use common\models\LoginForm;
use common\models\Groups;

use common\models\SearchRepair;


use yii\filters\AccessControl;
use yii\web\Controller;

use yii\filters\VerbFilter;

class WarningController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],            
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Set the latest action origin in order to set the correct track
     * @param  [type] $event  [description]
     * @param  [type] $result [description]
     * @return [type]         [description]
     */
    public function afterAction($event, $result)
    {
         
        if (\Yii::$app->session->get('lastAction')!="update" && \Yii::$app->session->get('lastAction')!="view" && \Yii::$app->session->get('lastAction')!="create" && Yii::$app->controller->action->id!="update" && \Yii::$app->controller->action->id!="view" && Yii::$app->controller->action->id!="create"){
            \Yii::$app->session->set('lastAction',Yii::$app->controller->action->id);
        }
        
        return $result;

    }
    
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionWarranty(){

    	$viewType = "warranty";

    	$searchModel = new SearchRepair();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $viewType);
        
        return $this->render('warranty', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionPickup(){

    	$viewType = "topickup";

    	$searchModel = new SearchRepair();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $viewType);
        
        return $this->render('pickup', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Checks if the current route matches with given routes
     * @param array $routes
     * @return bool
     */
    public function isActive($routes = array())
    {
    	
        if (in_array('warranty',$routes) && Yii::$app->controller->action->id == "warranty"){
            return "activeTop";
        }else if (in_array('topickup',$routes) && Yii::$app->controller->action->id == "pickup"){
            return "activeTop";
        }
        
    }

}
