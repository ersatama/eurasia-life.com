<?php

namespace backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\helpers\Html;
use common\behaviors\CheckUrlBehavior;
use common\behaviors\NotifyBehavior;
use common\models\Page;
use backend\actions\Move;
use backend\actions\Redactor;
use backend\behaviors\ControllerHelper;
use backend\behaviors\FindOneById;
use backend\models\PageForm;

/**
 * Class PagesController – Управляет контент-страницами
 *
 * @mixin CheckUrlBehavior
 * @mixin ControllerHelper
 * @mixin FindOneById
 * @mixin NotifyBehavior
 *
 * @package backend\controllers
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class PagesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => CheckUrlBehavior::class,
                'actions' => [
                    'index' => '/pages',
                ]
            ],
            ControllerHelper::class,
            [
                'class' => FindOneById::class,
                'notFoundMessage' => 'Page with id `%s` not found',
            ],
            NotifyBehavior::class,
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'move' => ['POST'],
                    'redactor-image-upload' => ['POST'],
                    'redactor-file-upload' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $findPageById = function ($id) {
            return $this->findPageById($id, true);
        };

        return [
            // перемещаем модель по дереву
            'move' => [
                'class' => Move::class,
                'modelClassName' => Page::class,
            ],

            // список загруженных файлов (+ картинки)
            'redactor-file-list' => [
                'class' => Redactor::class,
                'mode' => Redactor::MODE_FILES,
                'findOneById' => $findPageById,
            ],

            // загрузка файлов
            'redactor-file-upload' => [
                'class' => Redactor::class,
                'mode' => Redactor::MODE_UPLOAD_FILES,
                'findOneById' => $findPageById,
            ],

            // список загруженных картинок
            'redactor-image-list' => [
                'class' => Redactor::class,
                'mode' => Redactor::MODE_IMAGES,
                'findOneById' => $findPageById,
            ],

            // загрузка картинок
            'redactor-image-upload' => [
                'class' => Redactor::class,
                'mode' => Redactor::MODE_UPLOAD_IMAGES,
                'findOneById' => $findPageById,
            ],
        ];
    }

    /**
     * Список страниц
     * @return string
     */
    public function actionIndex()
    {
        $query = Page::find();
        $query->active();
        $query->withoutRoot();
        $query->sort();
        $pages = $query->all();

        return $this->render('index', [
            'pages' => $pages,
        ]);
    }

    /**
     * Добавляем страницу
     * @param null|int $id - id записи
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function actionCreate($id = null)
    {
        $page = $this->findPageById($id, true, false);
        if (!$page) {
            $page = PageForm::createTmp();
            return $this->redirect(['create', 'id' => $page->id]);
        }

        if ($page->statusIsActive()) {
            return $this->redirect(['update', 'id' => $page->id]);
        }

        $pageForm = new PageForm($page);
        $pageForm->scenario = PageForm::SCENARIO_CREATE;
        if ($this->loadAndSavePageForm($pageForm)) {
            $this->notifySuccess('Страница успешно добавлена.');
            return $this->redirect(['update', 'id' => $page->id]);
        }

        return $this->render('create', [
            'pageForm' => $pageForm,
        ]);
    }

    /**
     * Обновляем страницу
     * @param int $id - id записи
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function actionUpdate($id)
    {
        $page = $this->findPageById($id);

        $pageForm = new PageForm($page);
        if ($this->loadAndSavePageForm($pageForm)) {
            $pageForm->notifySuccess('Страница успешно сохранена.');
            return $this->refresh();
        }

        return $this->render('update', [
            'pageForm' => $pageForm,
        ]);
    }

    /**
     * @param PageForm $pageForm
     * @return bool
     * @throws \yii\base\Exception
     */
    protected function loadAndSavePageForm(PageForm $pageForm)
    {
        return $pageForm->load(Yii::$app->request->post()) && $pageForm->save();
    }

    /**
     * Удаляем страницу
     * @param int $id - id записи
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $page = $this->findPageById($id);
        $pageDesc = ($t = $page->name) ? sprintf(' «%s»', Html::encode($t)) : sprintf(' c ID «%s»', $page->id);

        $page->delete();

        $this->notifySuccess(sprintf('Страница%s успешно удалена.', $pageDesc));

        return $this->redirect(['index']);
    }

    /**
     * Вернет страницу с которой работаем по ID
     * @param int $id - id страницы
     * @param bool $orCreate – по-умолчанию ищем только среди активных страниц
     * @param bool $throwException – кидать исключение, если не нашли страницу
     * @return Page|array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findPageById($id, $orCreate = false, $throwException = true)
    {
        return $this->findOneById(Page::class, $id, $orCreate, $throwException);
    }
}
