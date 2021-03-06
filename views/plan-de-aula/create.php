<?php
if(@$_SESSION['sesion']=="si")
{ 
	// echo $_SESSION['nombre'];
} 
//si no tiene sesion se redirecciona al login
else
{
	echo "<script> window.location=\"index.php?r=site%2Flogin\";</script>";
	die;
}

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PlanDeAula */

$this->title = 'Agregar plan de aula:';
$this->params['breadcrumbs'][] = ['label' => 'Plan De Aulas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-de-aula-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		'personas' 				=> $personas,
		'periodos'				=> $periodos,
		'niveles' 				=> $niveles,
		'asignaturas' 			=> $asignaturas,
		'estados' 				=> $estados,
		'indicadorDesempenos'	=> $indicadorDesempenos,
    ]) ?>

</div>
