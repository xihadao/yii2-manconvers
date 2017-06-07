<?php

namespace rbac\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use rbac\admin\models\Department;
use rbac\admin\models\searchs\Department as DepartmentSearch;

/**
 * Department controller
 */
class DepartmentController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Department models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DepartmentSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'operator');
        $dataProvider = Department::get(0, Department::find()->where(['>=','status','-1'])->asArray()->all());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single Department model.
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
     * Creates a new Department model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Department();
        $model->load(Yii::$app->request->post()) && $model->save();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    /**
     * Updates an existing App model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
    
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    /**
     * Deletes an existing Department model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    /**
     * Batch delete existing Manholecovers models.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param array $ids
     * @return mixed
     */
    public function actionBatchUpdateStatus()
    {
        //if(!Yii::$app->user->can('deleteYourAuth')) throw new ForbiddenHttpException(Yii::t('app', 'No Auth'));
        $ids = Yii::$app->request->post('ids');
        $statusType = Yii::$app->request->post('statusType');
        $status = constant('\rbac\admin\models\Department::'.$statusType);
        if (is_array($ids)) {
            foreach ($ids as $id) {
                /*$this->findModel($id)->delete();*/
                $model = $this->findModel($id);
                $model->status = $status;
                $model->save();
            }
        }
    
        return $this->redirect(['index']);
    }
    /**
     * Batch delete existing Manholecovers models.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param array $ids
     * @return mixed
     */
    public function actionBatchDisabled()
    {
        //if(!Yii::$app->user->can('deleteYourAuth')) throw new ForbiddenHttpException(Yii::t('app', 'No Auth'));
    
        $ids = Yii::$app->request->post('ids');
        if (is_array($ids)) {
            foreach ($ids as $id) {
                /*$this->findModel($id)->delete();*/
                $model = $this->findModel($id);
                $model->status = Department::STATUS_DISABLED;
                $model->save();
            }
            
        }
        return $this->redirect(['index']);
    }
    /**
     * Finds the Department model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Department the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Department::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}