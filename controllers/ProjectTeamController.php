<?php

namespace app\controllers;

use app\models\User;
use app\models\ProjectTeam;
use app\models\ProjectTeamSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProjectTeamController implements the CRUD actions for ProjectTeam model.
 */
class ProjectTeamController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all ProjectTeam models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProjectTeamSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProjectTeam model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ProjectTeam model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($projectId)
    {
        $model = new ProjectTeam();
        $model->type = ProjectTeam::TYPE_MEMBER;
        $model->project_id = $projectId;

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['project/view', 'id' => $model->project_id, 'tab' => 'team']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('form', [
            'model' => $model,
            'users' => User::getList(),
        ]);
    }

    /**
     * Updates an existing ProjectTeam model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['project/view', 'id' => $model->project_id, 'tab' => 'team']);
        }

        return $this->render('form', [
            'model' => $model,
            'users' => User::getList(),
        ]);
    }

    /**
     * Deletes an existing ProjectTeam model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        return $this->redirect(['project/view', 'id' => $model->project_id, 'tab' => 'team']);
    }

    /**
     * Finds the ProjectTeam model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ProjectTeam the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProjectTeam::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Такого члена команды не существует.');
    }
}
