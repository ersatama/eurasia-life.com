<?php

namespace backend\controllers;

use Yii;
use yii\base\Exception;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use common\helpers\Html;
use common\behaviors\NotifyBehavior;
use common\models\Language;
use common\models\PostArticle;
use backend\actions\Redactor;
use backend\behaviors\ControllerHelper;
use backend\behaviors\FindOneById;
use backend\models\PostArticleForm;
use backend\models\PostArticleSearchForm;

/**
 * Class PostsController — Управляет Публикациями
 *
 * @mixin ControllerHelper
 * @mixin FindOneById
 * @mixin NotifyBehavior
 *
 * @package backend\controllers
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class PostsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            ControllerHelper::class,
            [
                'class' => FindOneById::class,
                'notFoundMessage' => 'Post with id `%s` not found',
            ],
            NotifyBehavior::class,
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
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
        $findPostArticleById = function ($id) {
            return $this->findPostArticleById($id, true);
        };

        return [
            // список загруженных файлов (+ картинки)
            'redactor-file-list' => [
                'class' => Redactor::class,
                'mode' => Redactor::MODE_FILES,
                'findOneById' => $findPostArticleById,
            ],

            // загрузка файлов
            'redactor-file-upload' => [
                'class' => Redactor::class,
                'mode' => Redactor::MODE_UPLOAD_FILES,
                'findOneById' => $findPostArticleById,
            ],

            // список загруженных картинок
            'redactor-image-list' => [
                'class' => Redactor::class,
                'mode' => Redactor::MODE_IMAGES,
                'findOneById' => $findPostArticleById,
            ],

            // загрузка картинок
            'redactor-image-upload' => [
                'class' => Redactor::class,
                'mode' => Redactor::MODE_UPLOAD_IMAGES,
                'findOneById' => $findPostArticleById,
            ],
        ];
    }

    /**
     * Список публикаций
     * @return string
     */
    public function actionIndex()
    {
        $queryParams = $this->getQueryParams();
        if ($queryParams instanceof Response) {
            return $queryParams;
        }

        // поиск по публикациям
        $postArticleSearchForm = new PostArticleSearchForm();
        $postArticleSearchForm->search($queryParams);
        $postArticleSearchForm->dataProvider->models; // запрос тут

        return $this->render('index', [
            'postArticleSearchForm' => $postArticleSearchForm,
        ]);
    }

    /**
     * Добавляем публикацию
     * @param null|int $id - id записи
     * @return string|\yii\web\Response
     * @throws Exception
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     */
    public function actionCreate($id = null)
    {
        // языки
        $languages = Language::getAllWithCache();

        $postArticle = $this->findPostArticleById($id, true, false);
        if (!$postArticle) {
            $postArticle = PostArticleForm::createTmp();
            return $this->redirect(['create', 'id' => $postArticle->id]);
        }

        if ($postArticle->statusIsActive()) {
            return $this->redirect(['update', 'id' => $postArticle->id]);
        }

        $postArticleForm = new PostArticleForm(['postArticle' => $postArticle]);
        $postArticleForm->initLangForms();
        $postArticleForm->setScenarioCreate();
        if (($r = $this->loadAndSavePostForm($postArticleForm))) {
            if ($r instanceof Response) {
                return $r;
            }
            $this->notifySuccess('Новость успешно добавлена.');
            return $this->redirect(['update', 'id' => $postArticle->id]);
        }

        return $this->render('create', [
            'languages' => $languages,
            'postArticleForm' => $postArticleForm,
        ]);
    }

    /**
     * Обновляем публикацию
     * @param int $id - id записи
     * @return string|\yii\web\Response
     * @throws Exception
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     */
    public function actionUpdate($id)
    {
        // языки
        $languages = Language::getAllWithCache();

        $postArticle = $this->findPostArticleById($id);

        $postArticleForm = new PostArticleForm(['postArticle' => $postArticle]);
        $postArticleForm->initLangForms();
        if (($r = $this->loadAndSavePostForm($postArticleForm))) {
            if ($r instanceof Response) {
                return $r;
            }
            $postArticleForm->notifySuccess('Новость успешно сохранена.');
            return $this->refresh();
        }

        return $this->render('update', [
            'languages' => $languages,
            'postArticleForm' => $postArticleForm,
        ]);
    }

    /**
     * Загружаем и сохраняем форму публикации
     * @param PostArticleForm $postArticleForm
     * @return bool|\yii\web\Response
     * @throws Exception
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     */
    protected function loadAndSavePostForm(PostArticleForm $postArticleForm)
    {
        $request = Yii::$app->request;

        if (!$request->isPost || !$postArticleForm->load($request->post())) {
            return false;
        }

        array_map(function ($fieldName) use ($postArticleForm) {
            $postArticleForm->$fieldName = UploadedFile::getInstance($postArticleForm, $fieldName);

            foreach ($postArticleForm->getLangForms() as $langForm) {
                $langForm->$fieldName = UploadedFile::getInstance($langForm, $fieldName);
            }
        }, PostArticleForm::UPLOAD_FILE_FIELDS);

        return $postArticleForm->save();
    }

    /**
     * Удаляем публикацию
     * @param int $id - id записи
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $postArticle = $this->findPostArticleById($id);
        $postArticleDesc = ($t = $postArticle->name) ? sprintf(' «%s»', Html::encode($t)) : sprintf(' c ID «%s»', $postArticle->id);

        $postArticle->commonDelete();

        $this->notifySuccess(sprintf('Новость%s успешно удалена.', $postArticleDesc));

        return $this->redirect(['index']);
    }

    /**
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     */
    public function actionMove()
    {
        $request = Yii::$app->request;

        $model = $this->findPostArticleById((int)$request->post('id'));
        $afterModel = ($_t = (int)$request->post('afterId')) ? $this->findPostArticleById($_t) : null;

        $afterModel ? $model->commonMoveAfter($afterModel) : $model->commonMoveAsFirst();

        $model->commonSetUpdatedAttributes();
        $model->commonSave();

        return $this->asJson([
            'status' => 200,
        ]);
    }

    /**
     * Вернет публикацию с которым работаем по ID
     * @param int $id - id публикации
     * @param bool $orCreate – по-умолчанию ищем только среди активных публикаций
     * @param bool $throwException – кидать исключение, если не нашли публикацию
     * @return PostArticle|array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findPostArticleById($id, $orCreate = false, $throwException = true)
    {
        return $this->findOneById(PostArticle::class, $id, $orCreate, $throwException);
    }

    /**
     * @return array|mixed|Response
     */
    protected function getQueryParams()
    {
        $queryParams = Yii::$app->request->queryParams;

        if (isset($queryParams['q']) && !strlen($queryParams['q'])) {
            return $this->redirect(Url::current(['q' => null]));
        }

        return $queryParams;
    }
}
