<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ResultadosPruebasExternas */

$this->title = 'Actualizar Resultados Pruebas Externas:';
$this->params['breadcrumbs'][] = ['label' => 'Resultados Pruebas Externas', 'url' => ['index']];

$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="resultados-pruebas-externas-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		'estados'	=> $estados,
		'institucion'=>$institucion,
    ]) ?>

</div>
