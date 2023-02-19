<?php

namespace app\controllers;

use app\models\User;
use app\models\Project;
use app\models\ProjectTeam;
use app\models\ProjectTeamSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Yii;

/**
 * ProjectTeamController implements the CRUD actions for ProjectTeam model.
 */
class ProjectTeamController extends Controller
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
     * Creates a new ProjectTeam model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($projectId)
    {
        $model = new ProjectTeam();
        $model->type = ProjectTeam::TYPE_MEMBER;
        $model->project_id = $projectId;
        if(!$model->project->canEdit($this->user)) {
            throw new ForbiddenHttpException('Вы не можете приглашать участника в этот проект!');
        }

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
     * Creates a new ProjectTeam model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionInvite($userId)
    {
        $model = new ProjectTeam();
        $model->user_id = $userId;
        if($model->user->role == User::ROLE_MENTOR) {
            $model->type = ProjectTeam::TYPE_MENTOR;
        } elseif($model->user->role == User::ROLE_INVESTOR) {
            $model->type = ProjectTeam::TYPE_INVESTOR;
        } else {
            $model->type = ProjectTeam::TYPE_MEMBER;
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['user/profile', 'id' => $model->user_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('invite', [
            'model' => $model,
            'projects' => Project::getMyProjectList($this->user),
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

        if(!$model->project->canEdit($this->user)) {
            throw new ForbiddenHttpException('Вы не можете изменять участника в этом проекте!');
        }

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

        if(!$model->project->canEdit($this->user)) {
            throw new ForbiddenHttpException('Вы не можете удалять участника в этом проекте!');
        }
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
