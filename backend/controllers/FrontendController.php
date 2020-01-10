<?php

namespace backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use common\behaviors\CheckUrlBehavior;
use common\behaviors\NotifyBehavior;
use common\models\Language;
use common\models\LandingPage;
use common\models\ShortCode;
use backend\actions\Redactor;
use backend\behaviors\ControllerHelper;
use backend\models\ShortCodeForm;

/**
 * Class FrontendController – Управляет страницами сайта (Главная, Посадочные и т.д.)
 *
 * @mixin CheckUrlBehavior
 * @mixin ControllerHelper
 * @mixin NotifyBehavior
 *
 * @package backend\controllers
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class FrontendController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            ControllerHelper::class,
            [
                'class' => CheckUrlBehavior::class,
                'actions' => [
                    'main' => '/frontend/main',
                ],
            ],
            NotifyBehavior::class,
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
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
        $findOneById = function ($id) {
            return $this->findShortCodeById($id);
        };

        return [
            // список загруженных файлов (+ картинки)
            'redactor-file-list' => [
                'class' => Redactor::class,
                'mode' => Redactor::MODE_FILES,
                'findOneById' => $findOneById,
            ],

            // загрузка файлов
            'redactor-file-upload' => [
                'class' => Redactor::class,
                'mode' => Redactor::MODE_UPLOAD_FILES,
                'findOneById' => $findOneById,
            ],

            // список загруженных картинок
            'redactor-image-list' => [
                'class' => Redactor::class,
                'mode' => Redactor::MODE_IMAGES,
                'findOneById' => $findOneById,
            ],

            // загрузка картинок
            'redactor-image-upload' => [
                'class' => Redactor::class,
                'mode' => Redactor::MODE_UPLOAD_IMAGES,
                'findOneById' => $findOneById,
            ],
        ];
    }

    /**
     * Главная страница
     * @return string|Response
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionMain()
    {
        // языки
        $languages = Language::getAllWithCache();

        /* @var $frontendUrlManager yii\web\UrlManager */
        $frontendUrlManager = \Yii::$app->frontendUrlManager;

        $fCreateUrl = function ($language) use ($frontendUrlManager) {
            return Yii::getAlias('@frontendWeb') . $frontendUrlManager->createUrl(['site/index', 'language' => $language]);
        };

        $pageOnSiteMap = [];

        $forMap = [];
        foreach ($languages as $language) {
            $forMap[$language->id] = 'page-main--' . $language->id;
            $pageOnSiteMap[$language->id] = $fCreateUrl($language->slug);
        }

        $shortCodeForms = $this->getShortCodeForms(array_values($forMap), [
            'h1' => [
                'label' => 'Заголовок (h1)',
                'type' => ShortCode::TYPE_INPUT,
            ],
            'h1-sub' => [
                'label' => 'Подзаголовок (h1)',
                'type' => ShortCode::TYPE_TEXTAREA,
            ],
            'block-1' => [
                'label' => 'Блок 1',
                'type' => ShortCode::TYPE_TEXTAREA,
            ],
            'block-2__header' => [
                'label' => 'Блок 2 (Заголовок)',
                'type' => ShortCode::TYPE_INPUT,
            ],
            'block-2__content-1' => [
                'label' => 'Блок 2 (Первая колонка)',
                'type' => ShortCode::TYPE_REDACTOR,
            ],
            'block-2__content-2' => [
                'label' => 'Блок 2 (Вторая колонка)',
                'type' => ShortCode::TYPE_REDACTOR,
            ],
        ]);

        if ($shortCodeForms instanceof Response) {
            return $shortCodeForms;
        }

        $forMapFlip = array_flip($forMap);
        $shortCodeFormGroups = [];
        foreach ($shortCodeForms as $shortCodeForm) {
            $shortCodeFormGroups[$forMapFlip[$shortCodeForm->shortCode->for]][] = $shortCodeForm;
        }

        return $this->render('short-code-form-page', [
            'title' => 'Главная сайта',
            'pageUrl' => $this->getFrontendHost() . '/',
            'languages' => $languages,
            'shortCodeFormGroups' => $shortCodeFormGroups,
            'pageOnSiteMap' => $pageOnSiteMap,
        ]);
    }

    /**
     * Редактируем посадочную
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionLandingPage($id)
    {
        $landingPages = LandingPage::getAllWithCache();
        foreach ($landingPages as $landingPage) {
            if ($landingPage->id == $id) {

                $shortCodeFormMap = [
                    'name' => [
                        'label' => 'Наименование',
                        'type' => ShortCode::TYPE_INPUT,
                    ],
                    'slug' => [
                        'label' => 'URL',
                        'type' => ShortCode::TYPE_INPUT,
                    ],
                    'h1' => [
                        'label' => 'Заголовок (H1)',
                        'type' => ShortCode::TYPE_INPUT,
                    ],
                    'h1-sub' => [
                        'label' => 'Подзаголовок (H1)',
                        'type' => ShortCode::TYPE_TEXTAREA,
                    ],
                ];

                if ($landingPage->isStrahovanieRabotnikovOtNeschastnyhSluchaev()) {
                    $shortCodeFormMap = array_merge($shortCodeFormMap, [
                        // block-1
                        'block-1-header' => [
                            'label' => 'Блок 1 (H2)',
                            'type' => ShortCode::TYPE_INPUT,
                        ],
                        'block-1__content-1' => [
                            'label' => 'Блок 1 (нет договора ОСНС)',
                            'type' => ShortCode::TYPE_REDACTOR,
                        ],
                        'block-1__content-2' => [
                            'label' => 'Блок 1 (есть договора ОСНС)',
                            'type' => ShortCode::TYPE_REDACTOR,
                        ],

                        // block-2
                        'block-2__title-1' => [
                            'label' => 'Блок 2 (название 1)',
                            'type' => ShortCode::TYPE_INPUT,
                        ],
                        'block-2__content-1' => [
                            'label' => 'Блок 2 (контент 1)',
                            'type' => ShortCode::TYPE_REDACTOR,
                        ],
                        'block-2__title-2' => [
                            'label' => 'Блок 2 (название 2)',
                            'type' => ShortCode::TYPE_INPUT,
                        ],
                        'block-2__content-2' => [
                            'label' => 'Блок 2 (контент 2)',
                            'type' => ShortCode::TYPE_REDACTOR,
                        ],
                        'block-2__title-3' => [
                            'label' => 'Блок 3 (название 3)',
                            'type' => ShortCode::TYPE_INPUT,
                        ],
                        'block-2__content-3' => [
                            'label' => 'Блок 3 (контент 3)',
                            'type' => ShortCode::TYPE_REDACTOR,
                        ],
                    ]);
                }

                if ($landingPage->isAnnuitetnoeStrahovanieVRamkahOsns()) {
                    $shortCodeFormMap = array_merge($shortCodeFormMap, [
                        // block-1
                        'block-1' => [
                            'label' => 'Блок 1',
                            'type' => ShortCode::TYPE_TEXTAREA,
                        ],

                        // block-2
                        'block-2__header' => [
                            'label' => 'Блок 2 (заголовок)',
                            'type' => ShortCode::TYPE_INPUT,
                        ],
                        'block-2__content-1' => [
                            'label' => 'Блок 2 (первая колонка)',
                            'type' => ShortCode::TYPE_REDACTOR,
                        ],
                        'block-2__content-2' => [
                            'label' => 'Блок 2 (вторая колонка)',
                            'type' => ShortCode::TYPE_REDACTOR,
                        ],
                        'block-2__content-3' => [
                            'label' => 'Блок 3 (третья колонка)',
                            'type' => ShortCode::TYPE_REDACTOR,
                        ],

                        // block-3
                        'block-3__title-1' => [
                            'label' => 'Блок 3 (название 1)',
                            'type' => ShortCode::TYPE_INPUT,
                        ],
                        'block-3__content-1' => [
                            'label' => 'Блок 3 (контент 1)',
                            'type' => ShortCode::TYPE_REDACTOR,
                        ],
                        'block-3__title-2' => [
                            'label' => 'Блок 3 (название 2)',
                            'type' => ShortCode::TYPE_INPUT,
                        ],
                        'block-3__content-2' => [
                            'label' => 'Блок 3 (контент 2)',
                            'type' => ShortCode::TYPE_REDACTOR,
                        ],
                    ]);
                }

                if ($landingPage->isStrahovanieZhizniZayomshhikovPoKreditam()) {
                    $shortCodeFormMap = array_merge($shortCodeFormMap, [
                        // block-1
                        'block-1__header' => [
                            'label' => 'Блок 1 (заголовок)',
                            'type' => ShortCode::TYPE_INPUT,
                        ],
                        'block-1__content-1' => [
                            'label' => 'Блок 1 (первая колонка)',
                            'type' => ShortCode::TYPE_REDACTOR,
                        ],
                        'block-1__content-2' => [
                            'label' => 'Блок 1 (вторая колонка)',
                            'type' => ShortCode::TYPE_REDACTOR,
                        ],

                        // block-2
                        'block-2__title-1' => [
                            'label' => 'Блок 2 (название 1)',
                            'type' => ShortCode::TYPE_INPUT,
                        ],
                        'block-2__content-1' => [
                            'label' => 'Блок 2 (контент 1)',
                            'type' => ShortCode::TYPE_REDACTOR,
                        ],
                        'block-2__title-2' => [
                            'label' => 'Блок 2 (название 2)',
                            'type' => ShortCode::TYPE_INPUT,
                        ],
                        'block-2__content-2' => [
                            'label' => 'Блок 2 (контент 2)',
                            'type' => ShortCode::TYPE_REDACTOR,
                        ],
                    ]);
                }

                if ($landingPage->isPensionnyjAnnuitet()) {
                    $shortCodeFormMap = array_merge($shortCodeFormMap, [
                        // block-1
                        'block-1' => [
                            'label' => 'Блок 1',
                            'type' => ShortCode::TYPE_REDACTOR,
                        ],

                        // block-2
                        'block-2__content-1' => [
                            'label' => 'Блок 2 (контент 1)',
                            'type' => ShortCode::TYPE_REDACTOR,
                        ],
                        'block-2__content-2' => [
                            'label' => 'Блок 2 (контент 2)',
                            'type' => ShortCode::TYPE_REDACTOR,
                        ],
                        'block-2__content-3' => [
                            'label' => 'Блок 2 (контент 3)',
                            'type' => ShortCode::TYPE_REDACTOR,
                        ],
                        'block-2__content-4' => [
                            'label' => 'Блок 2 (контент 4)',
                            'type' => ShortCode::TYPE_REDACTOR,
                        ],

                        // block-3
                        'block-3__title-1' => [
                            'label' => 'Блок 3 (название)',
                            'type' => ShortCode::TYPE_INPUT,
                        ],
                        'block-3__content-1' => [
                            'label' => 'Блок 3 (контент)',
                            'type' => ShortCode::TYPE_REDACTOR,
                        ],
                    ]);
                }

                $shortCodeFormMap = array_merge($shortCodeFormMap, [
                    'form-factoid' => [
                        'label' => 'Фактоид формы',
                        'type' => ShortCode::TYPE_TEXTAREA,
                    ],

                    'visible' => [
                        'label' => 'Показывать на сайте',
                        'type' => ShortCode::TYPE_BOOLEAN,
                    ],
                ]);

                return $this->getEditLandingPage($landingPage, $shortCodeFormMap);
            }
        }

        throw new NotFoundHttpException();
    }

    /**
     * Вернет редактирование посадочной страницы
     * @param LandingPage $landingPage
     * @param array $shortCodeFormMap
     * @return string|Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    protected function getEditLandingPage(LandingPage $landingPage, array $shortCodeFormMap)
    {
        // языки
        $languages = Language::getAllWithCache();

        $forMap = [
            $landingPage->lang_id => $landingPage->getShortCodeName(),
        ];

        /* @var $frontendUrlManager yii\web\UrlManager */
        $frontendUrlManager = \Yii::$app->frontendUrlManager;

        $fCreateUrl = function (LandingPage $landingPage) use ($frontendUrlManager) {
            return Yii::getAlias('@frontendWeb') . $frontendUrlManager->createUrl(['landing-pages/view', $landingPage]) . '?sim-sim';
        };

        $pageOnSiteMap = [
            $landingPage->lang_id => $fCreateUrl($landingPage),
        ];

        foreach (LandingPage::getAllWithCache() as $lp) {
            if (in_array($lp->id, $landingPage->translation_ids)) {
                $forMap[$lp->lang_id] = $lp->getShortCodeName();
                $pageOnSiteMap[$lp->lang_id] = $fCreateUrl($lp);
            }
        }

        $shortCodeForms = $this->getShortCodeForms(array_values($forMap), $shortCodeFormMap);

        if ($shortCodeForms instanceof Response) {
            return $shortCodeForms;
        }

        $forMapFlip = array_flip($forMap);
        $shortCodeFormGroups = [];
        foreach ($shortCodeForms as $shortCodeForm) {
            $shortCodeFormGroups[$forMapFlip[$shortCodeForm->shortCode->for]][] = $shortCodeForm;
        }

        return $this->render('short-code-form-page', [
            'title' => $landingPage->name,
            'pageUrl' => $fCreateUrl($landingPage),
            'languages' => $languages,
            'shortCodeFormGroups' => $shortCodeFormGroups,
            'pageOnSiteMap' => $pageOnSiteMap,
        ]);
    }

    /**
     * Вернет формы шортокодов
     * @param array $forArray
     * @param array $map
     * @return ShortCodeForm[]|Response
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    protected function getShortCodeForms(array $forArray, array $map)
    {
        $fForShortCodeKey = function (string $for, string $shortCode): string {
            return "{$for}---{$shortCode}";
        };

        // из БД
        $query = ShortCode::find()
            ->active()
            ->andWhere(['for' => $forArray])
            ->with('files')
            ->indexBy(function (ShortCode $r) use ($fForShortCodeKey) {
                return $fForShortCodeKey($r->for, $r->short_code);
            });
        $shortCodes = $query->all();

        //
        $_shortCodes = [];

        foreach ($forArray as $for) {
            foreach ($map as $shortCodeName => $shortCodeOptions) {
                $key = $fForShortCodeKey($for, $shortCodeName);
                if (isset($shortCodes[$key])) {
                    $shortCode = $shortCodes[$key];
                    unset($shortCodes[$key]);
                } else {
                    $shortCode = new ShortCode();
                    $shortCode->for = $for;
                    $shortCode->short_code = $shortCodeName;
                    $shortCode->changeStatusToActive();
                    $shortCode->setCreatedAttributes();
                }
                $shortCode->setAttributes($shortCodeOptions, false);
                $shortCode->save();

                $_shortCodes[] = $shortCode;
            }
        }

        // удалим чего уже нет
        array_map(function (ShortCode $shortCode) {
            $shortCode->delete();
        }, $shortCodes);

        $shortCodes = $_shortCodes;

        // создаем формы
        /**
         * @var $shortCodeForms ShortCodeForm[]
         */
        $shortCodeForms = array_map(function ($shortCode) {
            return ShortCodeForm::createForm($shortCode);
        }, $shortCodes);

        // передали данные
        if (($postData = Yii::$app->request->post())) {
            $btnMain = isset($postData[ShortCodeForm::BUTTON_MAIN_SAVE]);
            $saveShortCodeFormsIsValid = true;
            $saveShortCodeForms = [];

            foreach ($shortCodeForms as $shortCodeForm) {
                if (!$shortCodeForm->load($postData)) {
                    continue;
                }

                if ($btnMain) {
                    $saveShortCodeForms[] = $shortCodeForm;
                    $saveShortCodeFormsIsValid = $saveShortCodeFormsIsValid && $shortCodeForm->validate();
                } else if ($shortCodeForm->activeByBtn() && $shortCodeForm->save()) {
                    $this->setJsScrollTo('#' . $shortCodeForm->formName() . '-field-blk');

                    unset($shortCodeForm->getShortCode()->files);
                    break;
                }
            }

            if ($saveShortCodeForms && $saveShortCodeFormsIsValid) {
                // сохраним формы
                array_map(function (ShortCodeForm $saveShortCodeForm) {
                    $saveShortCodeForm->save(false);
                }, $saveShortCodeForms);

                $this->notifySuccess('Страница успешно сохранена.', 'header');
                return $this->refresh();
            } else {
                return $this->refresh();
            }
        }

        return $shortCodeForms;
    }

    /**
     * Вернет шорткод с которым работаем
     * @param int|null $id
     * @return ShortCode
     * @throws NotFoundHttpException
     */
    protected function findShortCodeById($id): ShortCode
    {
        $shortCode = $id
            ? ShortCode::find()->active()->andWhere(['id' => $id])->limit(1)->one()
            : null;

        if (!($shortCode instanceof ShortCode)) {
            throw new NotFoundHttpException(sprintf('ShortCode with id `%s` not found', $id));
        }

        return $shortCode;
    }

    /**
     * @return string|null
     */
    protected function getFrontendHost(): ?string
    {
        return Yii::getAlias('@frontendWeb');
    }

    /**
     * @param string $scrollTo
     */
    protected function setJsScrollTo(string $scrollTo): void
    {
        Yii::$app->view->params['js-scroll-to'] = $scrollTo;
    }
}
