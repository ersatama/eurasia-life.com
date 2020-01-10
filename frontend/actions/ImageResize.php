<?php

namespace frontend\actions;

use Yii;
use yii\base\Action;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use yii\web\NotFoundHttpException;
use common\models\File;

/**
 * Class ImageResize – Действие нарезки фоток
 * @package frontend\actions
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class ImageResize extends Action
{
    /**
     * @param $path
     * @return yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function run($path)
    {
        if (preg_match('`^resized/\d{3}/\d{3}/\d{3}-([^/]+)/(\d+)--([^\.]+)\.jpg$`uis', $path, $matches)) {

            list(, $fileCode, $fileId, $type) = $matches;

            $file = $this->findFile($fileId, $fileCode);

            if ($file && ('/' . $path == $file->getPreviewUrl($type))) {
                $source = $file->path;
                $dist = Yii::getAlias('@webroot/') . $path;

                $resizeRules = $this->getResizeRulesByType($type);
                if ($resizeRules) {

                    FileHelper::createDirectory(dirname($dist));

                    Image::resize($source, $resizeRules['w'], $resizeRules['h'])->save($dist);

                    return Yii::$app->response->sendFile($dist, null, ['inline' => true]);
                }
            }
        }

        throw new NotFoundHttpException(sprintf('File not found.'));
    }

    /**
     * @param $fileId
     * @param $fileCode
     * @return array|File|null|\yii\db\ActiveRecord
     */
    protected function findFile($fileId, $fileCode)
    {
        $fileQuery = File::find();
        $fileQuery->andWhere(['id' => $fileId]);
        $fileQuery->limit(1);
        $file = $fileQuery->one();

        return $file && pathinfo($file->filename, PATHINFO_FILENAME) == $fileCode && file_exists($file->path)
            ? $file : null;
    }

    /**
     * @todo: разные размеры
     * @param $type
     * @return array|null
     */
    protected function getResizeRulesByType($type)
    {
        if (!in_array($type, [300, 600, 1200])) {
            return null;
        }

        return [
            'w' => $type,
            'h' => $type,
        ];
    }
}
