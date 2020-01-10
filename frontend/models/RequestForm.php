<?php

namespace frontend\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use common\behaviors\NotifyBehavior;

/**
 * Class RequestForm – Форма заявки, обратной связи
 *
 * @mixin NotifyBehavior
 *
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class RequestForm extends Model
{
    const POSITION_DEFAULT = 1;

    const POSITION_LANDING_PAGE = 2;

    const POSITION_HEADER = 3;

    /**
     * @var string — Имя
     */
    public $name;

    /**
     * @var string — Телефон
     */
    public $phone;

    /**
     * @var string — Комментарий
     */
    public $comment;

    /**
     * @var int — Позиция на сайте
     */
    public $position = self::POSITION_DEFAULT;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            NotifyBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name
//            ['name', 'required'],
            ['name', 'trim'],
            ['name', 'string', 'max' => 255],

            // phone
            ['phone', 'required'],
            ['phone', 'trim'],
            ['phone', 'string', 'max' => 255],

            // comment
            ['comment', 'trim'],
            ['comment', 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Имя'),
            'phone' => Yii::t('app', 'Телефон'),
            'comment' => Yii::t('app', 'Комментарий'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'request-' . $this->position;
    }

    /**
     * Основной метод
     * @param bool $validate
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function send($validate = true)
    {
        if ($validate && !$this->validate()) {
            return false;
        }

        $nameBlock = '';

        if ($this->name) {
            $nameBlock = sprintf('<p>Имя: %s</p>', $this->name);
            $subjectUniq = sprintf('%s, %s', $this->name, $this->phone);
        } else {
            $subjectUniq = sprintf('%s', $this->phone);
        }

        $datetime = Yii::$app->formatter->asDatetime('now', 'd MMMM в HH:mm');
        $phone = sprintf(
            '<a href="tel:%s">%s</a>',
            preg_replace('/[^0-9\+]/uis', '', $this->phone),
            $this->phone
        );
        $comment = $this->comment != '' ? "<p>$this->comment</p>" : '';
        $comment = nl2br($comment);
        $page = Yii::$app->request->getAbsoluteUrl();
        $footer = "Письмо отправлено роботом $datetime";

        $subject = $this->position === static::POSITION_HEADER
            ? sprintf('[Заявка на перезвон] %s', $subjectUniq)
            : sprintf('[Заявка] %s', $subjectUniq);
        $htmlBody = <<<TEXT
$nameBlock
$comment
<p>Номер: $phone</p>
<p>Страница: $page</p>
<p>---</p>
<p>$footer</p>
TEXT;

        return $this->_sendMail($subject, $htmlBody);
    }

    /**
     * Отправляем почту
     * @param string $subject
     * @param string $htmlBody
     * @return bool
     * @throws InvalidConfigException
     */
    protected function _sendMail($subject, $htmlBody)
    {
        if (!isset(Yii::$app->params['form-emails']['from'])) {
            throw new InvalidConfigException(sprintf('`from` not found'));
        }

        if (!isset(Yii::$app->params['form-emails']['to'])) {
            throw new InvalidConfigException(sprintf('`to` not found'));
        }

        $from = Yii::$app->params['form-emails']['from'];
        $to = Yii::$app->params['form-emails']['to'];
        $bcc = isset(Yii::$app->params['form-emails']['bcc']) ? Yii::$app->params['form-emails']['bcc'] : null;

        $mailMessage = Yii::$app->mailer->compose()
            ->setFrom($from)
            ->setTo($to)
            ->setSubject($subject)
            ->setHtmlBody($htmlBody);

        if ($bcc) {
            $mailMessage->setBcc($bcc);
        }

        return $mailMessage->send();
    }
}
