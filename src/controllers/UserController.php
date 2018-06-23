<?php
namespace sergmoro1\user\controllers;

use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use sergmoro1\user\Module;

use common\models\User;
use sergmoro1\user\models\UserSearch;

class UserController extends Controller
{
    public $_model = null;
    
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
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (\Yii::$app->user->can('index', [], false)) {
            $searchModel = new UserSearch();
            $dataProvider = $searchModel->search(\Yii::$app->request->get());

            return $this->render('index', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ]);
        } else
            throw new ForbiddenHttpException(Module::t('core', 'Access denied.'));
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (\Yii::$app->user->can('update', ['user' => $model])) {
            if ($model->load(\Yii::$app->request->post()) && $model->save()) {
                if(\Yii::$app->user->identity->group == User::GROUP_COMMENTATOR) {
                    \Yii::$app->session->setFlash(
                        'success', 
                        Module::t('core', 
                            '{name}\'s profile was successfully updated.', 
                            ['name' => \Yii::$app->user->identity->name]
                        )
                    );
                    
                    return $this->redirect(['/blog/site/index']);
                } else
                    return $this->redirect(['index']);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else
            throw new ForbiddenHttpException(Module::t('core', 'Access denied.'));
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (!\Yii::$app->user->can('delete'))
            throw new ForbiddenHttpException(\Module::t('core', 'Access denied.'));

        $model = $this->findModel($id);
        foreach($model->files as $file)
            $file->delete();
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value or by name.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $name
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if($this->_model === null) 
        {
            if($this->_model = $id ? User::findOne($id) : null) 
            {
                return $this->_model;
            } else {
                throw new NotFoundHttpException(Module::t('core', 'The requested model does not exist.'));
            }
        }
    }
}
