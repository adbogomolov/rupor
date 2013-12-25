<?php 

$linkOptions = array();
$urlBase = '';
if($this->layout == '//layouts/iframe')
{
	$linkOptions['target'] = '_blank';	
	$urlBase = 'http://' . $_SERVER['SERVER_NAME'];
}

if (Yii::app()->user->isGuest): ?>

	<div style="margin:0 0 20px;color:#d43a33">
	<p>В соответствии с <?= CHtml::link('ФЗ №59', $urlBase . '/page/legal', $linkOptions); ?> обязательными к рассмотрению органами государственной власти являются сообщения с указанием фамилии,
			имени и электронного адреса пользователя.</p>
			<font color="black">Ваши фамилия и имя:</font> не определено – необходима <?= CHtml::link('регистрация', $urlBase . '/user/register', $linkOptions); ?>.
			<br/>
			<font color="black">Ваш электронный адрес:</font> не определено – необходима <?= CHtml::link('регистрация', $urlBase . '/user/register', $linkOptions); ?>.
	</div>

<?php else: ?>

	<?php $user = Yii::app()->user->model; ?>
	<?php if (!$user->first_name || !$user->last_name): ?>
	<div style="margin:0 0 20px;color:#d43a33">
		<p>В соответствии с <?= CHtml::link('ФЗ №59', $urlBase . '/page/legal', $linkOptions); ?> обязательными к рассмотрению органами государственной власти являются сообщения с указанием фамилии,
		имени и электронного адреса пользователя.</p>
		<font color="black">Ваши фамилия и имя:</font> не полные данные – необходимо заполнить в <?= CHtml::link('настройках', $urlBase . '/user/settings', $linkOptions); ?>.
	</div>
	<?php endif; ?>
	
<?php endif; ?>