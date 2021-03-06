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
/**********
Versión: 001
Fecha: 17-03-2018
Desarrollador: Oscar David Lopez
Descripción: CRUD de ponderacion-resultados
---------------------------------------
Modificaciones:
Fecha: 17-03-2018
Persona encargada: Oscar David Lopez
Cambios realizados: - nombre de los botones 
Envío de variables para mostrar los posibles valores de los periodos
Envío de variables para mostrar los posibles valores de los estados
---------------------------------------
Modificaciones:
Fecha: 27-04-2018
Persona encargada: Oscar David Lopez
Cambios realizados: - miga de pan
---------------------------------------
**********/
use yii\helpers\Html;
use app\models\Sedes;

$idSedes = $model->id_sede;

$nombreSede = Sedes::find()->where('id='.$idSedes)->one();
$idInstitucion = $nombreSede->id_instituciones;
$nombreSede = $nombreSede->descripcion;


/* @var $this yii\web\View */
/* @var $model app\models\PonderacionResultados */

$this->title = 'Actualizar';
$this->params['breadcrumbs'][] = 
	[
		'label' => 'Ponderación de resultados', 
		'url' => [
					'index',
					'idInstitucion' => $idInstitucion, 
					'idSedes' 		=> $idSedes,
				 ]
	];						 
		
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="ponderacion-resultados-update">

    <h1><?= Html::encode($nombreSede) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		'periodos'=>$periodos,
		'estados'=>$estados,
		'idSedes'=>$idSedes,
    ]) ?>
<script>
idPonderacion = <?php echo @$model->id; ?>;

</script>


</div>
