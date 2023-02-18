<?php

namespace app\controllers;

use app\models\Project;
use app\models\ProjectTeam;
use app\models\ProjectSearch;
use app\models\ProjectTeamSearch;
use app\models\ProjectResultSearch;
use app\models\TaskSearch;
use app\models\EventSearch;
use app\models\Publication;
use app\models\PublicationSearch;
use app\models\LogSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends Controller
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
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Project models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'levels' => Project::getLevelList(),
            'statuses' => Project::getStatusList(),
        ]);
    }

    /**
     * Displays a single Project model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $tab = 'team')
    {
        $model = $this->findModel($id);        
        return $this->render('view', [
            'model' => $model,
            'statuses' => Project::getStatusList(),
            'tab' => $tab,
            'levels' => Project::getLevelList(),
            'teamIndex' => $this->renderTeamIndex($model),
            'resultIndex' => $this->renderResultIndex($model),
            'taskIndex' => $this->renderTaskIndex($model),
            'eventIndex' => $this->renderEventIndex($model),
            'publicationIndex' => $this->renderPublicationIndex($model),
            'logIndex' => $this->renderLogIndex($model),
        ]);
    }

    /**
     * Получить команду проекта
     *
     * @return string
     */
    private function renderTeamIndex(Project $model)
    {
        $searchModel = new ProjectTeamSearch();
        $searchModel->project_id = $model->id;
        $searchModel->type = ProjectTeam::TYPE_MEMBER;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->renderPartial('/project-team/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Получить результат проекта
     * @return string
     */
    private function renderResultIndex(Project $model)
    {
        $searchModel = new ProjectResultSearch();
        $searchModel->project_id = $model->id;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->renderPartial('/project-result/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Отобразить список задач
     * @param Project $model проект
     * @return string
     */
    private function renderTaskIndex(Project $model)
    {
        $searchModel = new TaskSearch();
        $searchModel->project_id = $model->id;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->renderPartial('/task/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    private function renderEventIndex(Project $model)
    {
        $searchModel = new EventSearch();
        $searchModel->project_id = $model->id;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->renderPartial('/event/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    private function renderPublicationIndex(Project $model)
    {
        $searchModel = new PublicationSearch();
        $searchModel->projectId = $model->id;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->renderPartial('/publication/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'types' => Publication::getTypeList(),
        ]);
    }

    private function renderLogIndex(Project $model)
    {
        $searchModel = new LogSearch();
        $searchModel->project_id = $model->id;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->renderPartial('/log/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Project();
        $model->author_id = Yii::$app->user->id;

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('form', [
            'model' => $model,
            'levels' => Project::getLevelList(),
        ]);
    }

    /**
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('form', [
            'model' => $model,
            'levels' => Project::getLevelList(),
        ]);
    }
    /**
     * Отправка проекта на проверку
     * @param int $id
     * @return string
     */
    public function actionCheck($id) {
        $model = $this->findModel($id);
        $model->status = Project::STATUS_CHECK;
        $model->save();
        return $this->redirect(['view', 'id' => $model->id]);
    }
    /**
     * Прием проекта
     * @param int $id
     * @return string
     */
    public function actionAccept($id) {
        $model = $this->findModel($id);
        $model->status = Project::STATUS_GRANT;
        $model->save();
        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Deletes an existing Project model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Такого проекта не существует.');
    }
}
