<?php

namespace itshkacomua\blog\models;

use common\components\behaviors\StatusBehavior;
use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * This is the model class for table "blog".
 *
 * @property int $id
 * @property string $title
 * @property string $text
 * @property string $alias
 * @property string $image
 * @property int $status_id
 * @property int $sort
 * @property int $create_time
 * @property int $update_time
 */
class Blog extends \yii\db\ActiveRecord
{
    public const GET_ID_BY_URL = [1 => 'Русский', 2 => 'Украинский', 3 => 'Английский'];
    const STATUS_LIST = ['off','on'];
    const IMAGES_SIZE = [
        ['170',null],
        ['800',null],
    ];
    public $tags_array;
    public $file;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['text'], 'string'],
            [['alias'], 'unique'],
            [['status_id', 'sort'], 'integer'],
            [['sort', 'language_id'], 'integer', 'min' => 1, 'max' => 99],
            [['title', 'alias'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 100],
            [['file'], 'image'],
            ['language_id', 'default', 'value' => 1],
            [['tags_array', 'create_time', 'update_time'], 'safe'],
        ];
    }

    public function behaviors()
    {
        return [
            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_time',
                'updatedAtAttribute' => 'update_time',
                'value' => new Expression('NOW()'),
            ],
            'statusBehavior' => [
                'class' => StatusBehavior::className(),
                'statusList' => self::STATUS_LIST,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Заголовок'),
            'text' => Yii::t('app', 'Текст'),
            'alias' => Yii::t('app', 'Алиас'),
            'status_id' => Yii::t('app', 'Статус'),
            'sort' => Yii::t('app', 'Сортировка'),
            'tags_array' => Yii::t('app', 'Теги'),
            'tagsAsString' => Yii::t('app', 'Теги'),
            'author.username' => Yii::t('app', 'Имя автора'),
            'author.email' => Yii::t('app', 'Почта автора'),
            'create_time' => Yii::t('app', 'Время добавления'),
            'update_time' => Yii::t('app', 'Время обновления'),
            'image' => Yii::t('app', 'Изображение'),
            'file' => Yii::t('app', 'Изображение'),
            'language_id' => Yii::t('app', 'Язык'),
        ];
    }

    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getBlogTag()
    {
        return $this->hasMany(BlogTag::className(), ['blog_id' => 'id']);
    }

    public function getImagesLinks()
    {
        return ArrayHelper::getColumn($this->images, 'imageUrl');
    }

    public function getImagesLinksData()
    {
        return ArrayHelper::toArray($this->image, [
                ImageManager::className() => [
                    'caption' => 'name',
                    'key' => 'id',
                ]]
        );
    }

    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])->via('blogTag');
    }

    public function getTagsAsString()
    {
        $arr = ArrayHelper::map($this->tags, 'id', 'name');
        return implode(',',$arr);
    }

    public function getSmallImage()
    {
        if ($this->image) {
            $path = str_replace('admin','',Url::home(true))  . 'uploads/images/blog/50x50/'.$this->image;
        } else {
            $path = str_replace('admin','',Url::home(true))  . 'uploads/images/nophoto.png';
        }

        return $path;
    }

    public function getMidleImage()
    {
        if ($this->image) {
            $path = str_replace('admin','',Url::home(true))  . 'uploads/images/blog/800/'.$this->image;
        } else {
            $path = str_replace('admin','',Url::home(true))  . 'uploads/images/nophoto.png';
        }

        return $path;
    }

    public function getBigImage()
    {
        if ($this->image) {
            $path = str_replace('admin','',Url::home(true))  . 'uploads/images/blog/'.$this->image;
        } else {
            $path = str_replace('admin','',Url::home(true))  . 'uploads/images/nophoto.png';
        }

        return $path;
    }

    public function afterFind()
    {
        parent::afterFind();
        return $this->tags_array = \yii\helpers\ArrayHelper::map($this->tags, 'id', 'id');
    }

    public function beforeSave($insert)
    {
        if ($file = UploadedFile::getInstance($this, 'file')) {
            $dir = Yii::getAlias('@images') . '/blog/';
            $this->image = strtotime('now').'_'.Yii::$app->getSecurity()->generateRandomString(6).'.'.$file->extension;

            if (file_exists($dir.$this->image)) {
                unlink($dir.$this->image);
            }
            if(file_exists($dir.'170x/'.$this->image)) {
                unlink($dir.'170x/'.$this->image);
            }
            if(file_exists($dir.'800x/'.$this->image)) {
                unlink($dir.'800x/'.$this->image);
            }

            $file->saveAs($dir . $this->image);
            $imag = Yii::$app->image->load($dir.$this->image);
            $imag->background('#fff',0);
            $imag->resize('170',null, Yii\image\drivers\Image::INVERSE);
            $imag->save($dir.'170x/'.$this->image, 90);
            $imag = Yii::$app->image->load($dir.$this->image);
            $imag->background('#fff',0);
            $imag->resize('800',null, Yii\image\drivers\Image::INVERSE);
            $imag->save($dir.'800x/'.$this->image, 90);
        }
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $arr = \yii\helpers\ArrayHelper::map($this->tags, 'id', 'id');
        foreach($this->tags_array as $one) {
            if(!in_array($one, $arr)) {
                $model = new BlogTag();
                $model->blog_id = $this->id;
                $model->tag_id = $one;
                $model->save();
            }

            if(isset($arr[$one])) {
                unset($arr[$one]);
            }
        }

        BlogTag::deleteAll(['tag_id' => $arr]);
    }

    public function beforeDelete()
    {
        if(parent::beforeDelete()) {
            $dir = Yii::getAlias('@images') . '/blog/';
            if (file_exists($dir . $this->image)) {
                unlink($dir . $this->image);
            }

            foreach (self::IMAGES_SIZE as $size) {
                $size_dir = $size[0] . 'x';
                if ($size[1] !== null) {
                    $size_dir .= $size[1];
                }

                if (file_exists($dir . $size_dir . '/' . $this->image)) {
                    unlink($dir . $size_dir . '/' . $this->image);
                }
            }
            BlogTag::deleteAll(['blog_id' => $this->id]);
            return true;
        } else {
            return false;
        }
    }

    public function imageDelete($id)
    {
        $model = Blog::findOne($id);
        $dir = Yii::getAlias('@images') . '/blog/';
        if (file_exists($dir . $model->image)) {
            unlink($dir . $model->image);
        }

        foreach (self::IMAGES_SIZE as $size) {
            $size_dir = $size[0] . 'x';
            if ($size[1] !== null) {
                $size_dir .= $size[1];
            }

            if (file_exists($dir . $size_dir . '/' . $model->image)) {
                unlink($dir . $size_dir . '/' . $model->image);
            }
        }

        if (!empty($model)) {
            $model->image = '';

            if ($model->save()) {
                return true;
            }
        } else {
            return \Yii::t('content', 'An error has occurred');
        }
    }
}
