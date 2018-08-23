<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EjecucionFase */

$this->title = 'Actualizar';
$this->params['breadcrumbs'][] = ['label' => 'Ejecucion Fases', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = "Actualizar";
?>
<div class="ejecucion-fase-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
