<div class="welcome-panel-column site-security-headers-column-small">
	
	<h3>Site Grade</h3>
	
	<div class="site-security-headers-grade" data-headers-color="<?=$Home->score()['color']?>">
		<?=$Home->score()['grade']?>
	</div>
	
	<?php if ($Home->score()['grade'] === 'A+'): ?>
		<div class="site-security-headers-mt-5">
			<small>
				You have a great score!<br>
				I'm not needed anymore.<br>
				Please uninstall me.
			</small>
		</div>
	<?php endif; ?>
</div>
