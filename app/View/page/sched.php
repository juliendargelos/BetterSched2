<p class="ee">
	<?php
		$hey = [
			'Coucou toi',
			'Oula, t\'as une sale tête aujourd\'hui',
			'Salut beauté',
			'Wanna some schedule ?',
			'Comme dit Kaaris, "Ton boul ne sent pas le jasmin"'
		];
	?>
	<?= $hey[array_rand($hey)]; ?>
</p>
<?= partial('header') ?>
<form>
	<div>
		<?=
			select('Filière', 'sched-group', $groups, function($group, $value, &$option) use($default) {
				$option = [$group['label'] => $value];
				return $value == $default['group'];
			});
		?>
	</div>
	<div>
		<?= select('Année', 'sched-year', $years, $default['year']); ?>
	</div>
	<div>
		<?=
			select('Semaine', 'sched-week', $weeks, function($week, $value, &$option) use($default) {
				$option = [$value.' ('.$week->begin.' → '.$week->end.')' => $value];
				return $value == $default['week'];
			});
		?>
	</div>
	<div class="filters"></div>
</form>

<main class="sched">
	<section class="hours">
		<?php $middleMinute = 60/(60/$minuteInterval/2); ?>

		<?php for($hour = $hourBegin; $hour <= $hourEnd; $hour++): ?>
			<div class="hour"><span><?= $hour ?>h</span></div>

			<?php if($hour < $hourEnd): ?>
				<?php for($minute = $minuteInterval; $minute < 60; $minute += $minuteInterval): ?>
					<?php if($minute == $middleMinute): ?>
						<div class="hour minute middle"><span><?= $minute ?></span></div>
					<?php else: ?>
						<div class="hour minute"></div>
					<?php endif; ?>
				<?php endfor; ?>
			<?php endif; ?>
		<?php endfor; ?>
	</section>
	<section class="days" ontouchstart="pageSched.swipe.start(event);" ontouchmove="pageSched.swipe.move(event);" ontouchend="pageSched.swipe.end(event);"></section>
</main>

<footer>
</footer>

<script type="text/javascript">
	var api = {
		hourBegin: <?= $hourBegin ?>,
		hourEnd: <?= $hourEnd ?>,
		minuteInterval: <?= $minuteInterval ?>,
		middleMinute: <?= $middleMinute ?>,
		days: ['<?= implode($days, '\',\'') ?>'],
		defaultDay: <?= $default['day'] ?>,
		filters: <?= $filters ?>,
		groupFilters: <?= $groupFilters ?>
	};
</script>
