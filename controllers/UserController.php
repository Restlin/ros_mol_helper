<?php

namespace app\controllers;

use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use Yii;
/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
                            'matchCallback' => function() {
                                return $this->user->role == User::ROLE_ADMIN;
                            }
                        ],
                        [
                            'allow' => true,
                            'actions' => ['my', 'update-my', 'list', 'profile', 'photo'],
                            'roles' => ['@'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['register', 'register-success'],
                            'roles' => ['?'],
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
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'roles' => User::getRoleList(),
        ]);
    }

    /**
     * Lists all User models.
     *
     * @return string
     */
    public function actionList()
    {
        $searchModel = new UserSearch();
        $searchModel->withoutAdmin = true;
        $dataProvider = $searchModel->search($this->request->queryParams);

        $roles = User::getRoleList();
        unset($roles[User::ROLE_ADMIN]);
        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'roles' => $roles,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'roles' => User::getRoleList(),
        ]);
    }

    /**
     * Просмотр информации о себе
     * @return string
     */
    public function actionMy()
    {
        $model = $this->user;
        return $this->render('my', [
            'model' => $model,
            'roles' => User::getRoleList(),
        ]);
    }

    /**
     * Displays a open information about single User model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionProfile($id)
    {
        $model = $this->findModel($id);
        return $this->render('profile', [
            'model' => $model,
            'roles' => User::getRoleList(),
        ]);
    }

    /**
     * Displays a photo single User model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionPhoto($id)
    {
        $model = $this->findModel($id);
        if(!$model->photo) {
            throw new NotFoundHttpException('Фото такого пользователя не существует!');
        }
        return $this->response->sendStreamAsFile($model->photo, "{$model->fio}.png", ['inline' => true]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new User();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('form', [
            'model' => $model,
            'roles' => User::getRoleList(),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionRegister()
    {
        $model = new User();
        $model->role = User::ROLE_DEFAULT;
        $model->setScenario('register');

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['register-success']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    public function actionRegisterSuccess() {
        return $this->render('register_success');
    }

    /**
     * Updates an existing User model.
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
            'roles' => User::getRoleList(),
        ]);
    }

    /**
     * Обновление информации о пользователе
     * @return string|\yii\web\Response
     */
    public function actionUpdateMy()
    {
        $model = $this->user;
        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->photoFile = UploadedFile::getInstance($model, 'photoFile');
            if($model->save()) {
                return $this->redirect(['my']);
            }
        }

        return $this->render('form_my', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Такого пользователя не существует!');
    }
}
