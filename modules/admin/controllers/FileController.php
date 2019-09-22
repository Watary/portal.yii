<?php

namespace app\modules\admin\controllers;

use app\modules\galleries\models\GalleryImages;
use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\modules\admin\models\UploadFiles;

class FileController extends Controller{

    public function actionUpload(){
        $model = new UploadFiles();
        if (Yii::$app->request->isPost) {
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');

            if ($model->upload()) {
                // file is uploaded successfully
                return;
            }
        }

        return;

        //return $this->render('upload', ['model' => $model]);
    }

    /**
     * Завантаження зображень на сервер і зберігання в базу даних адреси на зобраення
     *
     * @param $id
     * @return array
     */
    public function actionAjaxUpload($id){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();

            if( isset( $data['image_upload'] ) ){

                $uploaddir = './files/galleries/gallery_'.$id;

                if( ! is_dir( $uploaddir ) ) mkdir( $uploaddir, 0777 );

                $files      = $_FILES;
                $done_files = array();

                foreach( $files as $file ){
                    $model = new GalleryImages();
                    $model->gallery = $id;
                    $model->path = '/';
                    $model->save();

                    $file_name = "image_$model->id.".pathinfo($file['name'], PATHINFO_EXTENSION);

                    $model->path = $file_name;
                    $model->save();

                    if( move_uploaded_file( $file['tmp_name'], "$uploaddir/$file_name" ) ){
                        $done_files[] = realpath( "$uploaddir/$file_name" );
                    }
                }

                $result['files'] = $done_files ? array('files' => $done_files ) : array('error' => 'Ошибка загрузки файлов.');
                $result['error'] = $done_files ? '' : 'error';

                return $result;
            }
        }
    }
}