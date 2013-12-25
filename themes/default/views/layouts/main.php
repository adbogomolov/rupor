<?php $this->beginContent('//layouts/blank'); ?>
<div id="all">
	<div id="header">
	<div class="wrapper">
		<div id="top">
			<?php if (Yii::app()->user->isAuthenticated()): ?>
			<?php
				$user = Yii::app()->user->getModel();
			?>
			<ul id="usermenu">
				<?php /* <li><?php echo CHtml::link('Мои сообщения', array('/user/messages')); ?><b>2</b></li> */ ?>
				<?php if (Yii::app()->user->checkAccess(User::ROLE_ADMIN)): ?>
				<li><?php echo CHtml::link('Администрирование', array('/admin')); ?></li>
				<?php endif; ?>
				<li><?php echo CHtml::link('Настройки', array('/user/settings')); ?></li>
			</ul>
			<div id="userbar">
				<div class="badges">
					<?php if ($user->badges): ?>
					<?php foreach ($user->badges as $badge): ?>
					<?php // echo CHtml::link($badge); ?>
					<?php endforeach; ?>
					<?php endif; ?>
				</div>
				<div class="info">
					<div class="ava"><?php echo CHtml::link($user->getAvatar(), array('/user/index')); ?></div>
					<div class="username"><?php echo CHtml::link($user->getName(false), array('/user/index')); ?></div>
					<div class="progress"><div style="width:<?php echo $user->getRating(); ?>%"></div></div>
				</div>
				<?php echo CHtml::link('Выйти', array('/user/logout')); ?>
			</div>
			<?php else: ?>
			<div id="userbar">
				<div class="auth">
					<?php // echo CHtml::link('Войти', array('/user/login'), array('onclick'=>'Rupor.box.auth({href:"#auth_login"});return false;')); ?>
					<?php echo CHtml::link('Войти', array('/user/login')); ?>
					<?php echo CHtml::link('Регистрация', array('/user/register')); ?>
				</div>
			</div>
			
			<?php /*
			<div id="auth_remind" style="display:none">
				<form class="form">
					<div class="row">
						<input type="text" name="email" placeholder="Е-маил" />
					</div>
					<div class="row buttons">
						<button type="submit" class="btn">Отправить</button>
						или
						<a href="#" onclick="Rupor.box.auth({href:'#auth_login'});return false">Войти</a>
					</div>
				</form>
			</div>
			
			<div id="auth_login" class="form" style="display:none">
				<div class="form">
					<?php
						$model = new FormLogin();
						$form = $this->beginWidget('CActiveForm', array(
							// 'id' => 'FormLogin',
							'action' => array('/user/login'),
							// 'enableAjaxValidation' => true,
							'htmlOptions'=> array(
								'autocomplete'=>'off'
							),
							// 'clientOptions'=> array(
								// 'validateOnSubmit'=>true,
								// 'validateOnChange'=>false,
							// )
						));
					?>
					<div class="row">
						<?php echo $form->textField($model, 'email', array('placeholder'=>'Е-маил')) ?>
					</div>
					<div class="row">
						<?php echo $form->passwordField($model, 'password', array('placeholder'=>'Пароль')) ?>
					</div>
					<div class="row">
						<?php echo CHtml::link('Забыли пароль', array('/user/recovery')); ?>
					</div>
					<div class="row buttons">
						<input type="submit" class="btn" value="Войти"/>
					</div>
					<?php /*
					<div class="row social">
						Вход через социальные сети
						<ul>
							<li class="fb"><a href="#">Facebook</a></li>
							<li class="vk"><a href="#">Вконтакте</a></li>
							<li class="tw"><a href="#">Twitter</a></li>
						</ul>
					</div>
					*//* ?>
					<?php $this->endWidget(); ?>
				</div>
			</div>
			*/ ?>
			
			<?php endif; ?>
		</div>
		<ul id="menu">
			<li><?php echo CHtml::link('О проекте', '/promo'); ?></li>
			<li><?php echo CHtml::link('Статистика', array('/stats')); ?></li>
			<li><?php echo CHtml::link('API', array('/page/view', 'url'=>'api')); ?></li>
			<li><?php echo CHtml::link('Помочь проекту', array('/page/view', 'url'=>'help')); ?></li>
			<li><?php echo CHtml::link('Помощь', array('/page/view', 'url'=>'faq')); ?></li>
		</ul>
		<a id="logo" href="/"></a>
		
		<?php if ($this->getPageState('location')): ?>
		<?php echo $this->renderPartial('//partials/location'); ?>
		<?php endif; ?>
		
	</div>
	</div>
	
	<div id="main">
		<?php echo $content; ?>
	</div>
	
</div>

<div id="footer">
<div class="wrapper">
	<ul>
		<li class="copy"><span>&copy; <?php echo date('Y'); ?> Городские решения</span></li>
		<li class="info"><?php echo CHtml::link('Правовая информация', array('/page/view', 'url'=>'legal')); ?></li>
		
		<?php if (!($this->id == 'request' && $this->action->id == 'view')): // Запретил отображение на странице просмотра проблемы (баг с контактом) ?>
		<li class="share">
			<a href="javascript:" rel="tooltip">Рассказать друзьям</a>
			<div style="display:none">
				<?php echo $this->renderPartial('//partials/share'); ?>
			</div>
		</li>
		<?php endif; ?>
		
		<li class="mobile"><?php echo CHtml::link('Мобильные приложения', array('/page/view', 'url'=>'mobile')); ?></li>
		<li class="search">
			<form action="/search">
				<input type="text" name="q" placeholder="Поиск по сайту"/>
			</form>
		</li>
	</ul>
</div>
</div>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter22023709 = new Ya.Metrika({id:22023709,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/22023709" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<?php $this->endContent(); ?>