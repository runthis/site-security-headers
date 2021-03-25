<div class="site-security-headers-headers-section">
	<h1>All Headers</h1>

	<table class="wp-list-table widefat site-security-headers-wp-list-table">
		<thead>
			<tr>
				<th>Header</th>
				<th>Exists</th>
				<th>Valid</th>
				<th>Details</th>
			</tr>
		</thead>
		
		<tbody>
			
			<?php foreach ($Home->security_headers as $key => $header): ?>
				<tr>
					<td>
						<span class="site-security-headers-strong"><?=$header['header']?></span><br>
						<?php include 'piece/details.php'; ?>
					</td>
					
					<td class="text-center">
						<?php include 'icon/exists.php'; ?>
					</td>
					
					<td class="text-center">
						<?php include 'icon/valid.php'; ?>
					</td>
					
					<td>
						<?php if ($header['deprecated']): ?>
							<?php include 'piece/deprecated.php'; ?>
						<?php endif; ?>
						
						<span class="site-security-headers-strong">Description:</span> <?=htmlentities($header['information_description'])?>
						
						<?php if (!empty($header['actual'])): ?>
							<div class="site-security-headers-mt-5">
								<span class="site-security-headers-strong">Value:</span> <code><?=$header['actual']?></code>
							</div>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			
		</tbody>
	</table>
</div>
