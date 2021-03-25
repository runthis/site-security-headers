<h3>Headers Tested</h3>

<ul>
	<?php if (!empty($Home->site_headers)): ?>
		<?php foreach ($Home->security_headers as $key => $header): ?>
			
			<?php if ($header['valid'] === true): ?>
				
				<li class="site-security-headers-text-success">
				<span class="dashicons dashicons-yes"></span> <?=$header['header']?>
					<?php if ($header['deprecated']): ?>
						<small class="site-security-headers-text-danger">[deprecated]</small>
					<?php endif; ?>
				</li>
				
			<?php else: ?>
				
				<li class="site-security-headers-text-danger">
					<span class="dashicons dashicons-no-alt"></span> <?=$header['header']?>
					<?php if ($header['deprecated']): ?>
						<small class="site-security-headers-text-danger">[deprecated]</small>
					<?php endif; ?>
				</li>
				
			<?php endif; ?>
			
		<?php endforeach; ?>
	<?php else: ?>
		<li class="site-security-headers-text-danger"><span class="dashicons dashicons-no-alt"></span> Unable to get headers</li>
	<?php endif; ?>
</ul>
