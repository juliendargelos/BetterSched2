<p class="ee">Coucou toi</p>
<?php partial('header') ?>
<form>
	<div>
		<?php
			select('Filière', 'sched-group', $groups, function($group, $value, &$option) use($default) {
				$option = [$group['label'] => $value];
				return $value == $default['group'];
			});
		?>
	</div>
	<div>
		<?php select('Année', 'sched-year', $years, $default['year']); ?>
	</div>
	<div>
		<?php
			select('Semaine', 'sched-week', $weeks, function($week, $value, &$option) use($default) {
				$option = [$value.' ('.$week->begin.' → '.$week->end.')' => $value];
				return $value == $default['week'];
			});
		?>
	</div>
	<div class="filters">
		<div>
			<label for="sched-filter-none">Filtre</label>
			<select id="sched-filter-none" disabled>
				<option value="" selected>Aucun filtre disponible</option>
			</select>
		</div>
		<?php foreach($filters as $name => $filter): ?>
			<div>
				<?php select('Filtre', 'sched-filter-'.$name, $filter); ?>
			</div>
		<?php endforeach; ?>
	</div>
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
	<section class="days"></section>
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
		filters: {
			<?php
				$n = 0;
				$length = count($groups);
				foreach($groups as $key => $group) {
					echo '\''.$key.'\': '.(array_key_exists('filter', $group) ? '\''.$group['filter'].'\'' : 'null');
					$n++;
					if($n != $length) echo ',';
				}
			?>
		}
	};
</script>
