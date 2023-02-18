<?php

namespace app\controllers;

use app\models\ProjectResult;
use app\models\User;
use app\models\ProjectResultSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Yii;

/**
 * ProjectResultController implements the CRUD actions for ProjectResult model.
 */
class ProjectResultController extends Controller
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
     * Updates an existing ProjectResult model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if(!$model->project->canEdit($this->user)) {
            throw new ForbiddenHttpException('Вы не можете изменять результат этого проекта!');
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['project/view', 'id' => $model->project_id]);
        }

        return $this->render('form', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the ProjectResult model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ProjectResult the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProjectResult::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Нет такого результата проекта.');
    }
}
