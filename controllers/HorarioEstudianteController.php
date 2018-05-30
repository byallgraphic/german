<?php

/**********
Versión: 001
Fecha: 30-05-2018
Desarrollador: Oscar David Lopez
Descripción: horario estudiantes
---------------------------------------
Modificaciones:
Fecha: 30-05-2018
Persona encargada: Oscar David Lopez
Cambios realizados: - funcion ActionIndex se modifica para que muestre el horario del estudiante con datatables
---------------------------------------
**********/


namespace app\controllers;

use Yii;
use app\models\HorarioEstudiante;
use app\models\HorarioEstudianteBuscar;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use app\models\HorarioDocente;
use	yii\helpers\Json;
/**
 * HorarioEstudianteController implements the CRUD actions for HorarioEstudiante model.
 */
class HorarioEstudianteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all HorarioEstudiante models.
     * @return mixed
     */
	 
	//Horario del estudiante con datatables 
    public function actionIndex()
    {					
		$idSede = $_SESSION['sede'][0];
		$idPerfilesXPersona = $_SESSION['perfilesxpersonas'];
		//variable con la conexion a la base de datos  pe.id=10 es el perfil docente
		$connection = Yii::$app->getDb();
		
		//que materias se dan y en que dias en la sede actual
		$command = $connection->createCommand("
			select da.id , d.descripcion as dias, b.descripcion as bloques, a.descripcion as asignatura,
			pa.descripcion as grupo, au.descripcion as aula
			from distribuciones_academicas as da, asignaturas_x_niveles_sedes as ans, sedes_niveles as sn, dias as d, bloques as b,
			distribuciones_x_bloques_x_dias as dbd, sedes_x_bloques as sb, asignaturas as a, personas as p,perfiles_x_personas as pp,
			paralelos as pa, aulas as au, estudiantes as e
			where da.id_asignaturas_x_niveles_sedes = ans.id
			AND sn.id = ans.id_sedes_niveles
			AND sn.id_sedes = $idSede
			AND da.estado = 1
			AND dbd.id_distribuciones_academicas = da.id
			AND dbd.id_dias = d.id
			AND dbd.id_bloques_sedes = sb.id
			AND sb.id_bloques = b.id
			AND ans.id_asignaturas= a.id
			AND da.id_perfiles_x_personas_docentes = pp.id
			and pp.id_personas=p.id
			and da.id_paralelo_sede= pa.id
			and p.estado = 1
			and pa.estado= 1
			and da.estado= 1
			and da.id_aulas_x_sedes = au.id
			and e.id_paralelos = pa.id
			and e.id_perfiles_x_personas = $idPerfilesXPersona");
		$result = $command->queryAll();
		
			$command = $connection->createCommand("SELECT id, descripcion
			FROM dias
			where estado  =1
			order by id");
			$dias = $command->queryAll();
			
			
			$command = $connection->createCommand("
			SELECT b.id, b.descripcion
			FROM bloques as b, sedes_x_bloques as sb 
			where b.estado  =1
			and sb.id_sedes =$idSede
			and sb.id_bloques = b.id
			order by id asc");
			$bloques = $command->queryAll();
			
			$arrayHorario=array();
			//se crea un array con los bloque de la sede VS los dias de la semana con el valor no asignado
			foreach ($dias as $dia)
			{
				foreach ($bloques as $bloque)
				{
					//$bloque['descripcion'] nombre del bloque 
					//$dia['descripcion'] dia de la semana
					$arrayHorario[$bloque['descripcion']][$dia['descripcion']]="---";
				}
			}			
			
			//en la ubicacion bloque - dia se pone el nombrede la asignatura - group - aula que da ese docente $idDocente
			foreach($result as $r)
			{
				
				$arrayHorario[$r['bloques']][$r['dias']]=$r['asignatura']." |".$r['grupo']."|".$r['aula'];
			}
			
			
			//se construye el formato para llenar el dataTable
			
			// $data = 
			// [
					//["bloques"=>" BLOQUE "],["LUNES"=>"LUNES"],["MARTES"=>"MARTES"],
					//["bloques"=>" BLOQUE 1 "],["LUNES"=>"Mate"],["MARTES"=>"naturales"],
					//"bloques"=>" BLOQUE 2"],["LUNES"=>"naturales"],["MARTES"=>"mate"],
			// ];
			
			
			$datos=array();
			$datos[0]=["BLOQUE" => "  ","LUNES"=>"","MARTES"=>"","MIERCOLES"=>"","JUEVES"=>"","VIERNES"=>"","SABADO"=>"","DOMINGO"=>""];
			
			$cont =1;
			foreach($arrayHorario as $arrayHorarioJson=>$valor) 
			{
				$datos[$cont]=$valor; 
				$datos[$cont]['BLOQUE']=$arrayHorarioJson; 
				
				
				$cont++;
			}
			
		$dataProvider = new ArrayDataProvider([
			'allModels' => $datos,
		]);
		
		
        
        return $this->render('index', [
            // 'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
		
    
	}

    /**
     * Displays a single HorarioEstudiante model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new HorarioEstudiante model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new HorarioEstudiante();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing HorarioEstudiante model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing HorarioEstudiante model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the HorarioEstudiante model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return HorarioEstudiante the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HorarioEstudiante::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	//se formate la salida de un array
	public function pre($valor)
	{
		echo "<pre>"; print_r( $valor); echo "</pre>";	
	}

}
