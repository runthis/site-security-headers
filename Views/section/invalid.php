<div class="site-security-headers-headers-section">
	<h1>Invalid Headers</h1>
	
	<table class="wp-list-table widefat site-security-headers-wp-list-table">
		<thead>
			<tr>
				<th>Header</th>
				<th>Valid</th>
				<th>Details</th>
			</tr>
		</thead>
		
		<tbody>
			
			<?php foreach ($Home->headers_invalid() as $key => $header): ?>
				<tr>
					<td>
						<span class="site-security-headers-strong"><?=$header['header']?></span><br>
						<?php include 'piece/details.php'; ?>
					</td>
					<td class="text-center">
						<?php include 'icon/valid.php'; ?>
					</td>
					<td>
						<?php if ($header['deprecated']): ?>
							<?php include 'piece/deprecated.php'; ?>
						<?php endif; ?>
						
						<span class="site-security-headers-strong">Description:</span> <?=htmlentities($header['information_description'])?>
						
						<div class="site-security-headers-mt-5">
							<span class="site-security-headers-strong">Minimum Expectation:</span> <code><?=implode('</code> or <code>', json_decode($header['expected']))?></code>
						</div>
						
						<?php if ($header['unexpected'] && !empty($header['messages'])): ?>
							<div class="mt-2">
								<span class="site-security-headers-text-danger site-security-headers-strong">Undesired Values:</span> <code><?=implode('</code> or <code>', json_decode($header['unexpected']))?></code><br>
							</div>
						<?php endif; ?>
						
						<div class="site-security-headers-mt-2">
							<span class="site-security-headers-strong">Actual Values:</span> <code><?=$header['actual']?></code>
						</div>
						
					</td>
				</tr>
			<?php endforeach; ?>
			
		</tbody>
	</table>
</div>
