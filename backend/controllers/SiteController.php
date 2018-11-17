<?php
namespace backend\controllers;

use common\helpers\StringHelper;
use common\models\Member\Grant;
use common\services\GrantService;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\LoginForm;
use mdm\admin\components\MenuHelper;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'info', 'logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = 'left-layout';
        $menus  = MenuHelper::getAssignedMenu(Yii::$app->user->id, null, function($menu){
            return [
                'label'   => $menu['name'],
                'url'     => [$menu['route']],
                'options' => $menu['data'],
                'items'   => $menu['children']
            ];
        });

        $outData = [];
        foreach ($menus as $key => $v) {
            $child = [];
            if ($v['items']) {
                foreach ($v['items'] as $k => $item) {
                    $child[$k] = [
                        'name' => $item['label'],
                        'url'  => $item['url']
                    ];
                }
            }
            $outData[$key] = [
                'name'  => $v['label'],
                'class' => $v['options'],
                'url'   => $v['url'],
                'child' => $child
            ];
            if ($child) {
                unset($child);
            };
        }

        Yii::$app->view->params['menu'] = $outData;
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->renderPartial('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @return string
     */
    public function actionInfo()
    {
        return $this->render('info');
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
