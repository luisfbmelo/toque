<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\CheckboxColumn;
use yii\helpers\Url;
use common\models;
use yii\helpers\ArrayHelper;

use common\models\Stores;
use common\models\Status;
use common\models\Repair;
use common\models\Client;


setlocale(LC_ALL, "pt_BR", "pt_BR.iso-8859-1", "pt_BR.utf-8", "portuguese");
date_default_timezone_set('Atlantic/Azores');

//get user group and define buttons
if (\Yii::$app->session->get('user.group')!=3){ 
    $template = '{view}{update}{delete}';
}else{
    $template = '{view}{update}';
}


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Consulta rápida';
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="col-lg-10 col-xs-12 col-sm-8 col-md-8">
    <div class="row hidden-print">
        <div class="col-lg-2 col-xs-4 col-sm-4 col-md-4">
            <a href="<?php echo Url::to(['repair/create']); ?>" class="topBtn">
                <div class="btnEl">

                    <?php //Html::a('Nova reparação', ['create'], ['class' => 'btn btn-success']) ?>
                    <div class="glyphicon glyphicon-pencil"></div>   
                    <div class="bButtonTitle">Nova reparação</div>

                </div>
                
            </a>
        </div>
        <div class="col-lg-2 col-xs-4 col-sm-4 col-md-4">
            <a href="<?php echo Url::to(['repair/search']); ?>" class="topBtn">
                <div class="btnEl">

                    <?php //Html::a('Nova reparação', ['create'], ['class' => 'btn btn-success']) ?>
                    <div class="glyphicon glyphicon-search"></div>   
                    <div class="bButtonTitle">Pesquisar</div>

                </div>
            </a>
        </div>
    </div>

    <div class="row hidden-print">
        <div class="col-lg-12">
             <div class="repair-index">
                <h1 class="sectionTitle"><?= Html::encode($this->title) ?></h1>  

                <!-- <input type="button" value="Eliminar" class="btn btn-danger deleteBtn"/> -->  
                <div class="btn btn-danger deleteBtn">
                    <span class="glyphicon glyphicon-trash"></span>
                </div>

                <?php if (isset($_GET['SearchRepair'])){?>
                    <a href="<?php echo Yii::$app->request->baseUrl;?>/repair/fastsearch" class="btn btn-default clearBtn">
                        <span>Limpar</span>
                    </a>
                    <div class="clear"></div>
                <?php } ?>

                <?php 
                    echo GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            //['class' => 'yii\grid\SerialColumn'],
                            ['class' => CheckboxColumn::className()],
                            
                            'id_repair',

                            [
                                'attribute' => 'equip',
                                'label' => 'Equipamento',
                                'content' => function($model, $index, $dataColumn) {
                                    return $model->getEquipName()["equipDesc"];
                                },                           
                                
                            ],
                            [
                                'attribute' => 'model',
                                'label' => 'Modelo',
                                'content' => function($model, $index, $dataColumn) {
                                    return $model->getModelName()["modelName"];
                                },                           
                                
                            ],
                            'repair_done_desc:ntext',
                            [
                                //data from other modules tables
                                'attribute' => 'client',
                                'label' => 'Cliente',
                                'value' => 'client.cliName'

                            ],
                            [
                                'attribute' => 'total',
                                'label' => 'Total',
                                'content' => function($model, $index, $dataColumn) {
                                    return (!empty($model->total)) ? '<span style="font-weight:bold;">'.$model->total.' €</span>' : "";
                                },                           
                                
                            ],
                            [
                                'attribute' => 'status_id',
                                'label' => 'Estado',
                                'filter' => ArrayHelper::map(status::find()->where(['status'=>1])->asArray()->orderBy('id_status ASC')->all(), 'id_status','statusDesc'),
                                'content' => function($model, $index, $dataColumn) {
                                    return $status = "<div class='status-color'><span class='circle' style='background-color:#".$model->getStatusDesc()['color'].";'></span><span>".$model->getStatusDesc()["statusDesc"]."</span><span class='clearAll'></span></div>";
                                },                           
                                
                            ],


                            ['class' => 'yii\grid\ActionColumn',
                                'template' => $template,
                                'buttons'=>[
                                    'recover' => function ($url, $model) {     
                                        return Html::a('<span class="glyphicon glyphicon-repeat"></span>', ['recover', 'id' => $model->id_repair], [
                                                'data' => [
                                                    'confirm' => 'Tem a certeza que deseja recuperar esta reparação?',
                                                    'method' => 'post',
                                                ],
                                            ]);                             
                                    }
                                ],
                            ],
                        ],

                        /*'rowOptions' => function ($model, $index, $widget, $grid){
                            return ['class' => 'status_'.$model->status_id];
                        },*/
                        'filterModel' => $searchModel,
                        'headerRowOptions' =>['class'=>'listHeader'],
                        'options' => [
                            'class' => 'grid_listing',
                        ]
                    ]);
                    

                 ?>

            </div>
        </div>
    </div>
   
</section>

<script>
    $(document).ready(function(){
        $(".deleteBtn").click(function(){
            var urlBase = '<?php echo Yii::$app->request->baseUrl;?>';
            var urlDest = urlBase+'/repair/delajax';

            //get all selected elements
            var idList = $('input[type=checkbox][name="selection\\[\\]"]:checked').map(function () {
                return $(this).val();
            }).get();
            //var idList = $("input[type=checkbox]:checked").val();
            console.log(idList);
            //if exists
            if(idList!="")
            {
                if(confirm("Deseja realmente excluir este item?"))
                {
                    $.ajax({
                        url: urlDest,
                        type:"POST",
                        dataType: 'json',
                        data:{ list: idList},
                        success: function(data){
                            console.log(data);
                            if (data=="done"){
                                $(".overlay").css("display","block");
                                $(".ajaxSucc").css("display","block");
                                $(".ajaxSucc").delay(2000).fadeOut(500,function(){
                                    window.location = window.location.href;
                                });
                            }
                        },
                        error: function(){

                        }
                    });
                }
            }
        });

    $(".recoverBtn").click(function(){
            var urlBase = '<?php echo Yii::$app->request->baseUrl;?>';
            var urlDest = urlBase+'/repair/recoverajax';

            //get all selected elements
            var idList = $('input[type=checkbox][name="selection\\[\\]"]:checked').map(function () {
                return $(this).val();
            }).get();
            //var idList = $("input[type=checkbox]:checked").val();
            console.log(idList);
            //if exists
            if(idList!="")
            {
                if(confirm("Deseja realmente recuperar este item?"))
                {
                    $.ajax({
                        url: urlDest,
                        type:"POST",
                        dataType: 'json',
                        data:{ list: idList},
                        success: function(data){
                            console.log(data);
                            if (data=="done"){
                                $(".overlay").css("display","block");
                                $(".ajaxSuccRec").css("display","block");
                                $(".ajaxSuccRec").delay(2000).fadeOut(500,function(){
                                    window.location = window.location.href;
                                });
                            }
                        },
                        error: function(){

                        }
                    });
                }
            }
        });
    });
</script>