<?php

namespace app\controllers;

use app\models\HelpMessage;
use app\models\HelpMessageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HelpMessageController implements the CRUD actions for HelpMessage model.
 */
class HelpMessageController extends Controller
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
     * Lists all HelpMessage models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new HelpMessageSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'models' => HelpMessage::getModelList(),
            'attrs' => HelpMessage::getAllAttrList(),
        ]);
    }

    /**
     * Displays a single HelpMessage model.
     * @param int $id Код
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
     * Creates a new HelpMessage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new HelpMessage();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('form', [
            'model' => $model,
            'models' => HelpMessage::getModelList(),
            'attrs' => HelpMessage::getAllAttrList(),
        ]);
    }

    /**
     * Updates an existing HelpMessage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id Код
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('form', [
            'model' => $model,
            'models' => HelpMessage::getModelList(),
            'attrs' => HelpMessage::getAllAttrList(),
        ]);
    }

    /**
     * Deletes an existing HelpMessage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id Код
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the HelpMessage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id Код
     * @return HelpMessage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HelpMessage::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
