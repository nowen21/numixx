<?php

namespace app\modules\asistencial\formulacion\controllers;

use yii\web\Controller;

/**
 * Default controller for the `datospaciente` module
 */
class FormulacionController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
