<?php

$View = View::getInstance();


$query = $View->getVar('query_params');
$current = $View->getVar('paging.current');
$total = $View->getVar('paging.total');

$sort = $View->getVar('order.field');
$dir = $View->getVar('order.dir');

$fields = [
	[ 'title' => 'Имя', 'name' => 'user_name' ],
	[ 'title' => 'E-mail', 'name' => 'email' ],
	[ 'title' => 'Домашняя страница', 'name' => '' ],
	[ 'title' => 'Сообщение', 'name' => '' ],
	[ 'title' => 'Дата', 'name' => 'date_created' ]
];

?>


<div class="col-md-7">
	<h3>Список сообщений</h3>
	<table class="table">
		<thead>
			<tr>
				<?php foreach ($fields as $field) { ?>
					<th>
						<?php if ($field['name']) { ?>
							<a href="?<?=http_build_query(array_merge($query, [
								'sort' => $field['name'],
								'dir' => $sort === $field['name'] ? ($dir === 'DESC' ? 'ASC' : 'DESC') : 'ASC'
							]))?>">
						<?php } ?>
						<?=$field['title']?>
						<?php if ($field['name']) { ?>
							<?php if ($field['name'] === $sort) { ?>
								<i class="glyphicon glyphicon-triangle-<?=($dir === 'DESC' ? 'bottom' : 'top')?>"></i>
							<?php } ?>
							</a>
						<?php } ?>
					</th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($View->getVar('posts_data') as $post) { ?>
				<tr>
					<td><?=$post['user_name']?></td>
					<td><?=$post['email']?></td>
					<td><?=$post['homepage']?></td>
					<td><?=$post['text']?></td>
					<td><?=date('Y-m-d H:i:s', $post['date_created'])?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<?php if ($total > 1) { ?>
		<nav>
			<ul class="pagination">
				<?php if ($current > 1) { ?>
					<li>
						<a href="?<?=http_build_query(array_merge($query, [ 'page' => $current - 1 ]))?>" aria-label="Назад">
							<span aria-hidden="true">&laquo;</span>
						</a>
					</li>
				<?php } else { ?>
					<li>
						<span aria-hidden="true">&laquo;</span>
					</li>
				<?php } ?>

				<?php for ($i = 1; $i <= $total; ++$i) { ?>
					<li<?php if ($i === $current) { ?> class="active"<?php } ?>>
						<a href="?<?=http_build_query(array_merge($query, [ 'page' => $i ]))?>"><?=$i?></a>
					</li>
				<?php } ?>

				<?php if ($current < $total) { ?>
					<li>
						<a href="?<?=http_build_query(array_merge($query, [ 'page' => $current + 1 ]))?>" aria-label="Вперед">
							<span aria-hidden="true">&raquo;</span>
						</a>
					</li>
				<?php } else { ?>
					<li>
						<span aria-hidden="true">&raquo;</span>
					</li>
				<?php } ?>
			</ul>
		</nav>
	<?php } ?>
</div>



<div class="col-md-5">
	<h3>Оставьте сообщение</h3>
	<?php if ($View->getVar('submit.status') === false) { ?>
		<div class="alert alert-danger" role="alert">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			Неверно заполнена форма.
		</div>
	<?php } ?>
	<?php if ($View->getVar('submit.status') === true) { ?>
		<div class="alert alert-success" role="alert">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			Форма успешно отправлена
		</div>
	<?php } ?>
	<form method="post" action="" role="form">
		<input type="hidden" name="csrf" value="<?=$View->getVar('csrf_protected')?>">
		<div class="form-group<?=($View->hasVar('field.error.user_name') ? ' has-error' : '')?>">
			<label for="user_name">Имя:</label>
			<input type="text" name="user_name" class="form-control" id="user_name" value="<?=$View->getVar('field.value.user_name')?>">
		</div>
		<div class="form-group<?=($View->hasVar('field.error.email') ? ' has-error' : '')?>">
			<label for="email">E-mail:</label>
			<input type="email" name="email" class="form-control" id="email" value="<?=$View->getVar('field.value.email')?>">
		</div>
		<div class="form-group<?=($View->hasVar('field.error.homepage') ? ' has-error' : '')?>">
			<label for="homepage">Домашняя страница:</label>
			<input type="text" name="homepage" class="form-control" id="homepage" value="<?=$View->getVar('field.value.homepage')?>">
		</div>
		<div class="form-group<?=($View->hasVar('field.error.text') ? ' has-error' : '')?>">
			<label for="text">Текст:</label>
			<textarea name="text" id="text" class="form-control"><?=$View->getVar('field.value.text')?></textarea>
		</div>
		<div class="row">
			<div class="col-md-3">
				<img src="?captcha&<?=rand()?>" class="captcha-img">
			</div>
			<div class="col-md-6">
				<div class="form-group<?=($View->hasVar('field.error.captcha') ? ' has-error' : '')?>">
					<label for="captcha">Введите текст с картинки:</label>
					<input type="text" name="captcha" class="form-control" id="captcha">
				</div>
			</div>
		</div>
		<button type="submit" class="btn btn-default">Отправить</button>
	</form>
</div>