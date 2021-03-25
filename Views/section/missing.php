<div class="site-security-headers-headers-section">
	<h1>Missing Headers</h1>
	
	<table class="wp-list-table widefat site-security-headers-wp-list-table">
		<thead>
			<tr>
				<th>Header</th>
				<th>Exists</th>
				<th>Details</th>
			</tr>
		</thead>
		
		<tbody>
			
			<?php foreach ($Home->headers_missing() as $key => $header): ?>
				<tr>
					<td>
						<span class="site-security-headers-strong"><?=$header['header']?></span><br>
						<?php include 'piece/details.php'; ?>
					</td>
					
					<td class="site-security-headers-text-center">
						<?php include 'icon/exists.php'; ?>
					</td>
					<td>
						<?php if ($header['deprecated']): ?>
							<?php include 'piece/deprecated.php'; ?>
						<?php endif; ?>
						
						<span class="site-security-headers-strong">Description:</span> <?=htmlentities($header['information_description'])?>
					</td>
				</tr>
			<?php endforeach; ?>
			
		</tbody>
	</table>
</div>
