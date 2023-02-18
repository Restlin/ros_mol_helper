<?php

namespace app\controllers;

use app\models\Notice;
use app\models\NoticeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use Yii;


/**
 * NoticeController implements the CRUD actions for Notice model.
 */
class NoticeController extends Controller
{
    private ?User $user;

    public function __construct($id, $module, $config = []) {
        $this->user = Yii::$app->user->isGuest ? null : Yii::$app->user->getIdentity()->user;
        parent::__construct($id, $module, $config);
    }
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Notice models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new NoticeSearch();
        $searchModel->user_id = Yii::$app->user->id;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Notice model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $model->is_viewed = true;
        $model->save();
        $this->redirect(['project/view', 'id' => $model->project_id]);        
    }    

    /**
     * Finds the Notice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Notice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Notice::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Такого уведомления не существует!');
    }
}
