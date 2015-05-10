<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator dee\angular\generators\crud\Generator */

$controllerClass = StringHelper::basename($generator->getControllerClass());
$modelClass = StringHelper::basename($generator->modelClass);

$class = $generator->modelClass;
$pks = $class::primaryKey();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->getControllerClass(), '\\')) ?>;

use Yii;
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
<?php if($generator->alsoAsRest):?>
use <?= ltrim($generator->modelClass, '\\') ?>;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
<?php endif;?>

/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{
<?php if($generator->alsoAsRest):?>
    /**
     * @inheritdoc
     */
    public $enableCsrfValidation = false;
<?php endif;?>
    
    /**
     * Display main page.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('main');
    }
<?php if($generator->alsoAsRest):?>
    
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'resource' => [
                'class' => 'dee\angular\RestAction',
            ]
        ];
    }

    /**
     * Lists all <?= $modelClass ?> models.
     * @return mixed
     */
    public function query()
    {
        $query = <?= $modelClass ?>::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

    /**
     * Displays a single <?= $modelClass ?> model.
     * @param string $id the ID of the model to be loaded. If the model has a composite primary key,
     * the ID must be a string of the primary key values separated by commas.
     * The order of the primary key values should follow that returned by the `primaryKey()` method
     * of the model.
     * @return mixed
     */
    public function view($id)
    {
        return $this->findModel($id);
    }

    /**
     * Creates a new <?= $modelClass ?> model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function create()
    {
        $model = new <?= $modelClass ?>();

        $model->load(Yii::$app->request->post(),'');
        $model->save();
        
        return $model;
    }

    /**
     * Updates an existing <?= $modelClass ?> model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id the ID of the model to be loaded. If the model has a composite primary key,
     * the ID must be a string of the primary key values separated by commas.
     * The order of the primary key values should follow that returned by the `primaryKey()` method
     * of the model.
     * @return mixed
     */
    public function update($id)
    {
        $model = $this->findModel($id);

        $model->load(Yii::$app->request->post(),'');
        $model->save();

        return $model;
    }

    /**
     * Deletes an existing <?= $modelClass ?> model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id the ID of the model to be loaded. If the model has a composite primary key,
     * the ID must be a string of the primary key values separated by commas.
     * The order of the primary key values should follow that returned by the `primaryKey()` method
     * of the model.
     * @return mixed
     */
    public function delete($id)
    {
        return $this->findModel($id)->delete();
    }

    /**
     * Finds the <?= $modelClass ?> model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id the ID of the model to be loaded. If the model has a composite primary key,
     * the ID must be a string of the primary key values separated by commas.
     * The order of the primary key values should follow that returned by the `primaryKey()` method
     * of the model.
     * @return <?= $modelClass ?> the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
<?php if(count($pks) > 1): ?>
        $keys = <?= $modelClass ?>::primaryKey();
        $values = explode(',', $id);
        if (count($keys) === count($values)) {
            $model = <?= $modelClass ?>::findOne(array_combine($keys, $values));
        }else{
            $model = null;
        }
        if ($model !== null) {
<?php else: ?>
        if (($model = <?= $modelClass ?>::findOne($id)) !== null) {
<?php endif;?>
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
<?php endif;?>
}
