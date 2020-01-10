<?php

namespace console\controllers;

use yii\console\Controller;
use yii\console\ExitCode;
use Faker\Factory;
use Bluemmb\Faker\PicsumPhotosProvider;
use common\helpers\Html;
use common\models\Language;
use common\models\Page;
use common\models\PostArticle;
use backend\models\PageForm;
use backend\models\PostArticleForm;

/**
 * Class UtilController – Набор помогаек
 *
 * @package console\controllers
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class UtilController extends Controller
{
    /**
     * @return int
     * @throws \Throwable
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     */
    public function actionCreateRandomPages()
    {
        exit('Delete if sure');
        foreach (Page::find()->withoutRoot()->sort()->all() as $p) {
            $p->delete();
        }
//        exit;

        $fakerRu = Factory::create('ru_RU');
        $fakerKz = Factory::create('kk_KZ');
        $fakerEn = Factory::create('en_US');

        $fakerRu->addProvider(new PicsumPhotosProvider($fakerRu));
        $fakerKz->addProvider(new PicsumPhotosProvider($fakerKz));
        $fakerEn->addProvider(new PicsumPhotosProvider($fakerEn));

        $fContent = function ($faker) {
            $content = '';
            for ($aa = 0, $bb = rand(1, 15); $aa < $bb; $aa++) {
                if (rand(1, 10) > 9) {
                    $imgSrc = $faker->imageUrl(1200, 900, rand(1, 1084));
                    $content .= sprintf('<figure><img src="%s"></figure>', $imgSrc);
                } elseif (rand(1, 10) > 9) {
                    $iframeSrc = '//www.youtube.com/embed/' . $this->getRandomYoutubeCode();
                    $content .= sprintf('<figure><iframe  src="%s" frameborder="0" allowfullscreen=""></iframe></figure>', $iframeSrc);
                } else {
                    $content .= sprintf('<p>%s</p>', $faker->realText(rand(10, 1000)));
                }
            }

            return $content;
        };

        $ru = $this->createPage('ru');
        $this->stdout("created page: $ru->name\n");

        $kz = $this->createPage('kz');
        $this->stdout("created page: $kz->name\n");

        $en = $this->createPage('en');
        $this->stdout("created page: $en->name\n");

        $fFaker = function (Page $page) use ($ru, $kz, $en, $fakerRu, $fakerKz, $fakerEn) {
            $page->refresh();

            $ru->refresh();
            if ($page->id == $ru->id || $page->isChildOf($ru)) {
                return $fakerRu;
            }

            $kz->refresh();
            if ($page->id == $kz->id || $page->isChildOf($kz)) {
                return $fakerKz;
            }

            $en->refresh();
            if ($page->id == $en->id || $page->isChildOf($en)) {
                return $fakerEn;
            }
        };

        $fAddPages = function ($fAddPages, Page $page, $limit, $level = 1) use ($fFaker, $fContent) {
            $faker = $fFaker($page);
            for ($a = 0; $a < $limit; $a++) {
                $newPage = $this->createPage($faker->realText(rand(20, 40)), null, $fContent($faker));
                $newPage->appendTo($page);
                $this->stdout("created page: $newPage->name\n");

                if ($level < 4 && rand(0, 10) > 7) {
                    $fAddPages($fAddPages, $newPage, rand(1, 5), $level + 1);
                }
            }
        };

        $aboutRuPage = $this->createPage('О проекте', 'about', $fContent($fakerRu));
        $aboutRuPage->appendTo($ru);
        $this->stdout("created page: $aboutRuPage->name\n");
        $fAddPages($fAddPages, $aboutRuPage, rand(0, 5));

        $aboutKzPage = $this->createPage('[kz] О проекте', 'about', $fContent($fakerKz));
        $aboutKzPage->appendTo($kz);
        $this->stdout("created page: $aboutKzPage->name\n");
        $fAddPages($fAddPages, $aboutKzPage, rand(0, 5));

        $aboutEnPage = $this->createPage('[en] О проекте', 'about', $fContent($fakerEn));
        $aboutEnPage->appendTo($en);
        $this->stdout("created page: $aboutEnPage->name\n");
        $fAddPages($fAddPages, $aboutEnPage, rand(0, 5));

        $fAddPages($fAddPages, $ru, rand(1, 15));
        $fAddPages($fAddPages, $kz, rand(1, 15));
        $fAddPages($fAddPages, $en, rand(1, 15));

        return ExitCode::OK;
    }

    /**
     * @param string $name
     * @param string|null $slug
     * @param string|null $body
     * @return Page
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    protected function createPage(string $name, string $slug = null, string $body = null): Page
    {
        $page = PageForm::createTmp();
        $page->name = $name;
        $page->slug = $slug === null ? Html::slug($page->name) : $slug;
//        $page->title = '';
        $page->body = $body;
        $page->visible = true;
        $page->setUpdatedAttributes();
        $page->changeStatusToActive();
        $page->saveWithException();

        return $page;
    }

    /**
     * @return int
     * @throws \Throwable
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     */
    public function actionCreateRandomPosts()
    {
        exit('Delete if sure');
        $limit = 200;

        foreach (PostArticle::find()->all() as $p) {
            $p->refresh();
            $p->delete();
        }
//        exit;

        $fakerRu = Factory::create('ru_RU');
        $fakerKz = Factory::create('kk_KZ');
        $fakerEn = Factory::create('en_US');

        $fakerRu->addProvider(new PicsumPhotosProvider($fakerRu));
        $fakerKz->addProvider(new PicsumPhotosProvider($fakerKz));
        $fakerEn->addProvider(new PicsumPhotosProvider($fakerEn));


        for ($a = 0, $b = $limit; $a < $b; $a++) {
            $postArticle = PostArticleForm::createTmp();

            $postArticleForm = new PostArticleForm(['postArticle' => $postArticle]);
            $postArticleForm->initLangForms();
            $postArticleForm->setScenarioCreate();

            $forms = $postArticleForm->getLangForms();
            $forms[$postArticleForm->postArticle->lang_id] = $postArticleForm;

            $publish_at = strtotime(sprintf('NOW - %d DAYS', $limit - ($a + 1 - (int)(rand(1, 10) == 10))));
            foreach ($forms as $form) {

                $faker = null;
                if ($form->postArticle->lang_id === Language::ID_RU) {
                    $faker = $fakerRu;
                }
                if ($form->postArticle->lang_id === Language::ID_KZ) {
                    $faker = $fakerKz;
                }
                if ($form->postArticle->lang_id === Language::ID_EN) {
                    $faker = $fakerEn;
                }

                $content = '';
                for ($aa = 0, $bb = rand(1, 15); $aa < $bb; $aa++) {
                    if (rand(1, 10) > 9) {
                        $imgSrc = $faker->imageUrl(1200, 900, rand(1, 1084));
                        $content .= sprintf('<figure><img src="%s"></figure>', $imgSrc);
                    } elseif (rand(1, 10) > 9) {
                        $iframeSrc = '//www.youtube.com/embed/' . $this->getRandomYoutubeCode();
                        $content .= sprintf('<figure><iframe  src="%s" frameborder="0" allowfullscreen=""></iframe></figure>', $iframeSrc);
                    } else {
                        $content .= sprintf('<p>%s</p>', $faker->realText(rand(10, 1000)));
                    }
                }

                $form->name = $faker->realText(rand(10, 200));
                $form->slug = \common\helpers\Html::slug($form->name);
//                $form->announce = sprintf('<p>%s</p>', $faker->realText(rand(50, 255)));
                $form->content = $content;
                $form->main_image_url = $faker->imageUrl(1200, 900, rand(1, 1084));
                $form->publish_date = date(PostArticleForm::PUBLISH_DATE_FORMAT, $publish_at);
                $form->publish_time = date(PostArticleForm::PUBLISH_TIME_FORMAT, $publish_at);
                $form->visible = true;
            }

            $postArticleForm->save();

            echo $postArticleForm->name;

            echo PHP_EOL;
        }

        return ExitCode::OK;
    }

    protected function getRandomYoutubeUrl()
    {
        $map = [
            'https://www.youtube.com/watch?v=qshhI6HDibc',
            'https://www.youtube.com/watch?v=D_9HWkyUyIU',
            'https://www.youtube.com/watch?v=I8LjqF-VJYo',
            'https://www.youtube.com/watch?v=RVD3ZFWBN5c',
            'https://www.youtube.com/watch?v=mZzdwXZeFPQ',
            'https://www.youtube.com/watch?v=VNvs-2AhFSc',
            'https://www.youtube.com/watch?v=YLbPxqYZlTs',
            'https://www.youtube.com/watch?v=u8Odr4i4ZhE',
            'https://www.youtube.com/watch?v=YtCcv2gd7_g',
            'https://www.youtube.com/watch?v=WTXPXMJEUYI',
            'https://www.youtube.com/watch?v=E9bpyQzZfU8',
            'https://www.youtube.com/watch?v=87Seb7P-5aY',
            'https://www.youtube.com/watch?v=4g924St8JK0',
            'https://www.youtube.com/watch?v=prML54L6CpU',
            'https://www.youtube.com/watch?v=l-UkgAprquw',
            'https://www.youtube.com/watch?v=DO8DSl3VR-c',
            'https://www.youtube.com/watch?v=C1sqpHyXHbw',
            'https://www.youtube.com/watch?v=Pw0JrZcORDk',
            'https://www.youtube.com/watch?v=5HJmfn-2Amc',
            'https://www.youtube.com/watch?v=TLcHLWO8zNM',
            'https://www.youtube.com/watch?v=dy1rmbEzYmc',
        ];

        shuffle($map);

        return array_shift($map);
    }

    /**
     * @return string|string[]|null
     */
    protected function getRandomYoutubeCode()
    {
        return preg_replace('/^[^=]+=/', '', $this->getRandomYoutubeUrl());
    }
}
