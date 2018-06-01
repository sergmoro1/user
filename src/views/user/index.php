<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use sergmoro1\user\Module;


use sergmoro1\lookup\models\Lookup;

$this->title = Module::t('core', 'Users');

?>
<div class="post-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            [
				'attribute' => 'id',
				'options' => ['style' => 'width:4%;'],
			],
			[
				'header' => 'thumb',
				'format' => 'html',
				'value' => function($data) {
					return Html::img($data->getImage('thumb'), ['class' => 'img-responsive']);
				}
			],
			'name',
			'email',
			[
				'attribute' => 'status',
				'filter' => Lookup::items('UserStatus'),
				'value' => function($data) {
					return Lookup::item('UserStatus', $data->status);
				}
			],
			[
				'attribute' => 'group',
				'filter' => Lookup::items('UserRole'),
				'value' => function($data) {
					return Lookup::item('UserRole', $data->group);
				}
			],
            [
				'attribute' => 'created_at',
				'value' => function($data) {
					return date('d.m.y', $data->created_at);
				},
				'options' => ['style' => 'width:9%;'],
			],
            [
				'class' => 'yii\grid\ActionColumn',
				'template' => '{update}{delete}', 
				'options' => ['style' => 'width:6%;'],
			],
        ],
    ]); ?>

</div>
