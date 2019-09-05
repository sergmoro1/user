<?php
namespace sergmoro1\user\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use sergmoro1\user\Module;

use sergmoro1\user\models\BaseUser as User;
use sergmoro1\user\models\LoginForm;
use sergmoro1\user\models\PasswordResetRequestForm;
use sergmoro1\user\models\ResetPasswordForm;
use sergmoro1\user\models\SignupForm;

/**
 * Site controller implements user registration actions.
 */
class SiteController extends Controller
{
    public function init()
    {
        parent::init();

        Yii::$app->mailer->setViewPath('@vendor/sergmoro1/yii2-user/src/mail');
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'signup', 'index', 'logout', 'request-password-reset', 'reset-password'],
                'rules' => [
                    [
                        'actions' => ['login', 'signup', 'request-password-reset', 'reset-password'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm([
            'urlPasswordValid' => Url::to([
                'user/password-valid'])
        ]);
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();
        } else {
            return $this->render('login', [
                'model' => $model,
                'frontend' => $this->toFrontend(),
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect($this->toFrontend());
    }
    
    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if ($model->sendEmail($user)) {
                    Yii::$app->session->setFlash(
                        'success', 
                        Module::t('core', 
                            '{name}, thank you for registering on the {website} website, check email, to complete the procedure.', 
                            ['name' => $user->username, 'website' => Yii::$app->name]
                        )
                    );
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
            'frontend' => $this->toFrontend(),
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', Module::t('core', 'Check your email for further instructions.'));

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', Module::t('core', 'Sorry, we are unable to reset password for email provided.'));
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
            'frontend' => $this->toFrontend(),
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', Module::t('core', 'New password was saved.'));

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
            'frontend' => $this->toFrontend(),
        ]);
    }

    /**
     * Activate user.
     *
     * @param string $token
     * @return mixed
     * @throws InvalidParamException
     */
    public function actionActivateUser($token)
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException(Module::t('core', 'User activation token cannot be blank.'));
        }
        $user = User::findByPasswordResetToken($token);
        if (!$user) {
            throw new InvalidParamException(Module::t('core', 'Wrong user activation token.'));
        }
        $user->status = User::STATUS_ACTIVE;
        if($user->save())
            Yii::$app->session->setFlash(
                'success', 
                Module::t('core', 
                    'User {name} is successfully activated.', 
                    ['name' => $user->username]
                )
            );
        else
            Yii::$app->session->setFlash(
                'error', 
                Module::t('core', 
                    'User {name} can\'t be activated!', 
                    ['name' => $user->username]
                )
            );

        return ($url = Url::previous()) ? $this->redirect($url . '#leave-comment') : $this->goHome();
    }

    private function toFrontend()
    {
        return str_replace('back', 'front', (Url::base()
            ? Url::base()
            : Yii::$app->request->hostInfo
        ));
    }
}
