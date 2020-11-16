<?php


namespace app\controllers;
use yii\data\ActiveDataProvider;
use app\models\Student;
use Yii;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;
use yii\web\NotFoundHttpException;

class StudentController extends BaseController
{
    public function actionIndex(){
        return new ActiveDataProvider([
            'query' => Student ::find()->with('user'),
        ]);
    }
    public function actionCreate(){
        $student = new Student();
        return $this->saveModel($student);
    }
    public function actionUpdate($id){
        $student = $this->findModel($id);
        return $this->saveModel($student);
    }
    public function actionView($id){
        return $this->findModel($id);
    }
    public function findModel($id){
        $student = Student ::findOne($id);
        if ($student === null) {

            throw new NotFoundHttpException("Student with ID $id not found");
        }
        return $student;
    }
    public function saveModel($student){
        if ($student->loadAndSave(Yii::$app->getRequest()->getBodyParams())) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $response->getHeaders()->set('Location',
                Url::toRoute(['view', 'id' => $student->getPrimaryKey()], true));
        } elseif (!$student->hasErrors()) {
            throw new ServerErrorHttpException(serialize($student->getErrors()));
        }
        return $student;
    }
}