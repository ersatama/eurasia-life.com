<?php

namespace backend\actions;

use Yii;
use yii\base\Action;
use yii\web\NotFoundHttpException;

/**
 * Class Move – Действие для контроллера: перемещает модель по дереву
 *
 * @package backend\actions
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class Move extends Action
{
    /**
     * @var string — название класс модели с которым работаем
     */
    public $modelClassName;

    /**
     * @var bool
     */
    public $tree = true;

    /**
     * Запускаем действие
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        $data = Yii::$app->request->post() + ['id' => null];

        $model = $this->findOrFailById($data['id']);

        if ($this->tree) {
            if (isset($data['afterId']) && ($afterId = $data['afterId'])) {
                $model->insertAfter($this->findOrFailById($afterId));
            } elseif (isset($data['parentId']) && ($parentId = $data['parentId'])) {
                $model->prependTo($this->findOrFailById($parentId));
            } else {
                $modelClassName = $this->modelClassName;
                $modelRoot = $modelClassName::find()->active()->roots()->limit(1)->one();
                if (!$modelRoot) {
                    throw new NotFoundHttpException();
                }
                $model->prependTo($modelRoot);
            }
        } else {
            if (isset($data['afterId']) && ($afterId = $data['afterId'])) {
                $model->moveAfter($this->findOrFailById($afterId));
            } else {
                $model->moveAsFirst();
            }

            // todo: move!!!
            if ($model instanceof \common\models\PostArticle) {
                $model->setUpdatedAttributes();
                $model->save();
            }
        }

        return $this->controller->asJson([
            'status' => 200,
        ]);
    }

    /**
     * Поиск модели по ID
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    protected function findOrFailById($id)
    {
        $modelClassName = $this->modelClassName;
        $model = null;
        if ($id) {
            /**
             * @var $query \yii\db\Query
             */
            $query = $modelClassName::find();
            if ($this->tree) {
                $query->withoutRoot();
            }
            $query->active();
            $query->id($id);
            $query->limit(1);

            $model = $query->one();
        }
        if (!$model) {
            throw new NotFoundHttpException();
        }
        return $model;
    }
}
