<?php

namespace frontend\behaviors;

use Yii;
use yii\base\Behavior;
use yii\web\Controller;
use yii\web\Response;
use common\widgets\ActiveForm;
use frontend\models\RequestForm as RequestFormModel;

/**
 * Class RequestForm — Поведение помогает работать с формой обратной связи
 *
 * @property Controller $owner
 *
 * @package frontend\behaviors
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class RequestForm extends Behavior
{
    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeAction',
        ];
    }

    /**
     * @throws \yii\base\ExitException
     * @throws \yii\base\InvalidConfigException
     */
    public function beforeAction()
    {
        $requestForm = $this->getRequestForm(RequestFormModel::POSITION_HEADER);

        if ($requestForm instanceof Response) {
            $requestForm->send();
            Yii::$app->end();
            return;
        }

        $this->owner->view->params['header-request-form'] = $requestForm;
    }

    /**
     * Вернет форму обратной связи
     *
     * @param null $position
     * @return RequestFormModel|Response
     * @throws \yii\base\InvalidConfigException
     */
    public function getRequestForm($position = null)
    {
        $requestForm = new RequestFormModel();
        if ($position !== null) {
            $requestForm->position = $position;
        }

        if ($requestForm->load(Yii::$app->request->post())) {

            $successMessage = Yii::t('app', 'Спасибо, мы приняли заявку и свяжемся с вами в ближайшее время.');

            // ajax
            if (Yii::$app->request->isAjax) {
                $result = $requestForm->send()
                    ? ['status' => 'ok', 'message' => $successMessage]
                    : ['status' => 'err', 'errors' => $requestForm->getErrors()];
                return $this->owner->asJson($result);
            }

            if ($requestForm->send()) {
                $requestForm->notifySuccess($successMessage);
                return $this->owner->refresh('#form');
            }
        }

        return $requestForm;
    }
}
